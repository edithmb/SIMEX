import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useRoleStore = defineStore('role', () => {
  const currentRole = ref('admin')
  const isAdmin = computed(() => currentRole.value === 'admin')
  const isCliente = computed(() => currentRole.value === 'cliente')

  function setRole(role) {
    currentRole.value = role
  }

  return { currentRole, isAdmin, isCliente, setRole }
})
