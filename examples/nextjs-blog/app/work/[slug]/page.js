import Link from 'next/link'
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

  const [{ data }, { data: all }] = await Promise.all([
    client.collection('case-studies').list({ filter: { slug } }),
    manifold.collection('case-studies').list({ sort: 'sort_order', perPage: 100 }),
  ])
  const cs = data[0]
  if (!cs) notFound()

  const index = all.findIndex((c) => c.slug === cs.slug)
  const next = index >= 0 ? all[(index + 1) % all.length] : null

  const meta = [
    ['Klant', cs.client],
    ['Branche', cs.industry],
    ['Jaar', cs.year],
    ['Website', cs.live_url ? <a key="w" href={cs.live_url} style={{ color: 'var(--accent)' }}>{new URL(cs.live_url).hostname.replace('www.', '')}</a> : null],
  ].filter(([, value]) => value)

  return (
    <article>
      <p className="mono" style={{ color: 'var(--accent)' }}>Cases / {cs.category}</p>
      <h1 style={{ fontSize: 'clamp(2rem, 4.5vw, 3.4rem)', margin: '0.2em 0', maxWidth: '18em' }}>{cs.title}</h1>
      <p style={{ fontSize: '1.2rem', color: 'var(--ink-soft)', maxWidth: '40em' }}>{cs.tagline}</p>

      {cs.hero && (
        <figure style={{ margin: '2.5rem 0', borderRadius: '20px', overflow: 'hidden', border: '1px solid var(--line)', boxShadow: '0 24px 60px -30px rgba(25,23,18,0.5)' }}>
          <img src={cs.hero.url} alt={`${cs.title} hero`} style={{ display: 'block', width: '100%' }} />
        </figure>
      )}

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(160px, 1fr))', gap: '1.5rem', borderTop: '3px solid var(--ink)', borderBottom: '1px solid var(--line)', padding: '1.75rem 0', margin: '2rem 0' }}>
        {meta.map(([label, value]) => (
          <div key={label}>
            <p className="mono" style={{ margin: '0 0 0.3rem', color: 'var(--ink-soft)' }}>{label}</p>
            <p style={{ margin: 0, fontFamily: 'system-ui', fontWeight: 700 }}>{value}</p>
          </div>
        ))}
        {(cs.metrics ?? []).map((metric, i) => (
          <div key={`m${i}`}>
            <p className="mono" style={{ margin: '0 0 0.3rem', color: 'var(--ink-soft)' }}>{metric.label}</p>
            <p style={{ margin: 0, fontFamily: 'system-ui', fontWeight: 800, fontSize: '1.4rem' }}>{metric.value}</p>
          </div>
        ))}
      </div>

      {(cs.roles ?? []).length > 0 && (
        <div style={{ margin: '0 0 2.5rem' }}>
          <p className="mono" style={{ color: 'var(--ink-soft)', marginBottom: '0.6rem' }}>Rollen</p>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem' }}>
            {cs.roles.map((role, i) => (
              <span key={i} className="mono" style={{ border: '1px solid var(--ink)', borderRadius: '999px', padding: '0.4rem 0.9rem' }}>
                {role.name}
              </span>
            ))}
          </div>
        </div>
      )}

      <div className="case-body" style={{ maxWidth: '46em' }} dangerouslySetInnerHTML={{ __html: cs.body ?? '' }} />

      {(cs.gallery ?? []).length > 0 && (
        <section style={{ margin: '3.5rem 0' }}>
          <h2>Beelden</h2>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))', gap: '1.25rem' }}>
            {cs.gallery.map((item, i) => (
              <figure key={i} style={{ margin: 0, borderRadius: '14px', overflow: 'hidden', border: '1px solid var(--line)', background: 'var(--panel)' }}>
                <img src={item.url} alt={item.caption ?? ''} loading="lazy" decoding="async" style={{ display: 'block', width: '100%', aspectRatio: '16/10', objectFit: 'cover' }} />
                {item.caption && <figcaption className="mono" style={{ padding: '0.6rem 0.9rem', color: 'var(--ink-soft)' }}>{item.caption}</figcaption>}
              </figure>
            ))}
          </div>
        </section>
      )}

      <div style={{ display: 'flex', gap: '1rem', flexWrap: 'wrap', margin: '2.5rem 0' }}>
        {cs.repo_url && (
          <a href={cs.repo_url} style={{ background: 'var(--ink)', color: 'var(--paper)', padding: '0.7rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700, borderRadius: '999px' }}>
            View the code →
          </a>
        )}
        {cs.live_url && (
          <a href={cs.live_url} style={{ border: '2px solid var(--ink)', padding: '0.7rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700, borderRadius: '999px' }}>
            Bekijk live
          </a>
        )}
      </div>

      {next && (
        <Link href={`/work/${next.slug}`} className="case-card" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', gap: '1.5rem', marginTop: '3rem' }}>
          <div>
            <p className="mono" style={{ margin: '0 0 0.4rem', color: 'var(--ink-soft)' }}>Volgende case</p>
            <h3 style={{ margin: 0 }}>{next.title}</h3>
          </div>
          <span className="case-btn" style={{ marginTop: 0 }}>→</span>
        </Link>
      )}
    </article>
  )
}
