# Upgrading

## 1.x → 2.0

Three breaking changes, all in the response objects and the bulk retrieval methods. Nothing in
`ScopusApiFactory`, `SearchQuery` or the single-document methods changed.

| What | Before | After |
|---|---|---|
| Collection getters | `null` when the field was absent | `[]`, and they declare `: array` |
| `CitationCount::getStatus()` | `bool` — `true` for *any* non-empty status | the status string, or `null` |
| `retrieveAbstracts()` / `retrieveAuthors()` | swallowed every failure, returned `[]` | exceptions propagate |

**PHP 7.2 and 7.3 are no longer supported.** That happened in 1.5.0, not here — the requirement is
`^7.4 || ^8.0`. Composer will not install 2.0 on an older runtime; it resolves to 1.4.2 instead,
which carries the same bug fixes.

---

### 1. Collection getters return `[]`, never `null`

An empty Scopus result is routine, not exceptional, so iterating no longer needs a guard.

```php
// Before
if ($results->getEntries() !== null) {
    foreach ($results->getEntries() as $entry) { /* … */ }
}

// After
foreach ($results->getEntries() as $entry) { /* … */ }
```

Only an explicit comparison against `null` breaks. A falsy check works in both versions:

```php
if (!$results->getEntries()) { /* no results */ }   // fine before and after
if ($results->getEntries() === null) { /* … */ }    // never true in 2.0
```

The thirteen affected methods:

```
AbstractCitations::getIdentifiers()      Author::getAffiliationHistory()
AbstractCitations::getCiteInfos()        Author::getSubjectAreas()
Abstracts::getAuthors()                  AuthorProfile::getNameVariants()
Affiliation::getNameVariant()            AuthorProfile::getJournalHistory()
CiteInfo::getAuthors()                   Entry::getAffiliations()
Entry::getAuthors()                      Entry::getCoAuthor()
SearchResults::getEntries()              IAbstract::getAuthors()  (interface)
```

**If you implement `IAbstract` yourself**, its `getAuthors()` now declares `: array` and your
implementation must match, or PHP will raise a fatal signature error at load time.

### 2. `CitationCount::getStatus()` returns a string

It used to declare `: bool`, which coerced Scopus's status string — so `"found"` and `"NOT_FOUND"`
both came back as `true`. The method could not answer the question it existed for.

```php
// Before — always true for any document Scopus reported on
if ($count->getStatus() === true) { /* … */ }

// After
if ($count->isFound()) { /* … */ }          // the boolean question, answered correctly
$count->getStatus();                        // "found", "NOT_FOUND", … or null
```

A truthiness check needs no change — a non-empty string is truthy, exactly as `true` was:

```php
if ($count->getStatus()) { /* … */ }   // still compiles; still almost always true
```

That is the trap: it compiles, and it is as wrong as it always was. Move to `isFound()`.

### 3. Bulk retrieval no longer hides failures

`retrieveAbstracts()` and `retrieveAuthors()` wrapped their work in
`catch (Exception $e) { return []; }`. A network failure, an expired API key and a rate limit were
all indistinguishable from "no such document".

```php
// Before — [] meant anything at all
$abstracts = $api->retrieveAbstracts($ids);

// After
try {
    $abstracts = $api->retrieveAbstracts($ids);
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    // transport, auth, rate limiting
} catch (\Scopus\Exception\JsonException | \Scopus\Exception\XmlException $e) {
    // Scopus answered with something unparseable
} catch (\Exception $e) {
    // includes: Scopus returned fewer documents than were requested
}
```

An empty id list still returns `[]`, and makes no request.

**New in 2.0:** when Scopus returns fewer documents than were asked for, both methods throw rather
than mis-key the results:

```
Scopus returned 1 of the 2 requested documents (111,222), so results cannot be keyed by id.
```

Previously this produced a `ValueError` from inside `array_combine()`.

---

## Finding affected code

Mechanical checks, in rough order of how likely each is to matter:

```bash
# 1. Comparisons against null on a collection getter - these silently stop matching.
grep -rnE '(getEntries|getAuthors|getAffiliations|getCoAuthor|getSubjectAreas|getNameVariants?|getJournalHistory|getAffiliationHistory|getIdentifiers|getCiteInfos)\(\)\s*(===|!==)\s*null'

# 2. getStatus() used as a boolean - compiles, still wrong.
grep -rn 'getStatus()'

# 3. Bulk calls with no surrounding try/catch.
grep -rn 'retrieveAbstracts\|retrieveAuthors'

# 4. Your own implementations of the interface, which now need ': array'.
grep -rn 'implements .*IAbstract'
```

`is_null(...)` and `is_array(...)` guards around the getters in (1) are now dead code rather than
broken — safe to leave, better to delete.

## Not changed

`ScopusApiFactory`, `SearchQuery`, `ScopusApi::search()`, `query()`, `retrieve()`,
`retrieveAbstract()`, `retrieveAuthor()`, `retrieveAffiliation()`, `retrieveCitationCount()`,
`searchAuthors()`, `overviewCitation()` and `retrieveDocumentsAuthor()` all keep their signatures
and their behaviour. Every scalar getter still returns its value or `null`.
