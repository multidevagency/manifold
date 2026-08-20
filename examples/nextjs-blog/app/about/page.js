import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'
export const metadata = { title: 'Over mij', description: 'Full-stack developer — React/Next.js, Vue/Nuxt, Node.js, Laravel en AI-integraties.' }

export default async function About() {
  const profile = await manifold.global('profile').get().catch(() => ({}))

  return (
    <>
      <p className="mono" style={{ color: 'var(--accent)' }}>Over mij</p>
      <h1 style={{ fontSize: 'clamp(2.2rem, 4.5vw, 3.4rem)', margin: '0.2em 0' }}>{profile.name}</h1>
      <p style={{ fontSize: '1.15rem', color: 'var(--ink-soft)', maxWidth: '42em' }}>{profile.bio}</p>

      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '1rem', margin: '1.5rem 0 3rem', alignItems: 'center' }}>
        {profile.cv && (
          <a href={profile.cv.url} download style={{ background: 'var(--ink)', color: 'var(--paper)', padding: '0.7rem 1.4rem', textDecoration: 'none', fontFamily: 'system-ui', fontWeight: 700, borderRadius: '999px' }}>
            Download CV (PDF) ↓
          </a>
        )}
        {profile.linkedin && <a href={profile.linkedin} className="mono" style={{ color: 'var(--accent)' }}>LinkedIn</a>}
        {profile.github && <a href={profile.github} className="mono" style={{ color: 'var(--accent)' }}>GitHub</a>}
        {profile.location && <span className="mono" style={{ color: 'var(--ink-soft)' }}>📍 {profile.location}</span>}
      </div>

      <section style={{ borderTop: '3px solid var(--ink)', padding: '2rem 0' }}>
        <h2>Werkervaring</h2>
        {(profile.experience ?? []).map((job, i) => (
          <div key={i} style={{ display: 'grid', gridTemplateColumns: '160px 1fr', gap: '1.5rem', padding: '1.25rem 0', borderBottom: '1px solid var(--line)' }}>
            <span className="mono" style={{ color: 'var(--ink-soft)' }}>{job.period}</span>
            <div>
              <h3 style={{ margin: '0 0 0.2em', fontFamily: 'system-ui' }}>{job.role}</h3>
              <p className="mono" style={{ margin: '0 0 0.6em', color: 'var(--accent)' }}>{job.company}</p>
              <p style={{ margin: 0, color: 'var(--ink-soft)' }}>{job.description}</p>
            </div>
          </div>
        ))}
      </section>

      <section style={{ padding: '1rem 0 2rem' }}>
        <h2>Expertise</h2>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem' }}>
          {(profile.skills ?? []).map((skill, i) => (
            <span key={i} className="mono" style={{ border: '1px solid var(--ink)', borderRadius: '999px', padding: '0.4rem 0.9rem' }}>{skill.name}</span>
          ))}
        </div>
      </section>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '2rem', padding: '1rem 0 2rem' }}>
        <section>
          <h2>Opleiding</h2>
          {(profile.education ?? []).map((ed, i) => (
            <div key={i} style={{ padding: '0.6rem 0', borderBottom: '1px solid var(--line)' }}>
              <strong style={{ fontFamily: 'system-ui' }}>{ed.name}</strong>
              <p className="mono" style={{ margin: '0.2rem 0 0', color: 'var(--ink-soft)' }}>{ed.institution} · {ed.period}</p>
            </div>
          ))}
        </section>
        <section>
          <h2>Talen</h2>
          {(profile.languages ?? []).map((lang, i) => (
            <div key={i} style={{ display: 'flex', justifyContent: 'space-between', padding: '0.6rem 0', borderBottom: '1px solid var(--line)' }}>
              <strong style={{ fontFamily: 'system-ui' }}>{lang.name}</strong>
              <span className="mono" style={{ color: 'var(--ink-soft)' }}>{lang.level}</span>
            </div>
          ))}
        </section>
      </div>

      {profile.cv && (
        <section style={{ padding: '1rem 0 2rem' }}>
          <h2>CV</h2>
          <object data={profile.cv.url} type="application/pdf" style={{ width: '100%', height: '80vh', border: '2px solid var(--ink)', borderRadius: '12px' }}>
            <p>
              PDF-weergave wordt niet ondersteund in deze browser —{' '}
              <a href={profile.cv.url} style={{ color: 'var(--accent)' }}>download het CV</a>.
            </p>
          </object>
        </section>
      )}
    </>
  )
}
