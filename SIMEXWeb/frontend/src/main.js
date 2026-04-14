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

// Interceptor REQUEST: añade el token en cada petición
axios.interceptors.request.use((config) => {
  const auth = useAuthStore()
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

// Interceptor RESPONSE: si el backend devuelve 401, hacer logout
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
