import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoleStore } from '@/stores/role'
import * as authService from '@/services/authService'

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
      res = await authService.login(email, password)
    } catch (err) {
      const message = err.response?.data?.message ?? 'Credenciales incorrectas'
      throw new Error(message)
    }

    setToken(res.data.token)

    try {
      const meRes = await authService.me()
      const roleName = meRes.data.role?.name ?? 'cliente'
      const roleStore = useRoleStore()
      roleStore.setRole(roleName)
    } catch {
      // si /me falla, seguimos autenticados igualmente
    }
  }

  function logout() {
    clearToken()
  }

  return { token, isAuthenticated, login, logout }
})
