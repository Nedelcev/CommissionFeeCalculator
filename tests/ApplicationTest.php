<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Application;
use App\CommissionCalculator;
use App\CurrencyConverter;

/**
 * Class ApplicationTest
 * 
 * This class contains unit tests for the Application class.
 * It verifies that the commission calculation logic produces
 * expected results for a given input.
 */
class ApplicationTest extends TestCase  {
    /**
     * Test the commission calculation functionality.
     * 
     * This test initializes the CurrencyConverter and CommissionCalculator
     * with mock exchange rates, runs the Application with a sample CSV input,
     * and verifies that the output matches the expected commission values.
     */
    public function testCommissionCalculation() {
        // Initialize CurrencyConverter with mock exchange rates for testing
        $converter = new CurrencyConverter(['USD' => 1.1497, 'JPY' => 129.53]);
        
        // Initialize CommissionCalculator with the CurrencyConverter
        $calculator = new CommissionCalculator($converter);
        
        // Initialize Application with the CommissionCalculator
        $app = new Application($calculator);

        // Capture the output of the application to compare it against the expected result
        ob_start();  // Start output buffering
        $app->run('input.csv');  // Run the application with a sample input file
        $output = ob_get_clean();  // Get the output and clean the buffer

        // Define the expected output based on the sample input data
        $expectedOutput = "0.60\n3.00\n0.00\n...";
        
        // Assert that the actual output matches the expected output
        $this->assertEquals($expectedOutput, $output);
    }
}
