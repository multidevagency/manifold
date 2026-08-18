import { notFound } from 'next/navigation'
import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'

const renderBlock = (block, i) => {
  switch (block.blockType) {
    case 'content':
      return <div key={i} dangerouslySetInnerHTML={{ __html: block.body ?? '' }} />
    case 'cta':
      return (
        <p key={i}>
          <a href={block.url} style={{ display: 'inline-block', background: '#191712', color: '#f2efe6', padding: '0.6rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700 }}>
            {block.label}
          </a>
        </p>
      )
    case 'media':
      return (
        <figure key={i}>
          {block.file && <img src={block.file.url} alt="" style={{ maxWidth: '100%' }} />}
          {block.caption && <figcaption>{block.caption}</figcaption>}
        </figure>
      )
    default:
      return null
  }
}

export default async function Page({ params }) {
  const { slug } = await params
  const { data } = await manifold.collection('pages').list({ filter: { slug } })
  const page = data[0]
  if (!page || page.status !== 'published') notFound()

  return (
    <>
      {page.hero?.heading && (
        <header style={{ borderBottom: '3px solid #191712', marginBottom: '2rem', paddingBottom: '1rem' }}>
          <h1 style={{ marginBottom: 0 }}>{page.hero.heading}</h1>
          {page.hero.subheading && <p style={{ color: '#6b6657' }}>{page.hero.subheading}</p>}
        </header>
      )}
      {!page.hero?.heading && <h1>{page.title}</h1>}

      {(page.layout ?? []).map(renderBlock)}

      {page.content && !page.layout?.length && <div dangerouslySetInnerHTML={{ __html: page.content }} />}

      {(page.faq ?? []).length > 0 && (
        <>
          <h2>FAQ</h2>
          {page.faq.map((item, i) => (
            <details key={i} style={{ borderBottom: '1px solid #d9d4c4', padding: '0.5rem 0' }}>
              <summary style={{ fontWeight: 700, cursor: 'pointer' }}>{item.question}</summary>
              <p>{item.answer}</p>
            </details>
          ))}
        </>
      )}
    </>
  )
}
