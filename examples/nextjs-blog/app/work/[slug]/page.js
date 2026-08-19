import { notFound } from 'next/navigation'
import { manifold, manifoldPreview } from '../../../lib/manifold'

export const dynamic = 'force-dynamic'

export async function generateMetadata({ params }) {
  const { slug } = await params
  const { data } = await manifold.collection('case-studies').list({ filter: { slug } })
  const cs = data[0]
  if (!cs) return {}
  return { title: cs.meta_title || cs.title, description: cs.meta_description || cs.tagline || undefined }
}

export default async function CaseStudy({ params, searchParams }) {
  const { slug } = await params
  const { preview } = await searchParams
  const client = preview ? manifoldPreview() : manifold
  const { data } = await client.collection('case-studies').list({ filter: { slug } })
  const cs = data[0]
  if (!cs) notFound()

  return (
    <article>
      <p className="mono" style={{ color: 'var(--accent)' }}>{cs.category} · {cs.year}</p>
      <h1 style={{ fontSize: 'clamp(2rem, 4vw, 3rem)', margin: '0.2em 0' }}>{cs.title}</h1>
      <p style={{ fontSize: '1.2rem', color: 'var(--ink-soft)', maxWidth: '40em' }}>{cs.tagline}</p>

      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '2.5rem', borderTop: '3px solid var(--ink)', borderBottom: '1px solid var(--line)', padding: '1.5rem 0', margin: '2rem 0' }}>
        {(cs.metrics ?? []).map((metric, i) => (
          <div key={i}>
            <strong style={{ display: 'block', fontFamily: 'system-ui', fontSize: '2rem' }}>{metric.value}</strong>
            <span className="mono" style={{ color: 'var(--ink-soft)' }}>{metric.label}</span>
          </div>
        ))}
      </div>

      {cs.hero && <img src={cs.hero.url} alt="" style={{ maxWidth: '100%', border: '2px solid var(--ink)' }} />}

      <div style={{ maxWidth: '44em' }} dangerouslySetInnerHTML={{ __html: cs.body ?? '' }} />

      <p className="mono" style={{ margin: '2rem 0 0.5rem', color: 'var(--ink-soft)' }}>Stack: {cs.stack}</p>
      <div style={{ display: 'flex', gap: '1rem', marginTop: '1rem' }}>
        {cs.repo_url && (
          <a href={cs.repo_url} style={{ background: 'var(--ink)', color: 'var(--paper)', padding: '0.7rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700 }}>
            View the code →
          </a>
        )}
        {cs.live_url && (
          <a href={cs.live_url} style={{ border: '2px solid var(--ink)', padding: '0.7rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700 }}>
            Visit live
          </a>
        )}
      </div>
    </article>
  )
}
