import { createClient } from '@manifold-cms/client'

const url = process.env.MANIFOLD_URL ?? 'http://localhost:8000'

// Guest client: the API's guestFilters() restricts it to published content.
export const manifold = createClient({ url })

// Server-only token client for draft previews; never reaches the browser.
export const manifoldPreview = () =>
  createClient({ url, token: process.env.MANIFOLD_SERVER_TOKEN ?? null })
