# ixoplan-cde-sdk
PHP SDK for the Ixoplan API to be used within FAST

## Installation

Simply add `ixoplan/ixoplan-fast-sdk` to your composer.json, e.g:

```json
{
    "name": "myvendor/myproject",
    "description": "Using ixoplan-fast-sdk",
    "require": {
        "ixoplan/ixoplan-fast-sdk": "*"
    }
}
```

## Usage

The `\Ixolit\Dislo\Client` is designed for different transport layers. It needs a RequestClient interface to actually communicate with Ixoplan.
This SDK provides `\Ixolit\Dislo\CDE\CDEDisloClient`, an extension that defaults to an implementation based on FAST's internal methods.

```php
use Ixolit\Dislo\CDE\CDEDisloClient;

$apiClient = new CDEDisloClient();
```

For available API calls please refer to the [ixoplan-sdk](https://github.com/ixoplan/ixoplan-sdk).
