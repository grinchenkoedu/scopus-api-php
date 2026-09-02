# Scopus API for PHP

[![CI](https://github.com/grinchenkoedu/scopus-api-php/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/grinchenkoedu/scopus-api-php/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/grinchenkoedu/scopus-search-api)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)
[![PHP Version Require](https://img.shields.io/packagist/php-v/grinchenkoedu/scopus-search-api)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)
[![License](https://img.shields.io/packagist/l/grinchenkoedu/scopus-search-api)](https://packagist.org/packages/grinchenkoedu/scopus-search-api)

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

- PHP 7.4 – 8.x
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
$apiKey = "your-api-key-here";
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

## Development

Install the development dependencies and run the test suite:

```bash
composer install
vendor/bin/phpunit
```

Coverage is not configured in `phpunit.xml.dist`, because the source filter is
spelled differently in PHPUnit 9 and PHPUnit 10+ and this package supports both.
Request it on the command line instead:

```bash
vendor/bin/phpunit --coverage-text --coverage-filter src   # PHPUnit 10 and 11
vendor/bin/phpunit --coverage-text --whitelist src         # PHPUnit 8.5 and 9
```

### Regenerating the API docs

`docs/` is generated output, published at the API Docs link above via GitHub Pages
(`master` branch, `/docs` folder). Regenerate it after changing anything under `src/`:

```bash
curl -sL -o apigen.phar https://github.com/ApiGen/ApiGen/releases/download/v7.0.0-alpha.6/apigen.phar
php apigen.phar --workers 1 --output docs \
  --title "Scopus API for PHP" \
  --base-url "https://grinchenkoedu.github.io/scopus-api-php/" \
  src
```

`--workers 1` is required: the parallel scheduler crashes when ApiGen runs from a PHAR.

CI enforces this. The **Docs** workflow regenerates `docs/` on any change to `src/`,
`docs/` or `composer.json` and fails if the result differs from what is committed.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.

## Contributors

Thanks to all the contributors who have helped build and maintain this package:

![Contributors](https://contrib.rocks/image?repo=grinchenkoedu/scopus-api-php)

## License

This project is licensed under the MIT License.
