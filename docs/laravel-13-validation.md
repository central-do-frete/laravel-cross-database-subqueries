# Laravel 13 admission: constraint-only release 9.4.0

Release 9.4.0 appends `|| ^13.0` to the four runtime Illuminate requirements,
preserving every previously declared major from 5.6 through 12 and PHP
`>=7.1.3`. All 13 production source files remain byte-identical to release
9.3.0 (`08554168ba4a15a14e1a30fd26bee3c4dc9e928a`). No implementation,
test expectation or CI matrix is changed. Older declared ranges are inherited;
this release does not certify them anew. Development dependencies and the
existing package suite still target Laravel 9–12.

## Measured component comparison

On PHP 8.3.33, unchanged fork 9.3.0 was compared between Laravel 12.69.2 and
13.31.0 (official framework commit `7c75fbf93f91fa077d3df1c820cc14f4e59a9774`).
Separate native controls load zero Hoyvoy classes. Each of the four arms records
172 cases: **688 record executions, 344 before/after comparisons**.

| Per framework | Records | Successful SQL strings | Recorded errors |
| --- | ---: | ---: | ---: |
| Fork | 172 | 204 | 16 inherited observations |
| Native | 172 | 220 | 0 |

Across versions, fork **168/172 records and 200/204 SQL strings** match exactly;
native **168/172 records and 216/220 SQL strings** match exactly. Bindings,
recorded types, prefixes, error class/messages and clone state match. The same
four MySQL grouped/HAVING count strings change in both arms: Laravel 13 quotes
the unchanged alias name, changing `count(*) as aggregate` to
``count(*) as `aggregate` ``. These strings remain distinct in the evidence;
strict SQL identity is false. No fork override hides the framework's change.

The matrix covers real factories and grammars, raw/marker FROM, Eloquent
relationships, subqueries, pagination count compilation, query cloning and
connection-prefix/clone state. **All 688 records are compile-only, with zero
PDO calls.** The 16 errors per fork arm are eight PostgreSQL/SQL Server
driver/prefix/raw-expression cases through two routes. Their TypeErrors are
inherited Laravel 12 behavior, not a Laravel 13 regression or passing capability.
PostgreSQL and SQL Server remain inherited and unvalidated against servers.
SQLite and older runtime combinations were not revalidated in this step.

The evidence comparator passes nine controls, including eight deliberate
rejections. These test evidence rejection, not mutations of production code.

## Focused executed MySQL evidence

Instruction 096 required real result equivalence before accepting the four
native SQL differences. Both grouped and HAVING pagination were executed with
empty and `p_` prefixes on **MySQL 8.4.10**, using three synthetic rows:
active, inactive, active. There are **four pairs, eight case executions**.
Each calls the real paginator and re-executes its captured count SQL to inspect
the raw result key. On both Laravel versions:

| Case (both prefixes) | Raw result | Paginator total | Page rows |
| --- | --- | ---: | --- |
| Grouped by status | `aggregate`: integer 2 | integer 2 | active, inactive |
| HAVING count > 1 | `aggregate`: integer 1 | integer 1 | active |

All four pairs match in result key, value/type, total/type, page rows, bindings
and page SQL. The only four differences are the retained count SQL strings.
Five deliberate changes to captured keys, totals/types, rows or SQL are rejected
by the focused comparator. Firstmate's conditional acceptance applies on this
executed evidence: the quoting changes are cosmetic for these measured cases.
This is not a claim of database equivalence for every query or driver.

The test-owned MySQL server uses tmpfs, no published ports and an offline
network namespace. Its UUID is checked by the PHP clients. Both outbound guard
layers stay enabled without a new allowance; only established loopback fixture
access is used. Only the owned server is removed. No real provider, production
data or application endpoint is contacted.

## Limits and unchanged debt

The probe uses the application's Laravel 12 dependency tree with an isolated
Laravel 13 source overlay and its genuine new polyfill-php86 dependency. It is
not a complete Laravel 13 Composer installation or application acceptance gate.
No new registered GraphQL operation was exercised. The application pin and
release ship together; installing Laravel 13 remains a subsequent step.

[Connection-clone debt and its expiry](connection-clone-debt.md) remain exactly
as documented: re-evaluate before a new consumer clones database connections.
No clone override, seventh repair or change to that debt contract is included.
Full captures, source hashes, commands and controls are retained in the migration
mission's `data/rede-de-testes/laravel13-fork-094/` and
`data/rede-de-testes/laravel13-release-096/` evidence bundles.
