<p align="center">
  <img src="docs/screenshots/login.jpg" alt="Manifold login" width="720">
</p>

<h1 align="center">Manifold</h1>

<p align="center">
  <strong>A code-first headless CMS for Laravel + Nuxt.</strong><br>
  Define a collection as a PHP class — Manifold generates the database migrations,
  the REST API, and the admin UI. Nothing else to write.
</p>

---

## The idea

Payload CMS proved that "config as code" is the right model for a CMS: the schema
lives in version control, reviews happen in pull requests, and the admin panel is
a *projection* of the code rather than a thing you build. Manifold brings that
model to the Laravel + Nuxt stack.

One class:

```php
class Posts extends Collection
{
    protected string $slug = 'posts';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            Textarea::make('excerpt'),
            RichText::make('body'),
            Select::make('status')->options(['draft', 'review', 'published'])->default('draft')->required(),
            Relationship::make('category')->to('categories'),
            Boolean::make('featured'),
            DateTime::make('published_at')->index(),
        ];
    }

    public function access(): array
    {
        return [
            'read'   => fn ($user) => $user !== null,
            'delete' => fn ($user) => $user?->isEditor(),
        ];
    }
}
```

…gives you all of this:

| You get | How |
|---|---|
| **Real database tables** | `php artisan manifold:migrate` diffs your classes against the live schema and writes ordinary, reviewable Laravel migrations — then runs them |
| **A full REST API** | `GET/POST/PATCH/DELETE /api/manifold/posts` with pagination, search, filtering, sorting, validation, and Sanctum auth |
| **An admin panel** | The Nuxt app reads `/api/manifold/schema` and renders list views and edit forms for every collection — zero admin code per collection |
| **Access control** | Closures per operation, enforced server-side |

<p align="center">
  <img src="docs/screenshots/list-view.jpg" alt="Generated list view" width="720">
</p>

<p align="center">
  <img src="docs/screenshots/edit-view.jpg" alt="Generated edit form" width="720">
</p>

## Quick start

Requirements: PHP 8.3+, Composer, Node 20+, pnpm.

```bash
git clone <this repo> manifold && cd manifold

composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # demo collections + demo admin user

cd admin && pnpm install && cd ..

composer dev                        # boots API, queue, logs, vite AND the Nuxt admin
```

Open **http://localhost:3000** and sign in with `admin@manifold.test` / `password`.

## The 60-second demo

Add a field to `app/Collections/Posts.php`:

```php
Number::make('reading_time')->label('Reading time (min)'),
```

Then:

```bash
php artisan manifold:migrate
```

```
  posts: add column reading_time
    -> database/migrations/2026_08_18_190251_update_mf_posts_table.php
```

Reload the admin. The field is in the form, the column is in the table, and the
API accepts it — with validation. That's the whole product.

### Schema changes are explicit, not magical

The differ generates a migration *file* you can read in review — it never mutates
the schema silently. Because a diff cannot distinguish a rename from a
drop-plus-add, renames are declared:

```php
RichText::make('content')->renamedFrom('body'),
```

which generates `renameColumn('body', 'content')` instead of destroying data.

## Field types

`Text` · `Textarea` · `RichText` · `Slug` (auto-generated, uniqueness-suffixed) ·
`Email` · `Number` · `Boolean` · `Select` · `DateTime` · `Relationship`

All fields support `required()`, `unique()`, `index()`, `default()`, `label()`,
`help()`, `rules()` (extra Laravel validation rules), and `renamedFrom()`.

## API

```
POST   /api/manifold/auth/login          -> { token, user }
GET    /api/manifold/schema              -> collections + field schemas
GET    /api/manifold/{collection}        ?page&perPage&sort&search&filter[field]=v
POST   /api/manifold/{collection}
GET    /api/manifold/{collection}/{id}
PATCH  /api/manifold/{collection}/{id}   (partial updates)
DELETE /api/manifold/{collection}/{id}
```

Auth is Laravel Sanctum bearer tokens. Every operation passes through the
collection's `access()` gates.

## Architecture

```
packages/manifold/          the engine (Manifold\Cms)
├── src/Fields/             field type system (fluent builders)
├── src/Collections/        Collection base class
├── src/Support/            SchemaDiffer, MigrationGenerator, EntryRepository
├── src/Http/               REST controllers
└── src/Console/            manifold:migrate

app/Collections/            your collections (the only code you write)
admin/                      Nuxt 4 admin (schema-driven, Reka UI primitives, Tailwind v4)
```

Design decisions worth knowing:

- **Real columns, not a JSON blob.** Each collection gets its own table with
  typed, indexable columns. The cost is needing a schema differ; the win is
  real FKs-ready data, real indexes, and a database a DBA can read.
- **Generated migrations over runtime DDL.** Schema changes are files in
  `database/migrations`, so they go through review and deploy like any other
  migration.
- **The admin is disposable.** It renders entirely from `/api/manifold/schema`.
  Any client could do the same — the schema endpoint is the contract.

## Tests

```bash
php artisan test
```

18 feature tests cover auth, CRUD, validation, defaults, slug generation and
collision suffixing, relationship key normalization, filtering, search,
pagination, access control, and the schema differ (create / add / drop / rename
detection).

## Roadmap

Honest list of what a production release still needs:

- [ ] `Upload` field + media library
- [ ] Array / block fields (child tables)
- [ ] Draft & publish versioning
- [ ] Localization
- [ ] Column `->change()` coverage for more type transitions
- [ ] Roles beyond "authenticated user" in the demo collections
- [ ] Extract `packages/manifold` to a standalone Composer package

## License

MIT
