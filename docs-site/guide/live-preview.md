# Live preview

Give a collection a preview target:

```php
public function previewUrl(): ?string
{
    return 'http://localhost:3001/posts/{slug}';
}
```

The admin's edit view gains a **Live preview** pane that renders the URL — placeholders filled from the entry's values — beside the form, refreshed on every save. A **View as JSON** pane shows exactly what the API serves, with copy-to-clipboard.

## Draft preview

The preview iframe appends `?preview=1`. In the Next.js example, that flag switches the data client to one holding a **server-side token** (`MANIFOLD_SERVER_TOKEN`), which bypasses [guest filters](/guide/access-control) — so editors preview drafts while the public still gets 404s. The token never reaches the browser.
