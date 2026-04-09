import { setActivePinia, createPinia } from 'pinia'
import { useRoleStore } from '@/stores/role'

describe('role store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('defaults currentRole to admin', () => {
    const store = useRoleStore()
    expect(store.currentRole).toBe('admin')
    expect(store.isAdmin).toBe(true)
    expect(store.isCliente).toBe(false)
  })

  it('reads currentRole from localStorage', () => {
    localStorage.setItem('user_role', 'cliente')
    setActivePinia(createPinia())
    const store = useRoleStore()
    expect(store.currentRole).toBe('cliente')
    expect(store.isCliente).toBe(true)
    expect(store.isAdmin).toBe(false)
  })

  it('setRole persists to localStorage', () => {
    const store = useRoleStore()
    store.setRole('cliente')
    expect(store.currentRole).toBe('cliente')
    expect(localStorage.getItem('user_role')).toBe('cliente')
  })

  it('isAdmin and isCliente react to setRole', () => {
    const store = useRoleStore()
    expect(store.isAdmin).toBe(true)
    store.setRole('cliente')
    expect(store.isAdmin).toBe(false)
    expect(store.isCliente).toBe(true)
  })
})
