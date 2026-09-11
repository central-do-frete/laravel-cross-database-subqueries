# Laravel 10 Expression adaptation

The production change is exactly two methods, ten added / three removed lines
(net seven), compared with `4ff5c7268fae5baeef30f0f3c1c271ae0aee9c0d`.
Regression tests were committed first in `59d0a7f`; the repair is `602b0c8`.
Neither PostgresGrammar nor SqlServerGrammar changed.

## Requirement-range correction (instruction 038)

The first test-harness commit also narrowed runtime requirements to PHP ^8.0
and Laravel 9/10. **That exceeded the authorization.** It was not part of the
two-seam adaptation and should not have been delivered. The published PHP
`>=7.1.3` requirement is restored verbatim; each published Illuminate range is
restored with only `|| ^10.0` added. This correction changes no production PHP
method. The release remains additive, so its version is 9.1.0.

All four PHP 8.1/8.2 × Laravel 9/10 combinations were rerun after the restoration:
132 test executions / 384 assertions pass, without failures, errors or skips.
A separate bounded API probe loaded official Grammar, Expression and Macroable
sources at Laravel 5.6.40, 6.20.45, 7.30.7 and 8.83.29 under offline PHP 7.4.33.
Across 48 observations, the new predicate was always false, the original value
was retained, and both marker inspection and relationship marker text matched.
The inspected grammars recognize Expression instances, whose base class defines
`__toString`; recognized subclasses inherit that method. Plain strings also
retain the old path. This verifies the added branch's inactivity at those
checkpoints, not full legacy framework/database compatibility. CI still covers
only Laravel 9 and 10; the older published range remains inherited and untested.

## Test-first evidence

The restored harness uses PHPUnit 9.6 directly instead of Testbench 3.6, which
pins Laravel 5.6. The original five test methods and all 40 SQL assertions remain;
fixture model classes now each have their own file. Seventeen expected SQL
strings were stale against the **unchanged** Laravel 9 baseline: database
qualification, PostgreSQL LIKE casts, and SQLite native subselect qualification.
SQLite fixture paths are now fixed dummy database names, since no PDO is opened.
This avoids machine-specific path text in a compile-only assertion. These
updates freeze observed SQL, not a claim that every dialect's SQL executes.

Sixteen MySQL expression cases freeze eight input shapes with two prefixes.
Twelve relationship cases cover plain, base-expression and custom-stringable
FROM values, same/different connections, and exists/count forms. The custom
expression returns different string/value text to catch an overbroad unwrap.
A count case deliberately retains the pre-existing incorrect prefixed-column SQL.

Before the repair, Laravel 9 passed **33 tests / 96 assertions**; Laravel 10 had
**18 errors / 60 assertions** in those same 33 tests. After it, both pass
**33 / 96**, with no skipped test or relaxed assertion.

## SQL acceptance, separate from the test suite

The same frozen application investigation probes were replayed before and after
on Laravel **9.52.22** and **10.50.3**, using PHP **8.1.34**, the unchanged query
inputs, and a newly owned MySQL **8.4.10** container with synthetic rows. Probe
sources were not edited: the runner overlaid this fork's source and the frozen
Laravel 9 vendor path read-only. PHP used the application's outbound guard and
joined only the offline MySQL container's network namespace. No application
endpoint or production dataset was used.

Each side records **80 database cases**: 48 cross-database cases (12 patterns ×
two prefixes × two versions) and 32 raw/indirect cases (eight patterns × fork/native
× two versions). **72 previously compiled cases retain identical SQL, bindings
and observed outcomes within their framework version.** The eight formerly
failing Laravel 10 fork cases now compile and exactly match the frozen Laravel
9 fork SQL, bindings and results. Before they failed there was no compiled SQL
to compare: this is a repaired failure with an older-version oracle, not a
fabricated same-version SQL match.

Before: 64 passing / 16 failing database cases. After: **72 passing / 8 failing**.
The remaining eight are the four known prefixed withCount/whereIn/joinSub/selectSub
failures on each version. Their SQL and errors are unchanged before/after.
Between framework versions, the errors still differ because Laravel 10 includes
the connection name; that distinction is retained in the evidence.

Separately, **32 compile-only Expression API cases per side** preserve all 18
previously compilable SQL results and repair 14 Laravel 10 base-expression cases
to the frozen Laravel 9 SQL. Both custom-string examples on Laravel 10 already
compiled and remain exact. Relationship raw SQL keeps the fork's database
qualification; native Eloquent has different SQL and is not its qualification
oracle.

This is a bounded MySQL compatibility result, not all-driver validation or an
application Laravel 10 rollout. The application remains on Laravel 9. Full raw
recordings, exact probe sources, hashes and the comparison verifier are retained
by the migration in `graphql/docs/codebase/laravel10-fork-adaptation/`.

The fork was then installed independently of graphql's vendor tree and passed
all four PHP 8.1/8.2 × Laravel 9/10 combinations, **132 test executions / 384
assertions** in total, using PHPUnit 9.6.36. Composer resolves dependencies on
each target PHP version, as CI does. Attempting to reuse a PHP 8.2-resolved vendor
on PHP 8.1 correctly failed Composer's platform check; that check was not bypassed.
The earlier red/green probe harness used frozen PHPUnit 9.6.34 instead.

GitHub Actions replaces the obsolete PHP 7.1/7.2 Travis/Codacy upload workflow.
The README documents the local command and the limited compile-only safety
boundary. The existing LICENSE remains MIT; Composer validation retains its
pre-existing missing-license metadata warning.
