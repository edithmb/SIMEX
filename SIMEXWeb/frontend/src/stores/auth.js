import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoleStore } from '@/stores/role'
import axios from 'axios'

const LARAVEL = import.meta.env.VITE_LARAVEL_API

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
    localStorage.removeItem('user_role')
  }

  async function login(email, password) {
    let res
    try {
      res = await axios.post(LARAVEL + '/login', { email, password })
    } catch (err) {
      const message = err.response?.data?.message ?? 'Credenciales incorrectas'
      throw new Error(message)
    }

    setToken(res.data.token)

    try {
      const meRes = await axios.get(LARAVEL + '/me', {
        headers: { Authorization: `Bearer ${token.value}` },
      })
      const roleName = meRes.data.role?.name ?? 'cliente'
      const roleStore = useRoleStore()
      roleStore.setRole(roleName)
    } catch {
      // si /me falla, seguimos autenticados igualmente
    }
  }

  async function logout() {
    if (token.value) {
      try {
        await axios.post(LARAVEL + '/logout', null, {
          headers: { Authorization: `Bearer ${token.value}` },
        })
      } catch {
        // si el backend rechaza (token ya inválido, red caída…), seguimos limpiando en cliente
      }
    }
    clearToken()
  }

  return { token, isAuthenticated, login, logout }
})
