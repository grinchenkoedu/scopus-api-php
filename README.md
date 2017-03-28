# Scopus API for PHP (Unofficial)
Scopus API for PHP

## Usage:

```
$apiKey = "11111111111111111111111111111111";
$api = new Scopus\ScopusApi($apiKey);
$results = $api
  ->query([
    'af-id' => 11111111
  ])
  ->start(0)
  ->count(25)
  ->viewComplete()
  ->search();
var_dump($results);

foreach ($results->getEntries() as $entry) {
    $abstractUrl = $entry->getLinks()->getSelf();
    $abstract = $api->retrieve($abstractUrl);
    var_dump($abstract);
    
    $authors = $entry->getAuthors();
    foreach ($authors as $author) {
        $authorUrl = $author->getUrl();
        $author = $api->retrieve($authorUrl):
        var_dump($author);
    }
}
```
