# SEO & GEO

## SEO

- `Seo::fields()` adds `meta_title` + `meta_description` to any collection.
- The Next.js example wires them into `generateMetadata` (title, description, Open Graph) with sensible fallbacks to the entry's title/excerpt.
- `app/sitemap.js` and `app/robots.js` generate `sitemap.xml` and `robots.txt` from live content.
- Article pages embed **JSON-LD** (`schema.org/Article`) built from entry data.

## GEO (Generative Engine Optimization)

AI answer engines read differently than crawlers:

- **`/llms.txt`** — a machine-readable site guide listing every published entry with its excerpt, generated live from the API.
- Meta descriptions and excerpts double as citable passages.
- Clean semantic HTML from the block renderer keeps content extractable.

The `Point` field covers literal geo data (`{lat, lng}`, range-validated) for location-based content.
