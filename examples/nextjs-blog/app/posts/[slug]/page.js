import { notFound } from 'next/navigation'
import { manifold, manifoldPreview } from '../../../lib/manifold'

export const dynamic = 'force-dynamic'

export default async function PostPage({ params, searchParams }) {
  const { slug } = await params
  const { preview } = await searchParams

  // preview=1 uses the server token, so the Manifold admin can preview drafts.
  const client = preview ? manifoldPreview() : manifold
  const { data } = await client.collection('posts').list({ filter: { slug } })
  const post = data[0]

  if (!post) notFound()

  return (
    <article>
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
