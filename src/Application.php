<?php

namespace App;

use App\Models\Operation;

/**
 * Class Application
 * 
 * This class serves as the entry point for processing commission calculations.
 * It reads operation data from a CSV file, creates Operation instances,
 * and calculates the commission fees for each operation.
 */
class Application 
{
    // Instance of CommissionCalculator for calculating commissions
    private $calculator;

    /**
     * Application constructor.
     * 
     * Initializes the application with a CommissionCalculator instance, which
     * will handle the calculation of commission fees for each operation.
     *
     * @param CommissionCalculator $calculator An instance of the commission calculator.
     */
    public function __construct(CommissionCalculator $calculator) {
        $this->calculator = $calculator;
    }

    /**
     * Processes the operations in a given CSV file and outputs the commission fees.
     * 
     * This method reads each line from the provided CSV file, creates an Operation
     * instance for each entry, and calculates the commission fee using the
     * CommissionCalculator. The calculated fee is then printed to the console.
     *
     * @param string $filePath The path to the CSV file containing operation data.
     */
    public function run($filePath) {
        // Open the CSV file for reading
        $file = fopen($filePath, 'r');
        
        // Process each line in the file as a CSV row
        while (($data = fgetcsv($file)) !== false) {
            // Create an Operation instance with data from the current CSV row
            $operation = new Operation(...$data);
            
            // Calculate and output the commission fee for the operation
            echo $this->calculator->calculateCommission($operation) . PHP_EOL;
        }
        
        // Close the CSV file after processing all lines
        fclose($file);
    }
}
