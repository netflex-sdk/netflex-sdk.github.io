# Netflex API

All of the Netflex SDKs packages rely on the core `netflex/api` package to communicate with the Netflex API.

If you want to implement a wrapper for missing features in the SDK, or want to do something that isn't exposed by the built-in packages, you can leverage the Netflex API directly. It will always be available.

## Installation

The package is a core depdenency of the Netflex SDK, and will already be provided for you in most common setups. If you are developing a Netflex library, or want to use it in a standalone project, install it using composer.

```bash
composer require netflex/api
```

## Facade

If you are writing a package for Netflex SDK, you should have `netflex/api` as a dependency. It has a <a href="https://laravel.com/docs/7.x/facades" target="_blank">Facade</a> which you will use to get an authenticated instance.

```php
<?php

use Netflex\API\Facades\API;

$variables = API::get('foundation/variables');
```

Alternatively, the facade is also aliased as `API`.

```php
<?php

use API;

$variables = API::get('foundation/variables');
```

## Documentation

For the most up to date documentation of the Netflex API, check out our [Postman documentation](https://documenter.getpostman.com/view/1198765/7159G1N?version=latest).

## Usage

The API facade has methods for the most common HTTP verbs (GET, PUT, POST, PATCH, and DELETE). They are exposed directly on the facade object.

> [!NOTE]
> The API class will interpret the response Content-Type header, and automatically decode the reponse.

If the method supports a payload (PUT, POST, PATCH), it will automatically get json encoded.

If the response is `application/json` it will be decoded as a <a href="https://www.php.net/manual/en/language.types.object.php" target="_blank">Object</a>. If you need to get the response as an associative array, the last argument can be set to true; eg:

```php
<?php

use Netflex\API\Facades\API;

$variablesAsObject = API::get('foundation/variables', false); // false is the default
$variablesAsArray = API::get('foundation/variables', true);

// Same for PUT, PATCH, and POST request
$responseAsObject = API::post('foundation/variable', [
  'alias' => 'test',
  'name' => 'test',
  'format' => 'text',
  'value' => 'Hello World!',
], false); // false is the default

$responseAsArray = API::post('foundation/variable', [
  'alias' => 'test',
  'name' => 'test',
  'format' => 'text',
  'value' => 'Hello World!',
], true);
```

## Configuration

The Netflex API can be configured through the configuration file `config/api.php`.

The default config will load the credentials from `.env`.

**Example configuration**

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Netflex Credentials
    |--------------------------------------------------------------------------
    |
    | Your application's API credentials for communicating with the
    | Netflex Content API.
    |
    */

    'publicKey' => env('NETFLEX_PUBLIC_KEY'),
    'privateKey' => env('NETFLEX_PRIVATE_KEY'),

];
```

If the credentials are missing, a `Netflex\API\Exceptions\MissingCredentialsException` will be thrown. (See [FAQ](/docs/faq.md?id=missingcredentialsexception-when-serving-project))

> [!NOTE]
> When you change a .env variable, you have to restart the development server for the change to take effect.
