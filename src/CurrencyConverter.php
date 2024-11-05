<?php

namespace App;

/**
 * Class CurrencyConverter
 * 
 * This class provides methods to convert amounts between EUR and other currencies.
 * It uses live exchange rates from an external API to ensure accuracy, with fallback rates
 * if the API call fails.
 */
class CurrencyConverter 
{
    // Stores exchange rates with base currency as EUR
    private $rates;

    // API URL for fetching the latest exchange rates
    private $apiUrl = 'https://api.exchangeratesapi.io/latest';

    /**
     * CurrencyConverter constructor.
     * 
     * Initializes the currency converter with an optional set of initial rates.
     * If no initial rates are provided, they will be fetched from the API.
     *
     * @param array $initialRates An optional array of initial exchange rates.
     */
    public function __construct(array $initialRates = []) {
        $this->rates = $initialRates;
        $this->fetchLatestRates();
    }

    /**
     * Fetches the latest exchange rates from the API and updates the rates property.
     * 
     * This method tries to retrieve exchange rates with EUR as the base currency.
     * If the API call fails, it falls back to using the initial rates provided in the constructor.
     */
    private function fetchLatestRates() {
        try {
            // Attempt to retrieve exchange rates from the API
            $response = file_get_contents($this->apiUrl . '?base=EUR');
            $data = json_decode($response, true);

            // Check if rates were returned and update the internal rates property
            if (isset($data['rates'])) {
                $this->rates = $data['rates'];
            } else {
                throw new \Exception("Invalid response structure");
            }
        } catch (\Exception $e) {
            // Display a warning and continue using fallback rates if the API call fails
            echo "Warning: Could not fetch latest exchange rates. Using fallback rates.\n";
        }
    }

    /**
     * Converts an amount from a specified currency to EUR.
     * 
     * @param float $amount   The amount to be converted.
     * @param string $currency The currency of the amount to be converted.
     * 
     * @return float The amount converted to EUR, or the original amount if conversion rate is unavailable.
     */
    public function convertToEur($amount, $currency) {
        // If the currency is already EUR, return the amount directly
        if ($currency === 'EUR') {
            return $amount;
        }
        // Return the converted amount if the rate is available, otherwise return the original amount
        return isset($this->rates[$currency]) ? $amount / $this->rates[$currency] : $amount;
    }

    /**
     * Converts an amount from EUR to a specified currency.
     * 
     * @param float $amount   The amount in EUR to be converted.
     * @param string $currency The target currency.
     * 
     * @return float The amount converted to the specified currency, or the original amount if conversion rate is unavailable.
     */
    public function convertFromEur($amount, $currency) {
        // If the target currency is EUR, return the amount directly
        if ($currency === 'EUR') {
            return $amount;
        }
        // Return the converted amount if the rate is available, otherwise return the original amount
        return isset($this->rates[$currency]) ? $amount * $this->rates[$currency] : $amount;
    }
}
