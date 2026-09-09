# Laravel 11: unchanged source before an additive declaration

The same eight observation probe/model files and all 13 installed 9.1.0 fork
source files were replayed before widening any manifest. On PHP 8.2.33 with a
new synthetic, offline MySQL 8.4.10 server, Laravel 10.50.3 returns 36 expected
results and four known prefix failures; Laravel 11.56.1 returns all 40 expected
results. No fork source or probe expectation changed.

36 database pairs match exactly (SQL, bindings, results, errors and pagination
queries). Four prefixed `withCount`, `whereIn-subquery`, `joinSub` and `selectSub`
cases now qualify `probe_b`.`p_orders` instead of the invalid database
`p_p_probe_b`. Laravel 11 native table wrapping repairs that recorded defect.
It is a changed outcome, not preservation of the old failure.

All 16 Expression API cases retain exact actual fork/native SQL and bindings,
Expression wrapping and string conversion. Nine alternative scalar-wrapping
diagnostics differ and remain in the evidence; only seven whole API records
are identical. The own unchanged suite passes 33 tests / 96 assertions on each
runtime, with zero failures, errors or skips. These are finite observations,
not blanket driver compatibility: PostgreSQL/SQL Server/SQLite have only the
inherited compile assertions, and older Laravel support remains inherited and
untested by this matrix.

The application repository preserves the exact probe, observations, comparison,
strict-schema offline dependency catalogue, source hashes and verifier:
[Laravel 11 fork probe](https://github.com/central-do-frete/graphql/blob/11c5178f127fa2ccdc1e364c93d4be8b9690c8a6/docs/codebase/LARAVEL11_FORK_PROBE.md).
Dependency acquisition is separate from runtime execution. No real service,
production data, shared stack or application API operation was used.

9.2.0 therefore adds Laravel 11 to the published range without a source change.
PHP >=7.1.3 and all existing Illuminate 5.6–10 constraints remain unchanged.
The next framework major requires another probe, not inferred compatibility.

Instruction 042 deliberately accepts the four repaired prefix outcomes while
retaining the original failure records. Four new versioned regression cases
pass on Laravel 9, 10 and 11: 37 tests and 108 assertions on each, zero failures,
errors or skips. The original 33-test suite had 96 assertions, not 96 tests.
The application audit found no dependency on the removed errors in its source;
all configured MySQL prefixes are empty and no runtime override was found.
See the [explicit expectation and bounded audit](https://github.com/central-do-frete/graphql/blob/dbe958ba5/docs/codebase/LARAVEL11_FORK_PROBE.md).
