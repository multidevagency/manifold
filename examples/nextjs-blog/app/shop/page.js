import Link from 'next/link'
import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'
export const metadata = { title: 'Shop — Manifold × Next.js' }

export default async function Shop() {
  const { data: products } = await manifold.collection('products').list({ sort: 'title' })

  return (
    <main>
      <h1>Shop</h1>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(180px, 1fr))', gap: '1.5rem' }}>
        {products.map((product) => (
          <Link key={product.id} href={`/shop/${product.slug}`} style={{ border: '2px solid #191712', padding: '1rem', textDecoration: 'none', color: 'inherit', boxShadow: '4px 4px 0 #191712' }}>
            {product.image && <img src={product.image.url} alt="" style={{ width: '100%', aspectRatio: '1', objectFit: 'cover' }} />}
            <strong style={{ display: 'block' }}>{product.title}</strong>
            <span style={{ fontFamily: 'monospace' }}>€{Number(product.price).toFixed(2)}</span>
            {!product.in_stock && <em style={{ display: 'block', color: '#e8490f' }}>Out of stock</em>}
          </Link>
        ))}
      </div>
    </main>
  )
}
