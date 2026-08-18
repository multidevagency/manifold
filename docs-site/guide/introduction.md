# Introduction

Manifold is a **code-first headless CMS** for the Laravel + Nuxt stack, modeled on the idea Payload CMS proved out: the schema belongs in version control, reviews happen in pull requests, and the admin panel is a *projection* of the code rather than something you build.

One PHP class per collection gives you:

| You get | How |
|---|---|
| Real database tables | `php artisan manifold:migrate` generates and runs reviewable migrations |
| A full REST API | Pagination, search, filters, validation, Sanctum auth, per-operation access gates |
| An admin panel | Rendered entirely from `/api/manifold/schema` — zero admin code per collection |
| A typed JS client | `@manifold-cms/client` plus `manifold types` TypeScript codegen |
| Live preview | Any frontend, drafts included, beside the edit form |

## Philosophy

- **Real columns, not a JSON blob.** Every scalar field is a typed, indexable column a DBA can read. JSON is reserved for genuinely nested data (groups, arrays, blocks).
- **Generated migrations over runtime DDL.** Schema changes are files that go through review and deploy like any other migration.
- **The admin is disposable.** Anything the admin does goes through the same public API contract you'd use from your own code.

## Project layout

```
packages/manifold/          the engine (Manifold\Cms)
packages/client-js/         @manifold-cms/client
packages/cli/               @manifold-cms/cli
app/Collections/            your collections — the only code you write
admin/                      Nuxt 4 admin panel
examples/nextjs-blog/       Next.js frontend with shop + draft preview
```
