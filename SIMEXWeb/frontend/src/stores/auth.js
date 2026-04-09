import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('jwt_token') ?? null)
  const isAuthenticated = computed(() => !!token.value)

  function setToken(newToken) {
    token.value = newToken
    localStorage.setItem('jwt_token', newToken)
  }

  function clearToken() {
    token.value = null
    localStorage.removeItem('jwt_token')
  }

  async function login(email, password) {
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email, password }),
    })

    if (!res.ok) {
      const data = await res.json()
      throw new Error(data.message ?? 'Credenciales incorrectas')
    }

    const data = await res.json()
    setToken(data.token)
  }

  function logout() {
    clearToken()
  }

  return { token, isAuthenticated, login, logout }
})
