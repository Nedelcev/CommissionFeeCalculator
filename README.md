# Commission Fee Calculator

This project is a PHP application that calculates commission fees for deposit and withdrawal operations for private and business clients. The commission fees are based on specific rules and conditions, with different rates and limits for each client type.

## Features

- **Supports multiple currencies**: Converts currencies based on live exchange rates from exchangeratesapi.io.
- **Different rules for private and business clients**: Private clients have a free limit for withdrawals, while business clients have a flat rate.
- **Commission calculation based on operation type**: Handles both deposits and withdrawals, with separate commission rates for each.

## Prerequisites

- PHP 7.4 or higher
- Composer (for dependency management)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/Nedelcev/CommissionFeeCalculator.git
   ```
   
2. Clone the repository:
   ```bash
   cd commission-fee-calculator
   ```

3. Install dependencies with Composer: 
   ```bash
   composer install
   ```

## Usage

  To run the application, use the following command, providing the path to your CSV file as an argument: 
   ```bash
   php script.php input.csv
   ```

## Example

  Given an input CSV file input.csv
   ```csv
   2014-12-31,4,private,withdraw,1200.00,EUR
   2015-01-01,4,private,withdraw,1000.00,EUR
   2016-01-05,4,private,withdraw,1000.00,EUR
   2016-01-05,1,private,deposit,200.00,EUR
   2016-01-06,2,business,withdraw,300.00,EUR
   2016-01-06,1,private,withdraw,30000,JPY
   2016-01-07,1,private,withdraw,1000.00,EUR
   2016-01-07,1,private,withdraw,100.00,USD
   2016-01-10,1,private,withdraw,100.00,EUR
   2016-01-10,2,business,deposit,10000.00,EUR
   2016-01-10,3,private,withdraw,1000.00,EUR
   2016-02-15,1,private,withdraw,300.00,EUR
   2016-02-19,5,private,withdraw,3000000,JPY
   ```

   Running the application will output the calculated commission fees for each operation. 
   ```bash
   php script.php input.csv
```

##Expected Output

  ```csv
  0.60
  3.00
  0.00
  0.06
  1.50
  0
  0.70
  0.30
  0.30
  3.00
  0.00
  0.00
  8612
   ```

## Project Structure

- `src/`: Contains the main application files.
- `tests/`: Contains test cases for validating the application.
- `script.php`: The entry point for running the application with a CSV file.
- `composer.json`: Manages project dependencies and autoloading with Composer.

## Testing

This project uses PHPUnit for testing. To run the tests, execute:
 ```bash
 ./vendor/bin/phpunit tests
```

The tests validate the commission calculation functionality based on sample input data.

## Configuration

- **Exchange Rates**: The application fetches live exchange rates from [exchangeratesapi.io](https://exchangeratesapi.io/). If rates cannot be retrieved, it falls back to the initial rates provided in the `CurrencyConverter` class.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

1. Fork the repository  
2. Create your feature branch (`git checkout -b feature/NewFeature`)  
3. Commit your changes (`git commit -m 'Add some feature'`)  
4. Push to the branch (`git push origin feature/NewFeature`)  
5. Open a pull request

---

Feel free to reach out if you have any questions or need assistance!
  
