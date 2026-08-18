# Access control

Each collection declares per-operation gates as closures:

```php
public function access(): array
{
    return [
        'read'   => fn ($user) => true,           // public
        'create' => fn ($user) => $user !== null,
        'update' => fn ($user) => $user?->isEditor(),
        'delete' => fn ($user) => $user?->isAdmin(),
    ];
}
```

A missing key denies everyone except authenticated users. Guests get `401`, authenticated-but-denied users get `403`.

## Guest filters

Public read access usually shouldn't expose everything. `guestFilters()` forces filters onto every unauthenticated read:

```php
public function guestFilters(): array
{
    return ['status' => 'published'];
}
```

Guests listing the collection only see matching rows; fetching a non-matching entry by id returns `404`. Authenticated requests bypass the filters — which is how [draft preview](/guide/live-preview) works.
