export const useApi = () => {
  const { token, setToken } = useAuth()

  return $fetch.create({
    onRequest({ options }) {
      const headers = new Headers(options.headers)
      headers.set('Accept', 'application/json')
      if (token.value) headers.set('Authorization', `Bearer ${token.value}`)
      options.headers = headers
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        setToken(null)
        navigateTo('/login')
      }
    },
  })
}
