export const useAuth = () => {
  const token = useState<string | null>('mf.token', () => localStorage.getItem('mf.token'))
  const user = useState<{ id: number; name: string; email: string } | null>('mf.user', () => null)

  const setToken = (value: string | null) => {
    token.value = value
    if (value) localStorage.setItem('mf.token', value)
    else localStorage.removeItem('mf.token')
  }

  const login = async (email: string, password: string) => {
    const res = await useApi()<{ token: string; user: any }>('/api/manifold/auth/login', {
      method: 'POST',
      body: { email, password },
    })
    setToken(res.token)
    user.value = res.user
  }

  const logout = async () => {
    try {
      await useApi()('/api/manifold/auth/logout', { method: 'POST' })
    } catch {}
    setToken(null)
    user.value = null
    navigateTo('/login')
  }

  return { token, user, login, logout, setToken }
}
