import Link from 'next/link'
import { manifold } from '../lib/manifold'
import ScrollHero from './components/ScrollHero'

export const dynamic = 'force-dynamic'

export default async function Home() {
  const { data: posts } = await manifold.collection('posts').list({ sort: '-published_at' })

  return (
    <main>
      <ScrollHero />
      <h1>Blog</h1>
      <ul style={{ listStyle: 'none', padding: 0 }}>
        {posts.map((post) => (
          <li key={post.id} style={{ marginBottom: '1.5rem' }}>
            <Link href={`/posts/${post.slug}`} style={{ fontSize: '1.2rem', fontWeight: 700 }}>
              {post.title}
            </Link>
            {post.excerpt && <p style={{ margin: '0.25rem 0', color: '#555' }}>{post.excerpt}</p>}
          </li>
        ))}
      </ul>
    </main>
  )
}
