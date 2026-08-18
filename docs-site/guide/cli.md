# CLI

```bash
npx @manifold-cms/cli <command>
```

`--url` defaults to `$MANIFOLD_URL` or `http://localhost:8000`; `--token` to `$MANIFOLD_TOKEN`.

## `manifold types`

Reads `/api/manifold/schema` and writes TypeScript interfaces:

```bash
manifold types --token <token> -o manifold-types.d.ts
```

Select/radio options become union types, blocks become discriminated unions:

```ts
export interface Posts {
  id: number
  title: string
  status: "draft" | "review" | "published"
  category_id: number | null
}

export interface Pages {
  layout: Array<
    | ({ blockType: "content" } & { body: string | null })
    | ({ blockType: "cta" } & { label: string; url: string })
  > | null
}
```

## `manifold export` / `import`

```bash
manifold export posts -o posts.json          # guest-filtered without --token
manifold import posts posts.json --token <t>
```

## `manifold init`

```bash
manifold init my-site
```

Clones the project and prints the setup steps.

## Laravel-side generator

```bash
php artisan make:collection Products
```
