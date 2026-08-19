# Next.js & other frameworks

Manifold is headless: any framework consumes the same REST API, directly or through [`@manifold-cms/client`](/guide/js-client).

The repo ships a complete Next.js App Router example in `examples/nextjs-blog`:

- **Blog** — post list + detail from the `posts` collection
- **Shop** — product grid + product pages with variants from `products`
- **Layout-builder pages** — `/{slug}` renders `pages` entries: hero group, blocks (`content`, `cta`, `media`), FAQ array
- **Draft preview** — `?preview=1` + server token renders unpublished entries for the admin's preview pane

```bash
cd examples/nextjs-blog
cp .env.example .env.local
pnpm install && pnpm dev   # http://localhost:3002
```

The same pattern works in Nuxt, SvelteKit, Astro, or plain fetch — the example's `lib/manifold.js` is ~10 lines.
