import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Si hay sesión activa pero aún no se ha guardado el rol de backend
// (usuarios que estaban logueados antes de añadir esta lógica), lo resolvemos aquí
import { useAuthStore } from '@/stores/auth'
const auth = useAuthStore()
auth.initBackendRole()

app.mount('#app')
