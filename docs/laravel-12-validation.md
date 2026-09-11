# Laravel 12: six API sites repaired, clone behavior retained as debt

Release 9.3.0 contains exactly the six authorized production changes, with
Laravel 9–11 preservation and the repaired Laravel 12 SQL checked against the
Laravel 11 oracle. Instruction 074 accepts the additional native clone behavior
as [debt with an expiry condition](connection-clone-debt.md), because the 97-site
application audit found no matching trigger. It is still observable in SQL and
rows when connection cloning is followed by a prefix change.

Tests preceded production: `53b6403` adds twelve independent factory/compiler
cases covering MySQL, PostgreSQL and SQL Server with empty and nonempty prefixes.
They use frozen 9/10/11 SQL; 12 must match the 11 oracle. The existing 37 test
cases and assertions were retained. Before the repair, each of 9/10/11 passed
49 tests / 138 assertions, while 12 produced 43 errors and six failures.

`2a20faa` changes exactly six methods: the three connection factories retain
`withTablePrefix(new Grammar())` when that API exists, otherwise construct the
grammar with its connection; the three `compileFrom` methods remove only the
second boolean argument to `wrapTable`. Nearby `wrap` calls remain unchanged.
No unrelated Expression, relationship, schema or locking method was changed.

The repaired own suite passes **49 tests / 138 assertions on each of Laravel
9.52.22, 10.50.3, 11.56.1 and 12.69.2** on PHP 8.3.33. Restoring each of the six
old methods separately makes exactly its two driver/prefix cases fail; all six
production mutants were detected. CI at `1d48785` passes all seven jobs: PHP
8.1/8.2 × Laravel 9/10, PHP 8.2 × Laravel 11, PHP 8.2/8.3 × Laravel 12.
[Recorded CI run](https://github.com/central-do-frete/laravel-cross-database-subqueries/actions/runs/34487588739).

The application repository's unchanged MySQL probes execute all 40 cases on
all four checkpoints, against synthetic data in a fresh offline MySQL 8.4.10
container. All 160 after records exactly match their assigned before records:
same framework for 9–11, Laravel 11 for 12. This includes SQL, bindings, rows,
errors and pagination queries. Laravel 9/10 retain their four known prefix
failures each; 11/12 pass all 40. A further 144 complete grammar records match.
PostgreSQL/SQL Server are **compile-only**, never server-executed; their eight
inherited raw-Expression errors on 10–12 remain. SQLite has only legacy compile
assertions. Older declared PHP/Illuminate support remains inherited and
unvalidated by this matrix. Requirements only add Laravel 12; nothing is narrowed.

The final clone experiment is an additional finding. Cloning a connection in
Laravel 11 shares its grammar. Changing one connection's prefix subsequently
changes the other connection's compiled SQL too. Laravel 12's native
`Connection::__clone()` creates a grammar bound to the clone, so those prefix
changes are independent. Across three drivers and two initial prefixes, twelve
fork and twelve native control state records differ from Laravel 11. All older
framework before/after records remain exact. Late prefix changes before cloning
and initial clone construction match on 12 too. Separately, all 96 query-builder
clone record pairs match; cloning a query does not clone its connection.

No seventh production change was made. Six versioned connection-lifecycle
cases now freeze the previously published captures on all three drivers and
both prefixes. The complete suite passes **55 tests / 180 assertions on each
of Laravel 9–12**, with no skips or weakened assertions. A separate test-only
mutation suppresses the native clone hook in each driver in turn; each must
fail its two affected cases; all three mutations were detected (six deliberate
failures across eighteen test executions). The production diff remains exactly six methods.

The accepted behavior is not harmless internal state: native Laravel causes it,
all 24 fork/control records change SQL, and eight ordinary MySQL row comparisons
change (four fork, four native). All fourteen fork marker row comparisons remain
equal. See the debt document for the precise expiry condition before introducing
a new connection-cloning consumer. Immutable tag 9.3.0 and the application's
minimum `^9.3` plus actual tagged Composer lock are one coordinated delivery.

The application repository owns the full raw evidence and exact probe scripts
under `docs/codebase/laravel12-fork-repair/`, with the verdict at
`docs/codebase/LARAVEL12_FORK_REPAIR.md`. Its historical snapshot verifier explicitly reports
`release_ready: false`; that preserves the pre-074 decision state, rather than
retroactively rewriting the evidence. The accepted debt document owns the current
disposition. Tests/captures used synthetic inputs, PDO-denying compile
closures or owned disposable MySQL, an offline namespace and a native transport
guard. No real service, production data or application GraphQL root was used.
