import { manifold } from '../lib/manifold'

const base = process.env.SITE_URL ?? 'http://localhost:3002'

export default async function sitemap() {
  const { data: posts } = await manifold.collection('posts').list({ perPage: 100, sort: '-published_at' })

  return [
    { url: base, lastModified: new Date() },
    ...posts.map((post) => ({
      url: `${base}/posts/${post.slug}`,
      lastModified: post.updated_at,
    })),
  ]
}
