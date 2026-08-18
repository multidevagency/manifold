import { notFound } from 'next/navigation'
import { manifold, manifoldPreview } from '../../../lib/manifold'

export const dynamic = 'force-dynamic'

export async function generateMetadata({ params }) {
  const { slug } = await params
  const { data } = await manifold.collection('posts').list({ filter: { slug } })
  const post = data[0]
  if (!post) return {}

  return {
    title: post.meta_title || post.title,
    description: post.meta_description || post.excerpt || undefined,
    openGraph: {
      title: post.meta_title || post.title,
      description: post.meta_description || post.excerpt || undefined,
      type: 'article',
      publishedTime: post.published_at || undefined,
    },
  }
}

export default async function PostPage({ params, searchParams }) {
  const { slug } = await params
  const { preview } = await searchParams

  // preview=1 uses the server token, so the Manifold admin can preview drafts.
  const client = preview ? manifoldPreview() : manifold
  const { data } = await client.collection('posts').list({ filter: { slug } })
  const post = data[0]

  if (!post) notFound()

  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'Article',
    headline: post.title,
    description: post.meta_description || post.excerpt || undefined,
    datePublished: post.published_at || undefined,
    dateModified: post.updated_at,
  }

  return (
    <article>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
      {post.status !== 'published' && (
        <p style={{ background: '#fbe0d4', borderLeft: '4px solid #e8490f', padding: '0.5rem 1rem', fontFamily: 'system-ui', fontSize: '0.85rem' }}>
          Draft preview — this post is not public.
        </p>
      )}
      <h1>{post.title}</h1>
      <div dangerouslySetInnerHTML={{ __html: post.body ?? '' }} />
    </article>
  )
}
