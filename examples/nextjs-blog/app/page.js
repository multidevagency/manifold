import Link from 'next/link'
import { manifold } from '../lib/manifold'
import ScrollHero from './components/ScrollHero'

export const dynamic = 'force-dynamic'

export default async function Home() {
  const { data: posts } = await manifold.collection('posts').list({ sort: '-published_at' })

  return (
    <>
      <ScrollHero />
      <h1>Blog</h1>
      <ul style={{ listStyle: 'none', padding: 0 }}>
        {posts.map((post) => (
          <li key={post.id} style={{ borderBottom: '1px solid var(--line)', padding: '1.25rem 0' }}>
            <Link href={`/posts/${post.slug}`} style={{ fontSize: '1.3rem', fontWeight: 700, fontFamily: 'system-ui', textDecoration: 'none' }}>
              {post.title}
            </Link>
            {post.excerpt && <p style={{ margin: '0.35rem 0 0', color: 'var(--ink-soft)' }}>{post.excerpt}</p>}
            {post.published_at && (
              <p className="mono" style={{ margin: '0.4rem 0 0', color: 'var(--ink-soft)' }}>
                {new Date(post.published_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}
              </p>
            )}
          </li>
        ))}
      </ul>
    </>
  )
}
