import { setActivePinia, createPinia } from 'pinia'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useRoleStore } from '@/stores/role'

describe('auth store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('starts unauthenticated when localStorage has no token', () => {
    const store = useAuthStore()
    expect(store.token).toBeNull()
    expect(store.isAuthenticated).toBe(false)
  })

  it('starts authenticated when localStorage has a token', () => {
    localStorage.setItem('jwt_token', 'existing-token')
    setActivePinia(createPinia())
    const store = useAuthStore()
    expect(store.token).toBe('existing-token')
    expect(store.isAuthenticated).toBe(true)
  })

  it('login sets token and fetches role on success', async () => {
    axios.post.mockResolvedValueOnce({ data: { token: 'abc123' } })
    axios.get.mockResolvedValueOnce({ data: { role: { name: 'cliente' } } })

    const store = useAuthStore()
    await store.login('user@test.com', 'pass')

    expect(store.token).toBe('abc123')
    expect(store.isAuthenticated).toBe(true)
    expect(localStorage.getItem('jwt_token')).toBe('abc123')

    expect(axios.post).toHaveBeenCalledWith('http://localhost:8000/api/login', {
      email: 'user@test.com',
      password: 'pass',
    })
    expect(axios.get).toHaveBeenCalledWith('http://localhost:8000/api/me', {
      headers: { Authorization: 'Bearer abc123' },
    })

    const roleStore = useRoleStore()
    expect(roleStore.currentRole).toBe('cliente')
  })

  it('login succeeds even if /me endpoint fails', async () => {
    axios.post.mockResolvedValueOnce({ data: { token: 'abc123' } })
    axios.get.mockRejectedValueOnce(new Error('Network error'))

    const store = useAuthStore()
    await store.login('user@test.com', 'pass')

    expect(store.isAuthenticated).toBe(true)
  })

  it('login defaults role to cliente when /me has no role name', async () => {
    axios.post.mockResolvedValueOnce({ data: { token: 'tok' } })
    axios.get.mockResolvedValueOnce({ data: {} })

    const store = useAuthStore()
    await store.login('a@b.com', 'x')

    const roleStore = useRoleStore()
    expect(roleStore.currentRole).toBe('cliente')
  })

  it('login throws with server message on failure', async () => {
    axios.post.mockRejectedValueOnce({
      response: { data: { message: 'Usuario no encontrado' } },
    })

    const store = useAuthStore()
    await expect(store.login('bad@test.com', 'wrong')).rejects.toThrow(
      'Usuario no encontrado',
    )

    expect(store.isAuthenticated).toBe(false)
  })

  it('logout clears token and localStorage', () => {
    localStorage.setItem('jwt_token', 'tok')
    localStorage.setItem('user_role', 'admin')
    setActivePinia(createPinia())
    const store = useAuthStore()
    store.logout()

    expect(store.token).toBeNull()
    expect(store.isAuthenticated).toBe(false)
    expect(localStorage.getItem('jwt_token')).toBeNull()
    expect(localStorage.getItem('user_role')).toBeNull()
  })
})
