import HaulFooter from '../components/HaulFooter'

export const metadata = { title: 'HAUL! footer demo' }

export default function FooterDemo() {
  // Full-bleed out of the site's centered column.
  return (
    <div style={{ width: '100vw', marginLeft: 'calc(50% - 50vw)', marginTop: '-3rem', marginBottom: '-5rem' }}>
      <HaulFooter />
    </div>
  )
}
