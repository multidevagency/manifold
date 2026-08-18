# Schema migrations

`php artisan manifold:migrate` diffs every registered collection against the live database and, for each difference, **writes an ordinary Laravel migration file** to `database/migrations`, then runs `php artisan migrate`.

```
posts: add column reading_time
  -> database/migrations/2026_08_18_190251_update_mf_posts_table.php
```

Use `--dry-run` to see the diff without writing anything.

## Renames are declared

A diff cannot distinguish a rename from a drop-plus-add, so renames are explicit:

```php
RichText::make('content')->renamedFrom('body'),
```

generates `renameColumn('body', 'content')` instead of destroying data. Without the declaration, the differ reports a drop and an add — which is exactly what a reviewer should get to see in the migration file.

## Column additions are nullable

Adding a NOT NULL column without a default fails on populated tables, so generated *additions* stay nullable and requiredness is enforced at the validation layer. Freshly created tables keep full NOT NULL semantics.

## Why files, not runtime DDL

The generated migration is reviewable in a pull request, replayable in CI, and ordered with the rest of your migration history — schema changes never happen silently.
