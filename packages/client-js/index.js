export function createClient({ url, token = null, fetch: fetchImpl = globalThis.fetch } = {}) {
  if (!url) throw new Error('createClient: `url` is required, e.g. http://localhost:8000')
  const base = url.replace(/\/+$/, '')
  let currentToken = token

  async function request(method, path, { params, body } = {}) {
    const query = params
      ? '?' + new URLSearchParams(
          Object.entries(params).flatMap(([k, v]) =>
            v == null ? []
            : typeof v === 'object' ? Object.entries(v).map(([fk, fv]) => [`${k}[${fk}]`, String(fv)])
            : [[k, String(v)]],
          ),
        )
      : ''

    const res = await fetchImpl(`${base}/api/manifold/${path}${query}`, {
      method,
      headers: {
        Accept: 'application/json',
        ...(body ? { 'Content-Type': 'application/json' } : {}),
        ...(currentToken ? { Authorization: `Bearer ${currentToken}` } : {}),
      },
      body: body ? JSON.stringify(body) : undefined,
    })

    if (res.status === 204) return null
    const json = await res.json().catch(() => null)
    if (!res.ok) {
      const error = new Error(json?.message ?? `Manifold request failed (${res.status})`)
      error.status = res.status
      error.errors = json?.errors ?? null
      throw error
    }
    return json
  }

  return {
    get token() { return currentToken },
    setToken(value) { currentToken = value },

    async login(email, password) {
      const res = await request('POST', 'auth/login', { body: { email, password } })
      currentToken = res.token
      return res
    },

    schema: () => request('GET', 'schema'),

    collection(slug) {
      return {
        list: (params = {}) => request('GET', slug, { params }),
        find: (id) => request('GET', `${slug}/${id}`).then((r) => r.data),
        create: (data) => request('POST', slug, { body: data }).then((r) => r.data),
        update: (id, data) => request('PATCH', `${slug}/${id}`, { body: data }).then((r) => r.data),
        delete: (id) => request('DELETE', `${slug}/${id}`),
      }
    },
  }
}
