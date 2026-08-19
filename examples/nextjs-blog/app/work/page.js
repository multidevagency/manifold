import Link from 'next/link'
import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'
export const metadata = { title: 'Cases' }

export default async function Work() {
  const { data: studies } = await manifold.collection('case-studies').list({ sort: 'sort_order', perPage: 100 })

  return (
    <>
      <p className="mono" style={{ color: 'var(--accent)' }}>Werk / Cases</p>
      <h1 style={{ marginTop: '0.2em' }}>Recent werk</h1>
      <p style={{ maxWidth: '44em', color: 'var(--ink-soft)', marginBottom: '3rem' }}>
        Een selectie van trajecten die ik de afgelopen periode heb opgeleverd — van eenmalige
        websites tot meerjarige platformen, en de tools waar ze op draaien.
      </p>

      <ul className="case-grid" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
        {studies.map((cs) => (
          <li key={cs.id}>
            <Link href={`/work/${cs.slug}`} className="case-card">
              <figure>
                {cs.hero && <img src={cs.hero.url} alt={`${cs.title} preview`} loading="lazy" decoding="async" />}
              </figure>
              <span className="mono case-type">{cs.stack && cs.category === 'client-work' ? cs.stack.split(',')[0] : cs.category}{cs.year ? ` · ${cs.year}` : ''}</span>
              <h2>{cs.title}</h2>
              <p>{cs.tagline || cs.summary}</p>
              <span className="case-btn">Bekijk case <span aria-hidden="true">→</span></span>
            </Link>
          </li>
        ))}
      </ul>
    </>
  )
}
