<img src="https://banners.beyondco.de/Laravel%20DocuWare.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-docuware&pattern=circuitBoard&style=style_1&description=An+opinionated+way+to+integrate+DocuWare+with+Laravel&md=1&showWatermark=0&fontSize=175px&images=document-report">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-docuware.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-docuware)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-docuware.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-docuware)
[![run-tests](https://github.com/codebar-ag/laravel-docuware/actions/workflows/run-tests.yml/badge.svg)](https://github.com/codebar-ag/laravel-docuware/actions/workflows/run-tests.yml)
[![Psalm](https://github.com/codebar-ag/laravel-docuware/actions/workflows/psalm.yml/badge.svg)](https://github.com/codebar-ag/laravel-docuware/actions/workflows/psalm.yml)
[![Check & fix styling](https://github.com/codebar-ag/laravel-docuware/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/codebar-ag/laravel-docuware/actions/workflows/php-cs-fixer.yml)


This package was developed to give you a quick start to communicate with the
DocuWare REST API. It is used to query the most common endpoints.

⚠️ This package is not designed as a replacement of the official 
[DocuWare REST API](https://developer.docuware.com/rest/index.html).
See the documentation if you need further functionality. ⚠️

## 💡 What is DocuWare?

DocuWare provides cloud document management and workflow automation software
that enables you to digitize, secure and work with business documents,
then optimize the processes that power the core of your business.

## 🛠 Requirements

- PHP: `^8.0`
- Laravel: `^8.12`
- DocuWare Cloud Access

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-docuware
```

Add the following environment variables to your `.env` file:

```bash
DOCUWARE_URL=https://domain.docuware.cloud
DOCUWARE_USER=user@domain.test
DOCUWARE_PASSWORD=password
```

## 🏗 Usage

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Return all file cabinets.
 */
$cabinets = DocuWare::getFileCabinets();

/**
 * Return all fields of a file cabinet.
 */
$fields = DocuWare::getFields($fileCabinetId);

/**
 * Return all dialogs of a file cabinet.
 */
$dialogs = DocuWare::getDialogs($fileCabinetId);

/**
 * Return all used values for a specific field.
 */
$values = DocuWare::getSelectList($fileCabinetId, $dialogId, $fieldName);

/**
 * Return a document.
 */
$document = DocuWare::getDocument($fileCabinetId, $documentId);

/**
 * Return image preview of a document.
 */
$content = DocuWare::getDocumentPreview($fileCabinetId, $documentId);

/**
 * Download single document.
 */
$content = DocuWare::downloadDocument($fileCabinetId, $documentId);

/**
 * Download multiple documents.
 */
$content = DocuWare::downloadDocuments($fileCabinetId, $documentIds);

/**
 * Update value of a indexed field.
 */
$value = DocuWare::updateDocumentValue($fileCabinetId, $documentId, $fieldName, $newValue);

/**
 * Upload new document.
 */
$document = DocuWare::uploadDocument($fileCabinetId, $fileContent, $fileName);

/**
 * Delete document.
 */
DocuWare::deleteDocument($fileCabinetId, $documentId);
```

## 🔍 Search usage

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Most basic example to search for documents.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->get();

/**
 * Search in multiple file cabinets.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->additionalFileCabinets($additionalFileCabinetIds)
    ->get();

/**
 * Find results on the next page. 
 * Default: 1
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->page(2)
    ->get();
    
/**
 * Define the number of results which should be shown per page. 
 * Default: 50
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->perPage(30)
    ->get();

/**
 * Use the full-text search. You have to activate full-text search in your file
 * cabinet before you can use this feature.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->fulltext('My secret document')
    ->get();

/**
 * Search documents which are created from the first of march.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->dateFrom(Carbon::create(2021, 3, 1))
    ->get();

/**
 * Search documents which are created until the first of april.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->dateUntil(Carbon::create(2021, 4, 1))
    ->get();

/**
 * Order the results by field name. Possibly values: 'desc' or 'asc'
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->orderBy('DWSTOREDATETIME', 'desc')
    ->get();

/**
 * Search documents filtered to the value. You can specify multiple filters.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->filter('TYPE', 'Order')
    ->filter('OTHER_FIELD', 'other')
    ->get();
    
/**
 * Or combine all together.
 */
$paginator = DocuWare::search()
    ->fileCabinet($fileCabinetId)
    ->dialog($dialogId)
    ->additionalFileCabinets($additionalFileCabinetIds)
    ->page(2)
    ->perPage(30)
    ->fulltext('My secret document')
    ->dateFrom(Carbon::create(2021, 3, 1))
    ->dateUntil(Carbon::create(2021, 4, 1))
    ->filter('TYPE', 'Order')
    ->filter('OTHER_FIELD', 'other')
    ->orderBy('DWSTOREDATETIME', 'desc')
    ->get();
```

Please see [Tests](tests/Feature/DocuWareTest.php) for more details.

## 🏋️ DTO's

```php
CodebarAg\DocuWare\DTO\FileCabinet {
  +id: "2f071481-095d-4363-abd9-29ef845a8b05"              // string
  +name: "Fake File Cabinet"                               // string
  +color: "Yellow"                                         // string
  +isBasket: true                                          // bool
  +assignedCabinet: "889c13cc-c636-4759-a704-1e6500d2d70f" // string
}
```

```php
CodebarAg\DocuWare\DTO\Dialog {
  +id: "fae3b667-53e9-48dd-9004-34647a26112e"            // string
  +type: "ResultList"                                    // string
  +label: "Fake Dialog"                                  // string
  +isDefault: true                                       // boolean
  +fileCabinetId: "1334c006-f095-4ae7-892b-fe59282c8bed" // string
}
```

```php
CodebarAg\DocuWare\DTO\Field {
  +name: "FAKE_FIELD"  // string
  +label: "Fake Field" // string
  +type: "Memo"        // string
  +scope: "User"       // string
```

```php
CodebarAg\DocuWare\DTO\DocumentField {
  +name: "FAKE_DOCUMENT_FIELD"  // string
  +label: "Fake Document Field" // string
  +value: 7680                  // integer
  +type: "Int"                  // string
}
```

```php
CodebarAg\DocuWare\DTO\Document {
  +id: 659732                                              // integer
  +file_size: 765336                                       // integer
  +total_pages: 100                                        // integer
  +title: "Fake Title"                                     // string
  +extension: ".pdf"                                       // string
  +content_type: "application/pdf"                         // string
  +file_cabinet_id: "a233b03d-dc63-42dd-b774-25b3ff77548f" // string
  +created_at: Illuminate\Support\Carbon                   // Carbon
  +updated_at: Illuminate\Support\Carbon                   // Carbon
  +fields: Illuminate\Support\Collection {                 // Collection|DocumentField[]
    #items: array:2 [
      0 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
      1 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
    ]
  }
}
```

```php
CodebarAg\DocuWare\DTO\DocumentPaginator
  +total: 39                               // integer
  +per_page: 10                            // integer
  +current_page: 9                         // integer
  +last_page: 15                           // integer
  +from: 1                                 // integer
  +to: 10                                  // integer
  +items: Illuminate\Support\Collection {  // Collection|Document[]
    #items: array:2 [
      0 => CodebarAg\DocuWare\DTO\Document // Document
      1 => CodebarAg\DocuWare\DTO\Document // Document
    ]
  }
}
```

## 🔐 Authentication

You only need to provide correct credentials. Everything else is automatically
handled from the package. Under the hood we are storing the authentication
cookie in the cache named *docuware.cookies*.

But if you need further control you can use the following methods to login and
logout with DocuWare:

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Login with your credentials. You only need to login once. Afterwards the
 * authentication cookie is stored in the cache `docuware.cookies` and is
 * used for all further requests.
 */
DocuWare::login();

/**
 * Logout your current session. Removes the authentication cookie in the cache.
 */
DocuWare::logout();
```

## 💥 Exceptions explained

- `CodebarAg\DocuWare\Exceptions\UnableToMakeRequest`

This is thrown if you are not authorized to make the request.

---

- `CodebarAg\DocuWare\Exceptions\UnableToProcessRequest`

This is thrown if you passed wrong attributes. For example a file cabinet ID
which does not exist.

---

- `CodebarAg\DocuWare\Exceptions\UnableToLogin`

This exception can only be thrown during the login if the credentials did not
match.

---

- `Illuminate\Http\Client\RequestException`

All other cases if the response is not successfully.

## ✨ Events

Following events are fired:

```php 
use CodebarAg\DocuWare\Events\DocuWareResponseLog;

// Log each response from the DocuWare REST API.
DocuWareResponseLog::class => [
    //
],
```

## 🔧 Configuration file

You can publish the config file with:
```bash
php artisan vendor:publish --provider="CodebarAg\DocuWare\DocuWareServiceProvider" --tag="docuware-config"
```

This is the contents of the published config file:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DocuWare Credentials
    |--------------------------------------------------------------------------
    |
    | This configuration option defines your credentials
    | to authenticate with the DocuWare REST-API.
    |
    */

    'credentials' => [
        'url' => env('DOCUWARE_URL'),
        'user' => env('DOCUWARE_USER'),
        'password' => env('DOCUWARE_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Cookie Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of minutes after the cookie lifetime
    | times out and it is required to refresh a new one. By default,
    | the lifetime lasts for 1 month (43 800 minutes).
    |
    */

    'cookie_lifetime' => (int) env('DOCUWARE_COOKIE_LIFETIME', 43800),

];
```

## 🚧 Testing

Copy your own phpunit.xml-file.
```bash
cp phpunit.xml.dist phpunit.xml
```

Modify environment variables in the phpunit.xml-file:
```xml
<env name="DOCUWARE_URL" value="https://domain.docuware.cloud"/>
<env name="DOCUWARE_USER" value="user@domain.test"/>
<env name="DOCUWARE_PASSWORD" value="password"/>
```

Run the tests:
```bash
composer test
```

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## ✏️ Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## 🧑‍💻 Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## 🙏 Credits

- [Ruslan Steiger](https://github.com/SuddenlyRust)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)

## 🎭 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
