import './globals.css'
import SiteHeader from './components/SiteHeader'
import HaulFooter from './components/HaulFooter'
import { manifold } from '../lib/manifold'

export const metadata = {
  title: { default: 'Manifold × Next.js', template: '%s — Manifold' },
  description: 'Demo site rendered by Next.js, content managed by Manifold CMS.',
}

export default async function RootLayout({ children }) {
  const footer = await manifold.global('footer').get().catch(() => ({}))
  const header = await manifold.global('header').get().catch(() => ({}))

  return (
    <html lang="en">
      <body>
        <SiteHeader />
        <main className="page">
          <div className="container">{children}</div>
        </main>
        <HaulFooter brand={header.brand ?? 'MANIFOLD'} links={footer.links ?? []} copyright={footer.copyright ?? ''} />
      </body>
    </html>
  )
}
