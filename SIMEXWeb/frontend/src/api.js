import { useAuthStore } from '@/stores/auth'

export async function apiFetch(method, path, body) {
  const auth = useAuthStore()

  const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }

  if (auth.token) {
    headers['Authorization'] = `Bearer ${auth.token}`
  }

  const opts = { method, headers }
  if (body) opts.body = JSON.stringify(body)

  const res = await fetch('/api' + path, opts)

  if (res.status === 204) return null

  if (res.status === 401) {
    auth.logout()
    window.location.href = '/login'
    throw new Error('No autenticado')
  }

  if (!res.ok) throw new Error(`Error ${res.status}`)

  return res.json()
}
