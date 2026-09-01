# Scopus API for PHP

[![Latest Stable Version](https://poser.pugx.org/grinchenkoedu/scopus-search-api/v/stable)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)
[![License](https://poser.pugx.org/grinchenkoedu/scopus-search-api/license)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)
[![PHP Version Require](https://poser.pugx.org/grinchenkoedu/scopus-search-api/require/php)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)
[![CI Tests](https://github.com/grinchenkoedu/scopus-api-php/actions/workflows/ci.yml/badge.svg)](https://github.com/grinchenkoedu/scopus-api-php/actions/workflows/ci.yml)

A third party PHP SDK for [Scopus APIs](https://dev.elsevier.com/scopus.html).

Currently supported APIs:
- Scopus Search API
- Abstract Retrieval API
- Author Retrieval API
- Affiliation Retrieval API
    - Search Author API
    - Citation Overview API
- Abstract Citations Count API

## Requirements

- PHP >= 7.2
- `guzzlehttp/guzzle` ^7.15.2 || ^8.0.1

## Installation

Install the package via Composer:

```bash
composer require grinchenkoedu/scopus-search-api
```

## Usage

```php
use Scopus\ScopusApi;
use Scopus\ScopusApiFactory;

// replace with your API key
$apiKey = "114ff0c3b57a0ec62e15efdedefd2e6f";
$api = (new ScopusApiFactory($apiKey))->createApiClient();

// Scopus Search API
$results = $api
    ->query("af-id(60071066)")
    ->start(0)
    ->count(5)
    ->viewComplete()
    ->search();

var_dump($results);

foreach ($results->getEntries() as $entry) {
    $abstractUrl = $entry->getLinks()->getSelf();
    
    // Abstract Retrieval API
    $abstract = $api->retrieve($abstractUrl);
    
    var_dump($abstract);

    $authors = $entry->getAuthors();
    foreach ($authors as $author) {
        $authorUrl = $author->getUrl();
        
        // Author Retrieval API
        $author = $api->retrieve($authorUrl);
        
        var_dump($author);
    }
}
```

## API Docs

Official API documentation:
[https://grinchenkoedu.github.io/scopus-api-php/](https://grinchenkoedu.github.io/scopus-api-php/)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.

## Contributors

Thanks to all the contributors who have helped build and maintain this package:

![Contributors](https://contrib.rocks/image?repo=grinchenkoedu/scopus-api-php)

## License

This project is licensed under the MIT License.
