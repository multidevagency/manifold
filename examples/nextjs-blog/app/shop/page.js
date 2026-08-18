import Link from 'next/link'
import { manifold } from '../../lib/manifold'

export const dynamic = 'force-dynamic'
export const metadata = { title: 'Shop' }

export default async function Shop() {
  const { data: products } = await manifold.collection('products').list({ sort: 'title' })

  return (
    <>
      <h1>Shop</h1>
      <div className="card-grid">
        {products.map((product) => (
          <Link key={product.id} href={`/shop/${product.slug}`} className="card">
            {product.image && <img src={product.image.url} alt="" style={{ width: '100%', aspectRatio: '1', objectFit: 'cover' }} />}
            <strong style={{ display: 'block', fontFamily: 'system-ui' }}>{product.title}</strong>
            <span className="mono">€{Number(product.price).toFixed(2)}</span>
            {!product.in_stock && <em style={{ display: 'block', color: 'var(--accent)' }}>Out of stock</em>}
          </Link>
        ))}
      </div>
    </>
  )
}
