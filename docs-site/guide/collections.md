# Collections

A collection is one PHP class extending `Manifold\Cms\Collections\Collection`, registered in `config/manifold.php`.

```php
class Posts extends Collection
{
    protected string $slug = 'posts';
    protected string $defaultSort = '-published_at';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            RichText::make('body'),
            Select::make('status')->options(['draft', 'published'])->default('draft')->required(),
        ];
    }
}
```

```php
// config/manifold.php
'collections' => [
    App\Collections\Posts::class,
],
```

Run `php artisan manifold:migrate` and the table, API, and admin exist.

## Conventions

| Member | Default | Purpose |
|---|---|---|
| `$slug` | kebab-case class name | URL segment + admin identity |
| `table()` | `mf_{slug}` | Physical table name |
| `$defaultSort` | `-created_at` | List ordering; `-` prefix = descending |
| `useAsTitle()` | — | Marks the field shown as the entry's title everywhere |

## Scaffolding

```bash
php artisan make:collection Products
```

writes the class skeleton; register it and migrate. Collections can also be created [from the admin UI](/guide/schema-editing) in local development.
