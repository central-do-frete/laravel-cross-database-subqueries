# Accepted connection-cloning debt, with an expiry condition

**Accepted debt, not a resolved defect or harmless internal difference.** Laravel
12 isolates each cloned database connection's grammar; Laravel 9–11 share that
grammar. Changing one connection's table prefix after cloning changes the other
connection's compiled SQL on 9–11, while 12 uses each connection's own prefix.
Ordinary MySQL queries can consequently return rows from different physical tables.
Marker aliases also change; the selected synthetic marker rows remain equal.

The framework causes this difference, independently of this fork's six-method
Laravel 12 repair. Twelve fork cross-framework state differences reproduce twelve
native controls; they are not 24 fork/native mismatches. The untouched fork cannot
construct on Laravel 12, so it provides no working pre-repair clone-output baseline.
No additional compatibility override was installed.

The consuming graphql application's audited source has **97 clone expressions**:
80 query builders, 12 DTOs, three collections, one date and one calculation row.
There is no connection clone, runtime prefix mutation or matching trigger in
those sites. Its 47 GraphQL clones are 46 query builders and one collection.
Eloquent query clones retain their connection and grammar; this was also measured
at runtime. PostgreSQL/SQL Server are not configured application connections.
This is why instruction 074 accepts the currently untriggered library behavior.
It does not establish that another consumer, future dependency or dynamic path
could never reach it.

**Expiry condition:** this acceptance must be re-evaluated **before introducing
any application or dependency path that clones database connections**, directly or
indirectly, in a way the audited application does not today. Cloning followed by
changing either connection's prefix makes the recorded divergence live. At that
boundary, characterize the affected SQL and actual database rows before shipping,
choose the intended behavior explicitly, and add regression coverage for that
consumer. Do not infer safety from unchanged query-clone tests, refresh the frozen
records to hide a new difference, or widen the fork implementation automatically.
Repeat the usage audit when a framework/dependency change adds a connection consumer.

`tests/Unit/ConnectionCloneContractTest.php` retains the existing versioned library
behavior across all three drivers and both initial prefixes. Fixtures come from
published captures, not expectations invented after a failing upgrade. These are
compile-only regressions, not a live application reachability guard. MySQL row
evidence and the 97-site audit are preserved in the application repository:
[073 attribution](https://github.com/central-do-frete/graphql/blob/0f2f3db1a0d51c98b01c41c4153dc7919a3ae0cd/docs/codebase/LARAVEL12_CLONE_ATTRIBUTION.md).
PostgreSQL and SQL Server remain compile-only; no server support is certified.
