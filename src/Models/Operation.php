<?php

namespace App\Models;

/**
 * Class Operation
 * 
 * This class represents a financial operation, such as a deposit or withdrawal.
 * Each instance contains the details of a single operation, including the date,
 * user ID, user type, operation type, amount, and currency.
 */
class Operation 
{
    // Date of the operation
    public $date;
    
    // Unique identifier for the user who performed the operation
    public $userId;
    
    // Type of user (either 'private' or 'business')
    public $userType;
    
    // Type of operation (either 'deposit' or 'withdraw')
    public $operationType;
    
    // Amount of money involved in the operation
    public $amount;
    
    // Currency of the operation amount (e.g., 'EUR', 'USD', 'JPY')
    public $currency;

    /**
     * Operation constructor.
     * 
     * Initializes an Operation instance with all necessary details, converting
     * the date to a DateTime object for easier handling in calculations.
     *
     * @param string $date           The date of the operation in 'Y-m-d' format.
     * @param int $userId            The ID of the user performing the operation.
     * @param string $userType       The type of the user (private or business).
     * @param string $operationType  The type of operation (deposit or withdraw).
     * @param float $amount          The amount of the operation.
     * @param string $currency       The currency of the amount.
     */
    public function __construct($date, $userId, $userType, $operationType, $amount, $currency) {
        // Convert the date string to a DateTime object for easy manipulation
        $this->date = new \DateTime($date);
        
        // Assign the provided values to the respective properties
        $this->userId = (int)$userId;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = (float)$amount;
        $this->currency = $currency;
    }
}
