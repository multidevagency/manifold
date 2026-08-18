import { notFound } from 'next/navigation'
import { manifold } from '../../../lib/manifold'

export const dynamic = 'force-dynamic'

export default async function ProductPage({ params }) {
  const { slug } = await params
  const { data } = await manifold.collection('products').list({ filter: { slug } })
  const product = data[0]
  if (!product) notFound()

  const variants = product.variants ?? []

  return (
    <>
      <h1>{product.title}</h1>
      <p style={{ fontFamily: 'monospace', fontSize: '1.4rem' }}>€{Number(product.price).toFixed(2)}</p>
      {product.image && <img src={product.image.url} alt="" style={{ maxWidth: '100%' }} />}
      <div dangerouslySetInnerHTML={{ __html: product.description ?? '' }} />

      {variants.length > 0 && (
        <>
          <h2>Variants</h2>
          <table style={{ borderCollapse: 'collapse', width: '100%' }}>
            <tbody>
              {variants.map((v, i) => (
                <tr key={i} style={{ borderBottom: '1px solid #d9d4c4' }}>
                  <td style={{ padding: '0.5rem 0' }}>{v.name}</td>
                  <td style={{ fontFamily: 'monospace' }}>{v.sku}</td>
                  <td style={{ fontFamily: 'monospace' }}>€{Number(v.price ?? product.price).toFixed(2)}</td>
                  <td style={{ color: v.in_stock ? '#22764a' : '#e8490f' }}>{v.in_stock ? 'In stock' : 'Out of stock'}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </>
      )}
    </>
  )
}
