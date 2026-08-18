import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Manifold',
  ignoreDeadLinks: 'localhostLinks',
  description: 'Code-first headless CMS for Laravel + Nuxt',
  themeConfig: {
    nav: [
      { text: 'Guide', link: '/guide/getting-started' },
      { text: 'GitHub', link: 'https://github.com/multidevagency/manifold' },
    ],
    sidebar: [
      {
        text: 'Getting started',
        items: [
          { text: 'Introduction', link: '/guide/introduction' },
          { text: 'Quick start', link: '/guide/getting-started' },
        ],
      },
      {
        text: 'Core concepts',
        items: [
          { text: 'Collections', link: '/guide/collections' },
          { text: 'Fields', link: '/guide/fields' },
          { text: 'Schema migrations', link: '/guide/schema-migrations' },
          { text: 'Access control', link: '/guide/access-control' },
        ],
      },
      {
        text: 'APIs & clients',
        items: [
          { text: 'REST API', link: '/guide/rest-api' },
          { text: 'JavaScript client', link: '/guide/js-client' },
          { text: 'CLI', link: '/guide/cli' },
        ],
      },
      {
        text: 'Admin panel',
        items: [
          { text: 'Live preview', link: '/guide/live-preview' },
          { text: 'AI content generation', link: '/guide/ai' },
          { text: 'Editing schema from the UI', link: '/guide/schema-editing' },
        ],
      },
      {
        text: 'Building frontends',
        items: [
          { text: 'Next.js & other frameworks', link: '/guide/frontends' },
          { text: 'SEO & GEO', link: '/guide/seo' },
          { text: 'Ecommerce', link: '/guide/ecommerce' },
        ],
      },
      { text: 'Roadmap', link: '/guide/roadmap' },
    ],
    search: { provider: 'local' },
    footer: { message: 'MIT Licensed', copyright: 'Manifold' },
  },
})
