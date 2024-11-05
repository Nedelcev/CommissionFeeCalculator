<?php

require 'vendor/autoload.php';

use App\Application;
use App\CommissionCalculator;
use App\CurrencyConverter;

// Instantiate necessary classes
$converter = new CurrencyConverter();
$calculator = new CommissionCalculator($converter);
$app = new Application($calculator);

// Check if a file path is provided as a command-line argument
if ($argc < 2) {
    echo "Please provide the path to the input CSV file.\n";
    exit(1);
}

$inputFile = $argv[1];

// Run the application with the provided input file
$app->run($inputFile);
