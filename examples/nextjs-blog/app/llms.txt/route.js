import { manifold } from '../../lib/manifold'

const base = process.env.SITE_URL ?? 'http://localhost:3002'

// llms.txt: a machine-readable site guide for AI crawlers and answer engines.
export async function GET() {
  const { data: posts } = await manifold.collection('posts').list({ perPage: 100, sort: '-published_at' })

  const body = [
    '# Manifold Example Blog',
    '',
    '> Demo blog served by Manifold CMS. All content below is publicly readable.',
    '',
    '## Posts',
    ...posts.map((post) => `- [${post.title}](${base}/posts/${post.slug})${post.excerpt ? `: ${post.excerpt}` : ''}`),
  ].join('\n')

  return new Response(body, { headers: { 'Content-Type': 'text/plain; charset=utf-8' } })
}
