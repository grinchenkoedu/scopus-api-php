# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- **Breaking:** the 13 getters that yield collections return `[]` instead of `null` when the field
  is absent, and declare `: array`. An empty Scopus result is routine, so iterating no longer
  needs a null guard on the common path. Affects `SearchResults::getEntries()`,
  `Entry::getAuthors()`/`getAffiliations()`/`getCoAuthor()`, `Abstracts::getAuthors()`,
  `CiteInfo::getAuthors()`, `Author::getAffiliationHistory()`/`getSubjectAreas()`,
  `AuthorProfile::getNameVariants()`/`getJournalHistory()`, `Affiliation::getNameVariant()`,
  `AbstractCitations::getIdentifiers()`/`getCiteInfos()` and `IAbstract::getAuthors()`.
- **Breaking:** `CitationCount::getStatus()` no longer declares `: bool`; it returns the raw
  status string, or `null` when absent. The bool coerced Scopus's string, so `found` and
  `NOT_FOUND` both came back as `true`.
- **Breaking:** `ScopusApi::retrieveAbstracts()` and `retrieveAuthors()` let exceptions propagate
  instead of catching `Exception` and returning `[]`, which made a network failure or an invalid
  API key indistinguishable from a document that was not found. Both now declare `: array`.

### Added
- `CitationCount::isFound()` — the boolean question `getStatus()` used to answer incorrectly.

### Fixed
- `retrieveAbstracts()` and `retrieveAuthors()` return `[]` for an empty id list instead of
  reaching an undefined index.
- `retrieveAuthors()` deduplicated ids to choose its branch but then chunked the original list,
  so duplicate ids reached `array_combine()` with mismatched counts and raised a `ValueError`.

### Upgrading

```php
// Collections
if ($results->getEntries() === null) {}   // before
if (!$results->getEntries()) {}           // after

// Citation status
$count->getStatus() === true              // before - true for NOT_FOUND too
$count->isFound()                         // after

// Bulk retrieval no longer hides failures
try {
    $api->retrieveAbstracts($ids);
} catch (\Exception $e) {
    // previously returned [] and told you nothing
}
```


## [1.5.0] - 2026-09-02

### Added
- PHPUnit test suite covering the API client, the query builder, the response DTOs and the XML
  utility, using Guzzle's `MockHandler` so no test touches the network.
- GitHub Actions CI running the suite on PHP 7.4 through 8.5.
- `.gitattributes`, so the distributed package no longer ships `docs/`, `tests/` and the CI config.

### Changed
- The minimum PHP version is now 7.4, up from 7.2, and the constraint is bounded at
  `^7.4 || ^8.0`. PHP 7.2 and 7.3 reached end of life in November 2020 and December 2021.
  Supporting them forced `require-dev` to span PHPUnit 8.5 to 11 and split the dependency graph
  across two Guzzle majors. No public API changed: projects still on PHP 7.2 or 7.3 keep
  resolving to 1.4.1 rather than failing to install.

### Removed
- `docs/`, `tests/` and the CI configuration are no longer part of the distributed package, and
  the manual `test/test.php` script is gone. Nothing under `vendor/` should have referenced them,
  but the paths do disappear.

### Fixed
- `XmlUtil::toArray()` no longer calls `each()`, which was removed in PHP 8.
- XML parse failures no longer emit raw PHP warnings, and no longer crash when
  `libxml_get_last_error()` returns `false`.
- Getters in `Scopus\Response` return `null` for a missing key instead of raising
  `Undefined array key`; Scopus routinely omits fields.
- Nullable parameters are declared explicitly, clearing the PHP 8.4 deprecation.

## [1.4.2] - 2026-09-01

Maintenance release on the `1.4.x` branch, for PHP 7.2 and 7.3, which cannot install 1.5.0.
Same fixes as 1.5.0, cut before the PHP requirement moved to 7.4.

## [1.4.1] - 2026-09-01

### Changed
- Prepared for Packagist: updated package name to `grinchenkoedu/scopus-search-api`.
- Added Yevhen Matasar to the authors list.
- Upgraded Composer autoloading to PSR-4.

### Security
- Fixed SSRF vulnerability by updating the `guzzlehttp/guzzle` requirement to `^7.15.2 || ^8.0.1`.

## [1.4.0] - 2023-05-14

### Added
- Added institution token support.

### Changed
- Updated `guzzlehttp/guzzle` to the latest version (7.6).
- Restored `retrieve` and `query` methods back to `public` visibility.

## [1.3.0] - 2022-05-01

### Added
- Added Abstract Citations Count API.

## [1.2.0] - 2021-11-14

### Added
- Added `hasError` method for better error handling.

### Changed
- Bumped `guzzlehttp/guzzle` version constraint to `>=6.3`.

### Fixed
- Fixed year parsing in the `getYear` method.

## [1.1.0] - 2020-05-19

### Added
- Added Search Author API.
- Added Citation Overview API (including fetching multiple documents).
- Added `openaccessFlag` to the `Entry` class.
- Created a support function to retrieve documents of a specific author easily.
- Generated initial API documentation using ApiGen.

### Changed
- Updated various classes (`AbstractCitations`, `Source`, etc.) for better compatibility.

[Unreleased]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.5.0...HEAD
[1.5.0]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.4.2...1.5.0
[1.4.2]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/grinchenkoedu/scopus-api-php/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/grinchenkoedu/scopus-api-php/releases/tag/1.1.0
