# Laravel cross-database subqueries — Central do Frete fork

This fork keeps Eloquent `has`, `whereHas`, `doesntHave` and `whereDoesntHave`
queries qualified against the related model's database. Models extend
`Hoyvoy\CrossDatabase\Eloquent\Model` and declare their connection. The package's
service provider is discovered by Laravel. Ordinary subselects and `withCount`
use Laravel's native qualification; the obsolete `withCount` override was removed
in the Laravel 8 adaptation because it double-qualified the database.

This branch is validated for **MySQL on Laravel 9.52.22 and 10.50.3**. The test
matrix uses PHP 8.1 and 8.2. Dependency constraints admit Laravel 9 and 10; they
are not a claim that every patch release, PHP version or relationship shape has
been exercised. Use this company's repository/version in Composer: the original
upstream package alone does not select this fork. No Laravel 10 release tag is
created by this change.

## The compatibility seam

`QueriesRelationships::addHasWhere` encodes a related FROM as
`prefix<-->table<-->database`. `MySqlGrammar::compileFrom` splits that marker,
qualifies the database and preserves the existing prefix/alias behavior. The
marker is internal protocol, not a SQL parser: a raw expression containing
`<-->` is still interpreted as a marker, and literal occurrences have no escape
protocol. Do not change either side independently.

Laravel 9 expressions are stringable; Laravel 10 base expressions are not.
Changing how Laravel hands raw SQL to these methods broke both marker creation
and marker inspection, affecting raw FROM, grouped pagination and derived
queries. Retrieve the value through the query's grammar only for recognized
expressions **without** `__toString()`. Custom string conversion remains
intentional: it can differ from `getValue()`. Keep the original expression for
the unmarked grammar fallback, or raw SQL becomes a quoted table identifier.
The [tests](tests/Unit/MySqlExpressionTest.php) freeze all three distinctions.

PostgreSQL and SQL Server grammars share the old stringability assumption and
**were not adapted or validated against database servers**. Their retained
legacy string-SQL assertions are compile-only, not driver support. Anyone who
needs these drivers must validate their dialects and widen the implementation
and CI deliberately. SQLite also has only legacy compile assertions here.

Known prefix limitations remain: prefixed count/subselect patterns can produce
incorrect qualification or prefixed column references. Passing a compile test
for that existing SQL does not make the query executable. See the
[validation record](docs/laravel-10-validation.md).

## Run the library's tests

```sh
composer update --with laravel/framework:9.52.22 --no-scripts --no-plugins --no-security-blocking --no-audit
composer test
composer update --with laravel/framework:10.50.3 --no-scripts --no-plugins --no-security-blocking --no-audit
composer test
```

The security-blocking option permits these deliberately historical framework
checkpoints to be resolved; it is not a dependency security assessment. Tests
compile SQL only, use synthetic values and install PDO closures that throw on
any database access. No application `.env`, real database or credentials are
needed. `FORK_TEST_AUTOLOAD` optionally selects an existing test-only Composer
autoloader for a diagnostic run; normal package runs use `vendor/autoload.php`.

[GitHub Actions](.github/workflows/sql-contracts.yml) runs both frameworks on both
PHP versions. Its test process has a separate network namespace with no network
interface; dependency acquisition happens earlier with scripts and plugins
disabled. The library suite does not install a general PHP outbound guard for
arbitrary new HTTP/mail/process code. Preserve its compile-only boundary, or
add an appropriate guard and test-owned service fixture before expanding it.

The company maintainer assignment is tracked separately. This repository owns
the regression tests and CI; no individual owner is invented in CODEOWNERS.
