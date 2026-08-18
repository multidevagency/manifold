const base = process.env.SITE_URL ?? 'http://localhost:3001'

export default function robots() {
  return {
    rules: [{ userAgent: '*', allow: '/' }],
    sitemap: `${base}/sitemap.xml`,
  }
}
