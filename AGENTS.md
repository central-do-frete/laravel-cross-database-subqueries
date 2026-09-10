# Project agent memory

- Read `README.md` before changing the cross-database protocol. The marker has
  two seams; custom `__toString()` and the original raw-expression fallback are
  compatibility contracts, not simplification opportunities.
- `composer test` runs SQL compilation tests. `tests/TestCase.php` denies PDO
  access; `.github/workflows/sql-contracts.yml` owns the Laravel/PHP matrix and
  network isolation. The README distinguishes MySQL validation from legacy
  compile assertions for other drivers.
- `docs/laravel-10-validation.md` records the frozen SQL comparison and existing
  prefix limitations. `docs/laravel-12-validation.md` records the six-site candidate
  and the unresolved native connection-clone behavior. Green compile CI alone
  does not resolve a measured behavior difference or authorize a release.

## Maintaining this file

Keep this file for knowledge useful to almost every future agent session in this project.
Do not repeat what the codebase already shows; point to the authoritative file or command instead.
Prefer rewriting or pruning existing entries over appending new ones.
When updating this file, preserve this bar for all agents and keep entries concise.
