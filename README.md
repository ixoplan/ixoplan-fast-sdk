# dislo-cde-sdk
PHP SDK for the Dislo API to be used within the CDE

## Installation

Simply add ixolit/dislo-cde-sdk to your composer.json, e.g:

    {
        "name": "myvendor/myproject",
        "description": "Using dislo-cde-sdk",
        "require": {
            "ixolit/dislo-cde-sdk": "*"
        }
    }


## Usage

The `\Ixolit\Dislo\Client` is designed for different transport layers. It needs a RequestClient interface to actually communicate with Dislo.
This SDK provides `\Ixolit\Dislo\CDE\CDEDisloClient`, an extension that defaults to an implementation based on CDE's internal methods.

    use Ixolit\Dislo\CDE\CDEDisloClient;

    $apiClient = new CDEDisloClient();

For available API calls please refer to the [dislo-sdk](https://github.com/Ixolit/dislo-sdk).  