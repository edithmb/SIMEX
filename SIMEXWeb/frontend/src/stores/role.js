import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

/**
 * Store de rol de vista activo en la UI.
 *
 * Distinto del rol del backend (`useAuthStore.backendIsAdmin`): aquí se
 * permite al usuario administrativo cambiar entre vistas (`admin` /
 * `cliente`) desde el selector del sidebar sin cambiar privilegios
 * reales. Persiste en `localStorage` bajo la clave `user_role`.
 *
 * @returns Store expuesto: `{ currentRole, isAdmin, isCliente, setRole }`.
 */
export const useRoleStore = defineStore('role', () => {
  // Rol de vista: controlado por el selector del sidebar
  const currentRole = ref(localStorage.getItem('user_role') ?? null)

  const isAdmin = computed(() => currentRole.value === 'admin')
  const isCliente = computed(() => currentRole.value === 'cliente')

  /**
   * Actualiza el rol de vista activo y lo persiste en `localStorage`.
   *
   * @param {string} role Ej. `'admin'` o `'cliente'`.
   */
  function setRole(role) {
    currentRole.value = role
    localStorage.setItem('user_role', role)
  }

  return { currentRole, isAdmin, isCliente, setRole }
})
