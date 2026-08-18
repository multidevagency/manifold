# Roadmap

An honest list of what a production release still needs.

## Fields & data
- [ ] Media library (browse/reuse uploads; the `Upload` field currently stores per-entry files)
- [ ] Child-table storage option for `ArrayField`/`Blocks` (today: JSON columns)
- [ ] Deep validation of nested container fields
- [ ] Localization
- [ ] Draft & publish versioning with scheduled publishing

## Ecommerce
- [ ] Carts & guest checkout
- [ ] Stripe / Mollie adapters with verified webhooks and idempotent order creation
- [ ] Orders, transactions, and token-gated guest order lookup
- [ ] Multi-currency pricing

## Platform
- [ ] Roles beyond "authenticated user" in the demo
- [ ] On-demand revalidation hooks (notify frontends on publish)
- [ ] Extract `packages/manifold` to a standalone Composer package
- [ ] Publish `@manifold-cms/client` and `@manifold-cms/cli` to npm
