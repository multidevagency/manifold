import { manifold } from '../lib/manifold'

// Rendered per request: content comes from the CMS, and the API is not
// reachable during image builds.
export const dynamic = 'force-dynamic'

const base = process.env.SITE_URL ?? 'http://localhost:3002'

export default async function sitemap() {
  const { data: posts } = await manifold.collection('posts').list({ perPage: 100, sort: '-published_at' }).catch(() => ({ data: [] }))

  return [
    { url: base, lastModified: new Date() },
    ...posts.map((post) => ({
      url: `${base}/posts/${post.slug}`,
      lastModified: post.updated_at,
    })),
  ]
}
