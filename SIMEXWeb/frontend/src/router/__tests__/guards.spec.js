import { createPinia, setActivePinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { defineComponent } from 'vue'

const Stub = defineComponent({ template: '<div />' })

function buildRouter() {
  const router = createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/login', name: 'login', component: Stub, meta: { public: true } },
      { path: '/', name: 'dashboard', component: Stub },
      { path: '/clientes', name: 'clientes', component: Stub },
    ],
  })

  router.beforeEach((to) => {
    const auth = useAuthStore()
    if (!to.meta.public && !auth.isAuthenticated) {
      return { name: 'login' }
    }
    if (to.name === 'login' && auth.isAuthenticated) {
      return { name: 'dashboard' }
    }
  })

  return router
}

describe('router guards', () => {
  let router

  beforeEach(() => {
    setActivePinia(createPinia())
    router = buildRouter()
  })

  it('redirects unauthenticated user to /login', async () => {
    await router.push('/')
    await router.isReady()
    expect(router.currentRoute.value.name).toBe('login')
  })

  it('allows unauthenticated user to access /login', async () => {
    await router.push('/login')
    await router.isReady()
    expect(router.currentRoute.value.name).toBe('login')
  })

  it('redirects authenticated user away from /login', async () => {
    localStorage.setItem('jwt_token', 'tok')
    setActivePinia(createPinia())
    router = buildRouter()

    await router.push('/login')
    await router.isReady()
    expect(router.currentRoute.value.name).toBe('dashboard')
  })

  it('allows authenticated user to access protected routes', async () => {
    localStorage.setItem('jwt_token', 'tok')
    setActivePinia(createPinia())
    router = buildRouter()

    await router.push('/clientes')
    await router.isReady()
    expect(router.currentRoute.value.name).toBe('clientes')
  })
})
