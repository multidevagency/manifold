import { manifold } from '../../lib/manifold'

export default async function SiteFooter() {
  const footer = await manifold.global('footer').get().catch(() => ({}))

  return (
    <footer className="site-footer">
      <div className="container inner">
        <div style={{ maxWidth: '340px' }}>
          <p className="mono" style={{ color: 'var(--accent)' }}>Manifold</p>
          {footer.tagline && <p style={{ margin: '0.4rem 0 0', color: 'var(--ink-soft)' }}>{footer.tagline}</p>}
        </div>
        <nav>
          {(footer.links ?? []).map((link, i) => (
            <a key={i} href={link.url}>{link.label}</a>
          ))}
        </nav>
        <p className="mono" style={{ color: 'var(--ink-soft)' }}>{footer.copyright}</p>
      </div>
    </footer>
  )
}
