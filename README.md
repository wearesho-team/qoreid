# QoreID PHP Integration
[![Test & Lint](https://github.com/wearesho-team/qoreid/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/wearesho-team/qoreid/actions/workflows/php.yml)

PHP client for a few [QoreID](https://qoreid.com) identity verification endpoints (Nigeria).

> [!WARNING]
> This package is limited in scope and is currently intended for internal use only.
> It covers only the endpoints we need, it is not published on Packagist,
> and its API may change without notice. It is not supported for third-party use.

Supported checks:

| Method                     | Endpoint                         | Request               | Response                 |
|----------------------------|----------------------------------|-----------------------|--------------------------|
| `Client::identifyNinPhone` | `v1/ng/identities/nin-phone/:id` | `Request\NinPhone`    | `Response\NinPhone`      |
| `Client::identifyNin`      | `v1/ng/identities/nin/:id`       | `Request\NinIdentify` | `Response\NinPhone`      |
| `Client::identifyNuban`    | `v1/ng/identities/nuban`         | `Request\Nuban`       | `Response\NubanDetails`  |

## Requirements
- PHP 8.4+
- A [PSR-16](https://www.php-fig.org/psr/psr-16/) cache (`psr/simple-cache` ^3.0) to store access tokens

## Installation
The package is not published on Packagist. Add this repository to your `composer.json`:
```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/wearesho-team/qoreid"
    }
  ]
}
```
```bash
composer require wearesho-team/qoreid
```

## Configuration
[ConfigInterface](./src/ConfigInterface.php) provides API credentials.
[EnvironmentConfig](./src/EnvironmentConfig.php) loads them from the environment:

| Variable               | Required | Description          |
|------------------------|----------|----------------------|
| QOREID_CLIENT_ID       | Yes      | QoreID client ID     |
| QOREID_CLIENT_SECRET   | Yes      | QoreID client secret |

The `QOREID_` prefix can be changed with the `EnvironmentConfig` constructor argument.

## Usage
```php
<?php

use Wearesho\QoreId;

$config = new QoreId\EnvironmentConfig();
$http = new GuzzleHttp\Client();
$cache = /* any Psr\SimpleCache\CacheInterface implementation */;

$client = new QoreId\Client(
    new QoreId\Auth\Client($config, $http, $cache),
    $http,
    $cache
);

// NIN lookup by phone number
$result = $client->identifyNinPhone(new QoreId\Request\NinPhone('08000000000', 'Bunch', 'Dillon'));
$result->getStatus();       // QoreId\Response\NinPhone::STATUS_VERIFIED or ::STATUS_MISMATCH
$result->getFieldMatches(); // ['firstname' => true, 'lastname' => true]
$result->getNin();          // 63184876213, null on mismatch
$result->getDetails();      // person details from QoreID, null on mismatch

// NIN verification
$result = $client->identifyNin(new QoreId\Request\NinIdentify(63184876213, 'Bunch', 'Dillon'));

// Bank account (NUBAN) verification
try {
    $details = $client->identifyNuban(new QoreId\Request\Nuban('058', '0123456789', 'Bunch', 'Dillon'));
    $details->getAccountName();
    $details->getFieldMatches();
} catch (QoreId\NoMatchException $exception) {
    // QoreID returned a status other than "verified"
}
```

Access tokens are requested on first use and cached until they expire.

### Errors
- `QoreId\ClientException` - QoreID rejected the request (4xx response, the message contains its status code and message),
  or its response is missing expected fields.
- `QoreId\NoMatchException` - NUBAN check status is not `verified`.
- `\JsonException` - response body is not valid JSON.
- Other Guzzle exceptions (5xx responses, network errors) are not wrapped.

## Development
```bash
composer install
composer lint   # PSR-12 code style
composer test   # PHPUnit
composer cover  # PHPUnit with coverage report
```

## License
[MIT](./LICENSE)
