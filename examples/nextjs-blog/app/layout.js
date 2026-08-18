export const metadata = { title: 'Manifold × Next.js' }

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body style={{ fontFamily: 'Georgia, serif', maxWidth: 640, margin: '0 auto', padding: '3rem 1.5rem', lineHeight: 1.6 }}>
        <header style={{ borderBottom: '3px solid #191712', paddingBottom: '1rem', marginBottom: '2rem' }}>
          <strong style={{ fontFamily: 'system-ui', letterSpacing: '0.05em' }}>MANIFOLD × NEXT.JS</strong>
          <nav style={{ float: 'right', fontFamily: 'system-ui', display: 'flex', gap: '1rem' }}>
            <a href="/">Blog</a>
            <a href="/shop">Shop</a>
            <a href="/about">About</a>
          </nav>
        </header>
        {children}
      </body>
    </html>
  )
}
