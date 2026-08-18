import Link from 'next/link'
import { manifold } from '../../lib/manifold'

export default async function SiteHeader() {
  const header = await manifold.global('header').get().catch(() => ({}))
  const nav = header.nav ?? []
  const brand = header.brand ?? 'MANIFOLD'

  return (
    <header className="site-header">
      <div className="container inner">
        <Link href="/" className="brand">
          {brand}<span>.</span>
        </Link>
        <nav className="site-nav">
          {nav.map((item, i) => (
            <Link key={i} href={item.url}>{item.label}</Link>
          ))}
        </nav>
      </div>
    </header>
  )
}
