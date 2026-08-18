import './globals.css'
import SiteHeader from './components/SiteHeader'
import SiteFooter from './components/SiteFooter'

export const metadata = {
  title: { default: 'Manifold × Next.js', template: '%s — Manifold' },
  description: 'Demo site rendered by Next.js, content managed by Manifold CMS.',
}

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body>
        <SiteHeader />
        <main className="page">
          <div className="container">{children}</div>
        </main>
        <SiteFooter />
      </body>
    </html>
  )
}
