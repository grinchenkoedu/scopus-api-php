# Scopus API for PHP (Unofficial)

PHP SDK for [Scopus APIs](https://dev.elsevier.com/scopus.html)

Currently, supported APIs:
- Scopus Search API
- Abstract Retrieval API
- Author Retrieval API
- Affiliation Retrieval API
    - Search Author API
    - Citation Overview API
- Abstract Citations Count API

## Installation

`composer require grinchenkoedu/scopus-search-api` 

## Usage:

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

https://kasparsj.github.io/scopus-api-php/

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.
