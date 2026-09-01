# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- **Breaking:** the minimum PHP version is now 7.4, up from 7.2. PHP 7.2 and 7.3 reached end of
  life in 2020 and 2021; no consumer of this package targets them, and supporting them held the
  development toolchain back. Requires a major version bump on release.

### Added
- PHPUnit test suite covering the API client, the query builder, the response DTOs and the XML
  utility, using Guzzle's `MockHandler` so no test touches the network.
- GitHub Actions CI running the suite on PHP 7.4 through 8.5.
- `.gitattributes`, so the distributed package no longer ships `docs/`, `tests/` and the CI config.

### Fixed
- `XmlUtil::toArray()` no longer calls `each()`, which was removed in PHP 8.
- XML parse failures no longer emit raw PHP warnings, and no longer crash when
  `libxml_get_last_error()` returns `false`.
- Getters in `Scopus\Response` return `null` for a missing key instead of raising
  `Undefined array key`; Scopus routinely omits fields.
- Nullable parameters are declared explicitly, clearing the PHP 8.4 deprecation.

## [1.4.1] - 2026-09-01

### Changed
- Prepared for Packagist: updated package name to `grinchenkoedu/scopus-search-api`.
- Added Yevhen Matasar to the authors list.
- Upgraded Composer autoloading to PSR-4.

### Security
- Fixed SSRF vulnerability by updating the `guzzlehttp/guzzle` requirement to `^7.15.2 || ^8.0.1`.

## [1.4.0] - 2023-06-14

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
