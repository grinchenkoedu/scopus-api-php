# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
- Updated `GuzzleHttp` to the latest version (7.6).

## [1.3.0] - 2022-05-01

### Added
- Added Abstract Citations Count API.

## [1.2.0] - 2021-11-14

### Fixed
- General bug fixes.

## [1.1.0] - 2020-05-19

### Added
- Added Search Author API.
- Added Citation Overview API.
- Created a support function to retrieve documents of a specific author easily.

### Changed
- Updated various classes for better compatibility.
