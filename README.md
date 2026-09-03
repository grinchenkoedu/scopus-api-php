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

## Upgrading

**Coming from 1.x? See [UPGRADING.md](UPGRADING.md).** 2.0 has three breaking changes:

| | Before | After |
|---|---|---|
| Collection getters (`getEntries()`, `getAuthors()`, …) | `null` when absent | `[]` |
| `CitationCount::getStatus()` | `bool` — `true` for any status | the status string, or `null` — use `isFound()` |
| `retrieveAbstracts()` / `retrieveAuthors()` | swallowed failures, returned `[]` | exceptions propagate |

Still on PHP 7.2 or 7.3? Composer will resolve to `1.4.2`, which carries the same bug fixes.

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

### API documentation

`docs/` is generated output, published at the API Docs link above via GitHub Pages
(`master` branch, `/docs` folder). **You do not need to regenerate it by hand** — the
**Docs** workflow rebuilds it on every change to `src/` or `composer.json` and commits
the result.

Generation is kept on CI on purpose: ApiGen lists implementing classes in filesystem
order without sorting them, so the same source produces different output on macOS and
on Linux. Running it in one place keeps the result reproducible.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes, and [UPGRADING.md](UPGRADING.md) for migration notes between major versions.

## Contributors

Thanks to all the contributors who have helped build and maintain this package:

![Contributors](https://contrib.rocks/image?repo=grinchenkoedu/scopus-api-php)

## License

This project is licensed under the MIT License.
