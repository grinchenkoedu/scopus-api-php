# Update Docs to Best Practices

**Type:** feature
**Asked:** Update ther readme to follow best practices / Update ther changelog to follow best practices

## Summary
- Convert `CHANGELOG.md` to the standard "Keep a Changelog" format.
- Enhance `README.md` with standard open-source badges and structure.
- Improve readability and conform to typical PHP/Composer package conventions.

## Design
We will modernize both documentation files to match standard open-source expectations now that the package is being published on Packagist. 

For the **Changelog**, we will adopt the [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) format. This means grouping changes under standard headers (`Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`) and formatting release headers as `## [Version] - YYYY-MM-DD`.

For the **README**, we will:
1. Add status badges at the top (Packagist version, PHP version, License).
2. Add a 'Requirements' section explicitly listing `php >= 7.2` and `guzzlehttp/guzzle`.
3. Standardize the headings.
4. Add a 'License' section at the bottom.

## Acceptance criteria
- [x] `CHANGELOG.md` correctly follows the "Keep a Changelog" structure.
- [x] `README.md` includes Packagist/PHP badges at the top.
- [x] `README.md` has clear Requirements, Installation, Usage, and License sections.

## Steps
- [x] Edit `CHANGELOG.md`:
   - Change the top heading and add a link to keepachangelog.com.
   - Reformat version headers (e.g., `- 14/06/2023 - v1.4` becomes `## [1.4.0] - 2023-06-14`).
   - Group bullet points under `### Added`, `### Fixed`, `### Changed`, etc.
- [x] Edit `README.md`:
   - Inject Markdown badges directly under the main `# Scopus API for PHP` title.
   - Add a `## Requirements` section above Installation.
   - Add a `## License` section at the bottom stating it's under the MIT License.

## Do not touch
- The actual PHP example code in the Usage section of the README (it is correct and functioning).
- Existing source files in `src/`.

## Evidence
- **Code:** `CHANGELOG.md` currently uses a custom format with hyphenated dates.
- **Code:** `README.md` lacks badges and a requirements section.
