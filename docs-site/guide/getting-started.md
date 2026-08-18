# Quick start

Requirements: PHP 8.3+, Composer, Node 20+, pnpm.

```bash
git clone https://github.com/multidevagency/manifold.git my-site
cd my-site

composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # demo collections + admin user
php artisan storage:link            # serves uploaded files

cd admin && pnpm install && cd ..

composer dev                        # API + queue + logs + vite + Nuxt admin
```

Open **http://localhost:3000** and sign in with `admin@manifold.test` / `password`.

Or scaffold with the CLI:

```bash
npx @manifold-cms/cli init my-site
```

## The 60-second tour

Add a field to `app/Collections/Posts.php`:

```php
Number::make('reading_time')->label('Reading time (min)'),
```

```bash
php artisan manifold:migrate
```

Reload the admin: the field is in the form, the column is in the table, the API validates it, and `manifold types` will emit it. That's the whole product.

## Optional extras

```bash
# Frontend example with shop, layout-builder pages, and draft preview
cd examples/nextjs-blog
cp .env.example .env.local && pnpm install && pnpm dev   # :3001

# AI content generation in the admin
echo 'ANTHROPIC_API_KEY=sk-ant-your-key' >> .env
```
