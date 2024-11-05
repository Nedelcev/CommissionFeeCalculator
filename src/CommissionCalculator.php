<?php

namespace App;

use App\Models\Operation;

/**
 * Class CommissionCalculator
 * 
 * This class calculates commission fees for deposit and withdrawal operations.
 * It supports different rules for private and business clients, and handles weekly
 * free limits for private clients.
 */
class CommissionCalculator 
{
    // Instance of CurrencyConverter for currency conversions
    private $converter;

    // Array to track weekly withdrawal limits for private clients by user and week
    private $userWeeklyLimits = [];

    /**
     * CommissionCalculator constructor.
     * 
     * Initializes the calculator with a CurrencyConverter instance to handle
     * conversions for operations in non-EUR currencies.
     *
     * @param CurrencyConverter $converter An instance for handling currency conversion.
     */
    public function __construct(CurrencyConverter $converter) {
        $this->converter = $converter;
    }

    /**
     * Calculates the commission fee for a given operation.
     * 
     * This method applies different rules based on the operation type (deposit or withdraw)
     * and user type (private or business). It calculates and returns the commission fee
     * rounded up to two decimal places.
     * 
     * @param Operation $operation The operation for which to calculate the commission fee.
     * 
     * @return string The calculated commission fee, formatted with two decimal places or as '0' if zero.
     */
    public function calculateCommission(Operation $operation) {
        $commission = 0;

        // Apply deposit and withdrawal rules
        if ($operation->operationType === 'deposit') {
            // Deposits are charged a flat 0.03% fee
            $commission = $operation->amount * 0.0003;
        } else {
            // Apply private or business withdrawal rules
            $commission = $operation->userType === 'private'
                ? $this->calculatePrivateWithdraw($operation)
                : $operation->amount * 0.005;  // Business clients are charged 0.5% on all withdrawals
        }

        // Format the result to two decimal places or return '0' if zero
        return $commission == 0 ? '0' : number_format(ceil($commission * 100) / 100, 2, '.', '');
    }

    /**
     * Calculates the commission fee for a private client's withdrawal.
     * 
     * Private clients have a free withdrawal limit of 1000 EUR per week for the first three
     * withdrawals. This method tracks each client's weekly usage and calculates commissions
     * on amounts exceeding the free limit.
     * 
     * @param Operation $operation The withdrawal operation to process.
     * 
     * @return string The calculated commission fee, formatted with two decimal places or as '0' if zero.
     */
    private function calculatePrivateWithdraw(Operation $operation) {
        $commission = 0;

        // Determine the week number for the operation date (ISO-8601 format)
        $week = $operation->date->format("oW");
        $userId = $operation->userId;

        // Initialize weekly data for the user if it doesn't already exist
        if (!isset($this->userWeeklyLimits[$userId][$week])) {
            $this->userWeeklyLimits[$userId][$week] = [
                'total' => 0,
                'count' => 0
            ];
        }

        // Reference the user's weekly data for modification
        $weeklyData = &$this->userWeeklyLimits[$userId][$week];

        // Check if the user is within the free limit (first three operations and total <= 1000 EUR)
        if ($weeklyData['count'] < 3) {
            // Convert the amount to EUR for accurate comparison
            $amountInEur = $this->converter->convertToEur($operation->amount, $operation->currency);
            
            // If the total usage + current amount is within the free limit, no commission is applied
            if ($weeklyData['total'] + $amountInEur <= 1000) {
                $weeklyData['total'] += $amountInEur;
                $weeklyData['count']++;
                return '0';
            }

            // Calculate commission on the amount exceeding the free limit
            $exceedAmount = $amountInEur + $weeklyData['total'] - 1000;
            $commission = $this->converter->convertFromEur($exceedAmount, $operation->currency) * 0.003;
            $weeklyData['total'] = 1000;  // Cap the total at the limit after applying commission
        } else {
            // If over three withdrawals in a week, apply 0.3% commission to the full amount
            $commission = $operation->amount * 0.003;
        }

        // Format the result to two decimal places or return '0' if zero
        return $commission == 0 ? '0' : number_format(ceil($commission * 100) / 100, 2, '.', '');
    }
}
