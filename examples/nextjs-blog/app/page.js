import Link from 'next/link'
import { manifold } from '../lib/manifold'
import ScrollHero from './components/ScrollHero'

export const dynamic = 'force-dynamic'

export default async function Home() {
  const [profile, { data: featured }, { data: posts }] = await Promise.all([
    manifold.global('profile').get().catch(() => ({})),
    manifold.collection('case-studies').list({ filter: { featured: 1 }, sort: 'sort_order' }),
    manifold.collection('posts').list({ sort: '-published_at', perPage: 3 }),
  ])

  return (
    <>
      <ScrollHero />

      <section style={{ padding: '2rem 0 4rem', borderBottom: '3px solid var(--ink)' }}>
        <p className="mono" style={{ color: 'var(--accent)' }}>{profile.name}</p>
        <h1 style={{ fontSize: 'clamp(2.2rem, 5vw, 4rem)', lineHeight: 1.05, margin: '0.3em 0', maxWidth: '16em' }}>
          {profile.headline}
        </h1>
        <p style={{ maxWidth: '44em', color: 'var(--ink-soft)', fontSize: '1.1rem' }}>{profile.intro}</p>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem', marginTop: '1.5rem' }}>
          {(profile.skills ?? []).map((skill, i) => (
            <span key={i} className="mono" style={{ border: '1px solid var(--ink)', padding: '0.35rem 0.7rem' }}>
              {skill.name}
            </span>
          ))}
        </div>
      </section>

      <section style={{ padding: '3rem 0' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline', marginBottom: '2rem' }}>
          <h2 style={{ fontSize: '2rem', margin: 0 }}>Selected work</h2>
          <Link href="/work" className="mono" style={{ color: 'var(--accent)' }}>Alle cases →</Link>
        </div>
        <ul className="case-grid" style={{ listStyle: 'none', padding: 0, margin: 0 }}>
          {featured.map((cs) => (
            <li key={cs.id}>
              <Link href={`/work/${cs.slug}`} className="case-card">
                <figure>
                  {cs.hero && <img src={cs.hero.url} alt={`${cs.title} preview`} loading="lazy" decoding="async" />}
                </figure>
                <span className="mono case-type">{cs.category}</span>
                <h3>{cs.title}</h3>
                <p>{cs.tagline}</p>
                <span className="case-btn">Bekijk case <span aria-hidden="true">→</span></span>
              </Link>
            </li>
          ))}
        </ul>
      </section>

      <section style={{ padding: '1rem 0 2rem', borderTop: '1px solid var(--line)' }}>
        <h2>Writing</h2>
        <ul style={{ listStyle: 'none', padding: 0 }}>
          {posts.map((post) => (
            <li key={post.id} style={{ padding: '0.6rem 0' }}>
              <Link href={`/posts/${post.slug}`} style={{ fontWeight: 700, fontFamily: 'system-ui', textDecoration: 'none' }}>
                {post.title}
              </Link>
            </li>
          ))}
        </ul>
      </section>
    </>
  )
}
