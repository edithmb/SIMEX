import { mount, flushPromises } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import { useAuthStore } from '@/stores/auth'
import { defineComponent } from 'vue'

const Stub = defineComponent({ template: '<div />' })

function buildRouter() {
  return createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/login', name: 'login', component: LoginView },
      { path: '/', name: 'dashboard', component: Stub },
    ],
  })
}

describe('LoginView', () => {
  let router
  let pinia

  beforeEach(async () => {
    pinia = createPinia()
    setActivePinia(pinia)
    router = buildRouter()
    router.push('/login')
    await router.isReady()
  })

  function mountLogin() {
    return mount(LoginView, {
      global: {
        plugins: [pinia, router],
      },
    })
  }

  it('renders email and password inputs and submit button', () => {
    const wrapper = mountLogin()
    expect(wrapper.find('input[type="email"]').exists()).toBe(true)
    expect(wrapper.find('input[type="password"]').exists()).toBe(true)
    expect(wrapper.find('button[type="submit"]').exists()).toBe(true)
  })

  it('renders SIMEX brand', () => {
    const wrapper = mountLogin()
    expect(wrapper.text()).toContain('SIMEX')
  })

  it('navigates to dashboard on successful login', async () => {
    const wrapper = mountLogin()
    const auth = useAuthStore()
    auth.login = vi.fn().mockResolvedValue(undefined)

    await wrapper.find('input[type="email"]').setValue('user@test.com')
    await wrapper.find('input[type="password"]').setValue('password')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(auth.login).toHaveBeenCalledWith('user@test.com', 'password')
    expect(router.currentRoute.value.path).toBe('/')
  })

  it('shows error message on login failure', async () => {
    const wrapper = mountLogin()
    const auth = useAuthStore()
    auth.login = vi.fn().mockRejectedValue(new Error('Credenciales incorrectas'))

    await wrapper.find('input[type="email"]').setValue('bad@test.com')
    await wrapper.find('input[type="password"]').setValue('wrong')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.find('.login-error').text()).toBe('Credenciales incorrectas')
  })

  it('disables button while loading', async () => {
    const wrapper = mountLogin()
    const auth = useAuthStore()

    let resolveLogin
    auth.login = vi.fn().mockImplementation(
      () => new Promise((r) => { resolveLogin = r }),
    )

    await wrapper.find('input[type="email"]').setValue('u@t.com')
    await wrapper.find('input[type="password"]').setValue('p')
    await wrapper.find('form').trigger('submit')
    await wrapper.vm.$nextTick()

    expect(wrapper.find('button[type="submit"]').attributes('disabled')).toBeDefined()
    expect(wrapper.find('button[type="submit"]').text()).toBe('Entrando\u2026')

    resolveLogin()
    await flushPromises()
  })

  it('re-enables button after failed login', async () => {
    const wrapper = mountLogin()
    const auth = useAuthStore()
    auth.login = vi.fn().mockRejectedValue(new Error('fail'))

    await wrapper.find('input[type="email"]').setValue('u@t.com')
    await wrapper.find('input[type="password"]').setValue('p')
    await wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(wrapper.find('button[type="submit"]').attributes('disabled')).toBeUndefined()
    expect(wrapper.find('button[type="submit"]').text()).toBe('Entrar')
  })
})
