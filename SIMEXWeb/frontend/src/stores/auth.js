import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoleStore } from '@/stores/role'
import axios from 'axios'

const LARAVEL = import.meta.env.VITE_LARAVEL_API

/**
 * Store de autenticación: mantiene el JWT, el flag de admin y orquesta
 * login/logout contra el backend Laravel.
 *
 * Persiste en `localStorage`:
 *  - `jwt_token`         → token actual (se inyecta vía interceptor axios).
 *  - `backend_is_admin`  → `"true"|"false"`, derivado de `role.name !== 'cliente'`.
 *
 * El rol del backend sólo se actualiza en login o en la rehidratación
 * inicial (`initBackendRole`) — nunca desde la UI — para no falsificar
 * privilegios en el cliente.
 *
 * @returns Store expuesto: `{ token, isAuthenticated, backendIsAdmin,
 *   initBackendRole, login, logout }`.
 */
export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('jwt_token') ?? null)
  const isAuthenticated = computed(() => !!token.value)

  // Rol real del backend — solo se actualiza en login o al inicializar, nunca desde la UI
  const backendIsAdmin = ref(localStorage.getItem('backend_is_admin') === 'true')

  /**
   * Persiste un nuevo JWT tanto en el state como en `localStorage`.
   *
   * @param {string} newToken Token firmado por el backend.
   */
  function setToken(newToken) {
    token.value = newToken
    localStorage.setItem('jwt_token', newToken)
  }

  /**
   * Elimina todos los rastros de sesión del cliente (state + localStorage).
   * Incluye claves heredadas (`user_role`, `backend_role`) para limpiar
   * sesiones creadas con versiones anteriores del store.
   */
  function clearToken() {
    token.value = null
    backendIsAdmin.value = false
    localStorage.removeItem('jwt_token')
    localStorage.removeItem('user_role')
    localStorage.removeItem('backend_role')
    localStorage.removeItem('backend_is_admin')
  }

  /**
   * Rehidrata el rol del backend al cargar la app.
   *
   * Caso de uso: el usuario ya tenía sesión antes de que existiera el
   * flag `backend_is_admin`. Si hay token pero todavía no tenemos el flag
   * en `localStorage`, llamamos a `/me` para resolverlo. Ante error
   * (token expirado, red caída…) limpiamos la sesión para evitar un
   * estado inconsistente.
   *
   * @returns {Promise<void>}
   */
  async function initBackendRole() {
    if (!token.value) return
    if (localStorage.getItem('backend_is_admin') !== null) return
    try {
      const meRes = await axios.get(LARAVEL + '/me')
      const roleName = meRes.data.role?.name ?? 'cliente'
      const isAdmin = roleName !== 'cliente'
      backendIsAdmin.value = isAdmin
      localStorage.setItem('backend_is_admin', String(isAdmin))
      const roleStore = useRoleStore()
      roleStore.setRole(roleName)
    } catch {
      // Si /me falla (token expirado, red caída), limpiar sesión
      clearToken()
    }
  }

  /**
   * Autentica al usuario contra `/login` y, tras guardar el token,
   * consulta `/me` para hidratar el rol.
   *
   * Si `/me` falla tras un login correcto mantenemos la sesión (token ya
   * guardado) pero sin rol — la app seguirá funcionando como cliente.
   *
   * @param {string} email    Email del usuario.
   * @param {string} password Contraseña en claro.
   * @returns {Promise<void>}
   * @throws {Error} Con el mensaje del backend si las credenciales fallan.
   */
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
      const meRes = await axios.get(LARAVEL + '/me')
      const roleName = meRes.data.role?.name ?? 'cliente'
      const isAdmin = roleName !== 'cliente'

      backendIsAdmin.value = isAdmin
      localStorage.setItem('backend_is_admin', String(isAdmin))

      const roleStore = useRoleStore()
      roleStore.setRole(roleName)
    } catch {
      // si /me falla, seguimos autenticados igualmente
    }
  }

  /**
   * Cierra sesión: avisa al backend (best-effort) para que invalide el JWT
   * y, pase lo que pase, limpia el estado local. Si el backend rechaza la
   * petición (token ya inválido, red caída…) seguimos adelante con
   * `clearToken()` para evitar que el usuario quede "medio logueado".
   *
   * @returns {Promise<void>}
   */
  async function logout() {
    if (token.value) {
      try {
        await axios.post(LARAVEL + '/logout')
      } catch {
        // si el backend rechaza (token ya inválido, red caída…), seguimos limpiando en cliente
      }
    }
    clearToken()
  }

  return { token, isAuthenticated, backendIsAdmin, initBackendRole, login, logout }
})
