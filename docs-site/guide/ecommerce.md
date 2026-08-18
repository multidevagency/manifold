# Ecommerce

The demo `Products` collection shows the modeling layer:

```php
Text::make('title')->required()->useAsTitle(),
Number::make('price')->decimal()->required(),
Upload::make('image'),
Select::make('status')->options(['draft', 'published'])->default('draft'),
Boolean::make('in_stock')->default(true),
Relationship::make('category')->to('categories'),
ArrayField::make('variants')->of([
    Text::make('name')->required(),
    Text::make('sku'),
    Number::make('price')->decimal(),   // overrides base price
    Boolean::make('in_stock'),
]),
...Seo::fields(),
```

The Next.js example renders `/shop` (grid) and `/shop/{slug}` (detail with a variants table, per-variant price/stock).

## What's deliberately not here yet

Carts, checkout, payments (Stripe/Mollie), orders, transactions, and guest order lookup are **roadmap** — they deserve a payment-provider integration done properly (webhook signature verification, idempotency, order state machine) rather than a scaffold. The collection layer above is the foundation they'd build on.
