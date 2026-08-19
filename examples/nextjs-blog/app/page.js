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
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline' }}>
          <h2 style={{ fontSize: '2rem' }}>Selected work</h2>
          <Link href="/work" className="mono" style={{ color: 'var(--accent)' }}>All work →</Link>
        </div>
        <div style={{ display: 'grid', gap: '1.5rem' }}>
          {featured.map((cs) => (
            <Link
              key={cs.id}
              href={`/work/${cs.slug}`}
              className="card"
              style={{ display: 'flex', flexWrap: 'wrap', gap: '1.5rem', justifyContent: 'space-between', alignItems: 'baseline', padding: '1.5rem' }}
            >
              <div style={{ maxWidth: '38em' }}>
                <span className="mono" style={{ color: 'var(--accent)' }}>{cs.category}</span>
                <h3 style={{ margin: '0.3em 0', fontFamily: 'system-ui', fontSize: '1.4rem' }}>{cs.title}</h3>
                <p style={{ margin: 0, color: 'var(--ink-soft)' }}>{cs.tagline}</p>
              </div>
              <div style={{ display: 'flex', gap: '2rem' }}>
                {(cs.metrics ?? []).slice(0, 2).map((m, i) => (
                  <div key={i}>
                    <strong style={{ display: 'block', fontFamily: 'system-ui', fontSize: '1.6rem' }}>{m.value}</strong>
                    <span className="mono" style={{ color: 'var(--ink-soft)' }}>{m.label}</span>
                  </div>
                ))}
              </div>
            </Link>
          ))}
        </div>
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
