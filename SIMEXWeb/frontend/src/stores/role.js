import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useRoleStore = defineStore('role', () => {
  // Rol de vista: controlado por el selector del sidebar
  const currentRole = ref(localStorage.getItem('user_role') ?? 'admin')

  const isAdmin = computed(() => currentRole.value === 'admin')
  const isCliente = computed(() => currentRole.value === 'cliente')

  function setRole(role) {
    currentRole.value = role
    localStorage.setItem('user_role', role)
  }

  return { currentRole, isAdmin, isCliente, setRole }
})
