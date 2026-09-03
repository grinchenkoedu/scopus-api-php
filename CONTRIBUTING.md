# Contributing

Thanks for helping. This is a small library with a narrow surface, so the bar is less about
process and more about not shipping surprises to the people who depend on it.

## AI-assisted contributions

**AI-assisted contributions are welcome.** Most of this repository's recent history was written
that way.

**The contributor who opens the pull request is fully responsible for it** — every line, whether
they typed it or a model produced it. That means:

- You understand what the change does and can explain why it is correct.
- You have read the diff yourself. Not skimmed it: read it.
- You can defend the design choices in review. *"The AI wrote it"* is not an answer to a review
  comment; it is a reason to close the pull request and start again.
- Generated tests are held to the same bar as generated code. A test that asserts whatever the
  code currently happens to do is worse than no test, because it makes the next change look safe.

Models are confident about things they have not checked. Verify claims against the code and
against a real run before repeating them in a pull request description — an unverified "all
green" costs a reviewer more than an honest "not tested".

## Before opening a pull request

These are mandatory, in this order:

**1. Write the tests.** Any change to `src/` needs unit tests. New behaviour gets a test that
would fail without the change; a bug fix gets a test that reproduces the bug. Cover the error
paths and the empty cases too — Scopus omits fields constantly, and most of this library's
history of defects is exactly that.

**2. Run them.**

```bash
composer install
vendor/bin/phpunit
```

**3. Review your own diff.** `git diff master...HEAD`, top to bottom. Most of what a reviewer
would tell you is visible here, and it is cheaper to find it yourself.

CI runs the suite on PHP 7.4 through 8.5, plus a `--prefer-lowest` job that pins Guzzle 7, so a
change that only works on your local version will be caught — but after you have asked someone to
review it.

## What the project expects

- **PHP `^7.4 || ^8.0`.** No syntax newer than 7.4. The CI matrix enforces this; check with
  `php -l` on 7.4 if you have it.
- **Guzzle 7 and 8 both work.** Do not use anything available in only one of them.
- **Do not edit `docs/` by hand.** It is generated, and the Docs workflow regenerates and commits
  it on every change to `src/`. Hand edits will be overwritten.
- **Do not commit `composer.lock`.** This is a library; the lock file is deliberately ignored.
- **Match the file you are editing.** The response classes use
  `isset($this->data['key']) ? $this->data['key'] : null` rather than `??`, and lazy-init caches
  use `$this->x ?? $this->x = …`. Consistency with the neighbours beats consistency with a style
  guide.
- **Breaking changes need a major version** and an entry in [UPGRADING.md](UPGRADING.md) with
  before/after code. If you are not sure whether something is breaking, ask in the pull request
  rather than guessing.
- **Every change gets a `CHANGELOG.md` entry** under `## [Unreleased]`, in the
  [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) section that fits it.

## Recommended tooling

Not required, but they encode the workflow above — plan, implement, review, verify, resolve
review comments — so the mandatory steps happen by default rather than by memory:

- [grinchenkoedu/claude-skills](https://github.com/grinchenkoedu/claude-skills) — for Claude Code
- [grinchenkoedu/antigravity-skills](https://github.com/grinchenkoedu/antigravity-skills) — for Antigravity

## Reporting a bug

A failing test is the best bug report. Failing that: the call you made, the response you got, the
PHP and Guzzle versions, and what you expected instead. **Never paste a real API key** — the
examples in this repository use `your-api-key-here`, and so should your report.
