# JavaScript client

`@manifold-cms/client` is a zero-dependency ESM client that runs in Node, Next.js, Nuxt, or the browser.

```js
import { createClient } from '@manifold-cms/client'

const manifold = createClient({ url: 'http://localhost:8000' })

// Public, guest-filtered reads
const { data, meta } = await manifold.collection('posts').list({
  sort: '-published_at',
  filter: { status: 'published' },
  page: 1,
})

// Authenticated
const authed = createClient({ url, token: process.env.MANIFOLD_SERVER_TOKEN })
await authed.collection('posts').create({ title: 'Hello' })
await authed.collection('posts').update(1, { status: 'published' })
await authed.collection('posts').delete(1)
```

Pair it with [`manifold types`](/guide/cli) for full type safety:

```ts
import type { Posts } from './manifold-types'

const posts = manifold.collection<Posts>('posts')
```

Errors throw with `.status` and `.errors` (the Laravel validation map).
