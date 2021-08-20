# Analyzer

## How to use it?

Install the package using composer.

```bash
composer require rtoledof/analizer
```

Once the package it's installed via composer initialize the logger. It requires the logger what is an interface that it 
should be implemented named ***ILogger*** and later on call the functions.


```php
$logger = new LoggerTest();
$analyzer = new \Rtoledof\Analyzer\Analyzer($logger)

$analyzer->analyze(new DateTime());
```

The analyzer has 3 method.

- read: Return the logs in the given date.
- store: Store a new log entry on the logger.
- analyze: Remove the logs older that the given date.

