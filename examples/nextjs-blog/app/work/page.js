import Link from 'next/link'
import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'
export const metadata = { title: 'Work' }

export default async function Work() {
  const { data: studies } = await manifold.collection('case-studies').list({ sort: 'sort_order', perPage: 50 })

  return (
    <>
      <h1>Work</h1>
      <div style={{ display: 'grid', gap: '1.5rem' }}>
        {studies.map((cs) => (
          <Link key={cs.id} href={`/work/${cs.slug}`} className="card" style={{ padding: '1.5rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', gap: '1rem', flexWrap: 'wrap' }}>
              <span className="mono" style={{ color: 'var(--accent)' }}>{cs.category} · {cs.year}</span>
              <span className="mono" style={{ color: 'var(--ink-soft)' }}>{cs.stack}</span>
            </div>
            <h2 style={{ margin: '0.4em 0 0.2em', fontSize: '1.5rem' }}>{cs.title}</h2>
            <p style={{ margin: 0, color: 'var(--ink-soft)' }}>{cs.summary}</p>
          </Link>
        ))}
      </div>
    </>
  )
}
