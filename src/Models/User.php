<?php

namespace App\Models;

/**
 * Class User
 * 
 * This class represents a user, storing information such as the user ID, type,
 * and tracking weekly withdrawal limits for private clients. It helps to manage
 * the count and total amount of withdrawals for each week, which is essential for
 * calculating commissions with free limits.
 */
class User
{
    // Unique identifier for the user
    private $id;

    // Type of the user (either 'private' or 'business')
    private $type;

    // Array to store the weekly withdrawal data (total amount and count of operations) for the user
    private $weeklyOperations;

    /**
     * User constructor.
     * 
     * Initializes the User instance with an ID and type, setting up an empty
     * array to track weekly withdrawal data.
     *
     * @param int $id    The user's unique identifier.
     * @param string $type The type of the user (private or business).
     */
    public function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
        $this->weeklyOperations = []; // Initialize weekly operations tracking array
    }

    /**
     * Returns the user's ID.
     * 
     * @return int The user's unique identifier.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the user's type.
     * 
     * @return string The type of the user (private or business).
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Adds a withdrawal operation for a given week, updating the total amount and count.
     * 
     * This method increments the count of withdrawals and updates the total amount
     * for the specified week. It helps manage the weekly free limit for private clients.
     *
     * @param string $week   The ISO week identifier (e.g., "2024W05").
     * @param float $amount  The amount of the current withdrawal operation.
     */
    public function addWeeklyOperation($week, $amount) {
        if (!isset($this->weeklyOperations[$week])) {
            $this->weeklyOperations[$week] = [
                'totalAmount' => 0,
                'operationCount' => 0
            ];
        }

        // Update the weekly total amount and operation count
        $this->weeklyOperations[$week]['totalAmount'] += $amount;
        $this->weeklyOperations[$week]['operationCount'] += 1;
    }

    /**
     * Retrieves the total amount and count of operations for a specific week.
     * 
     * If there is no record for the specified week, it returns a default structure
     * with zero values.
     *
     * @param string $week The ISO week identifier.
     * 
     * @return array An associative array with keys 'totalAmount' and 'operationCount'.
     */
    public function getWeeklyOperationData($week) {
        return $this->weeklyOperations[$week] ?? ['totalAmount' => 0, 'operationCount' => 0];
    }

    /**
     * Checks if the user is within the free limit for the specified week.
     * 
     * This method verifies if a new withdrawal can fit within the free limit
     * (1000 EUR and up to 3 operations per week) for private clients. It helps
     * determine if commission should be applied.
     *
     * @param string $week       The ISO week identifier.
     * @param float $amountInEur The amount of the current withdrawal in EUR.
     * 
     * @return bool True if the withdrawal is within the free limit; otherwise, false.
     */
    public function isWithinFreeLimit($week, $amountInEur) {
        $weekData = $this->getWeeklyOperationData($week);

        // Check if the count is below 3 and total is within the 1000 EUR limit
        if ($weekData['operationCount'] < 3 && ($weekData['totalAmount'] + $amountInEur) <= 1000) {
            return true;
        }
        return false;
    }
}
