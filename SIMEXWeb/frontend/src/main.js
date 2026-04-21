/**
 * @file Punto de entrada de la aplicación Vue 3.
 *
 * Monta la app, instala Pinia y Vue Router y registra dos interceptores
 * globales de axios:
 *  - Request: inyecta el JWT desde el store `auth` en cada petición.
 *  - Response: si el backend responde 401 cierra sesión y redirige a /login.
 *
 * Finalmente llama a `initBackendRole()` para rehidratar el rol del
 * usuario en sesiones que existían antes de introducir esa lógica.
 */

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'

import App from './App.vue'
import router from './router'
import { useAuthStore } from '@/stores/auth'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

/**
 * Interceptor de REQUEST: añade la cabecera `Authorization: Bearer <token>`
 * a cada petición axios cuando hay token almacenado en el store.
 *
 * @param {import('axios').InternalAxiosRequestConfig} config
 * @returns {import('axios').InternalAxiosRequestConfig}
 */
axios.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

/**
 * Interceptor de RESPONSE: si el backend responde 401 cerramos sesión
 * localmente y enviamos al usuario a la pantalla de login. El rechazo se
 * repropaga para que el código llamante pueda manejar el error si lo desea.
 *
 * @param {import('axios').AxiosResponse} res
 * @returns {import('axios').AxiosResponse}
 */
axios.interceptors.response.use(
  (res) => res,
  async (err) => {
    if (err.response?.status === 401) {
      const auth = useAuthStore()
      await auth.logout()
      router.push({ name: 'login' })
    }
    return Promise.reject(err)
  }
)

// Si hay sesión activa pero aún no se ha guardado el rol de backend
// (usuarios que estaban logueados antes de añadir esta lógica), lo resolvemos aquí
const auth = useAuthStore()
auth.initBackendRole()

app.mount('#app')
