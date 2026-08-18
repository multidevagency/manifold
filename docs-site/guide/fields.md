# Fields

All fields share a fluent core: `required()`, `unique()`, `index()`, `default()`, `label()`, `help()`, `rules()` (extra Laravel validation), and `renamedFrom()` (see [Schema migrations](/guide/schema-migrations)).

## Data fields

| Field | Column | Notes |
|---|---|---|
| `Text::make('title')` | varchar | `maxLength(n)` |
| `Textarea::make('excerpt')` | text | |
| `RichText::make('body')` | longtext | HTML; toolbar editor in the admin |
| `Code::make('snippet')` | longtext | `language('php')`; mono editor |
| `Email::make('contact')` | varchar | validated as email |
| `Slug::make('slug')` | varchar, unique | `from('title')` auto-generates, collision-suffixed |
| `Number::make('price')` | bigint / decimal | `decimal()` for money-ish values |
| `Boolean::make('featured')` | tinyint | Payload's Checkbox; defaults to `false` |
| `Select::make('status')` | varchar | `options([...])`; enum-validated |
| `Radio::make('plan')` | varchar | Select stored the same, rendered as radios |
| `DateTime::make('published_at')` | datetime | ISO-8601 over the API |
| `Date::make('birthday')` | date | date-only |
| `Json::make('settings')` | json | free-form, validated as JSON |
| `Point::make('location')` | json | `{lat, lng}`, range-validated |
| `Upload::make('image')` | varchar | stores a path; serialized as `{path, url}` |
| `Relationship::make('category')` | bigint `{name}_id`, indexed | `to('categories')` |
| `Join::make('posts')` | — (virtual) | `to('posts')->via('category')`; read-only reverse list |

## Container fields

Nested data stored as JSON on the parent row:

```php
Group::make('hero')->fields([
    Text::make('heading'),
    Upload::make('image'),
]),

ArrayField::make('faq')->of([
    Text::make('question')->required(),
    Textarea::make('answer'),
]),

Blocks::make('layout')->blocks([
    'content' => [RichText::make('body')],
    'cta' => [Text::make('label')->required(), Text::make('url')->required()],
]),
```

Blocks entries carry a `blockType` discriminator, validated server-side, and `manifold types` emits a discriminated TypeScript union for them.

## Layout fields

Pure admin organization — no database columns; their children get columns as usual:

```php
Tabs::of([
    'Content' => [...],
    'SEO' => Seo::fields(),
]),
Row::with([Text::make('first'), Text::make('last')]),
Collapsible::with([...], label: 'Advanced'),
Ui::note('Rendered as a hint in the form.'),
```

## Presets

`Seo::fields()` returns `meta_title` + `meta_description`, ready to spread into any collection.
