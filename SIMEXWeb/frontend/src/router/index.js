import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useRoleStore } from '@/stores/role'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { public: true },
    },
    {
      path: '/',
      name: 'dashboard',
      component: () => import('@/views/DashboardView.vue'),
      meta: { title: 'Panel de Control', breadcrumbParent: 'Inicio' },
    },
    {
      path: '/seguimiento',
      name: 'seguimiento',
      component: () => import('@/views/SeguimientoView.vue'),
      meta: { title: 'Seguimiento e Incoterms', breadcrumbParent: 'Inicio / Operaciones' },
    },
    {
      path: '/solicitudes',
      name: 'solicitudes',
      component: () => import('@/views/SolicitudesView.vue'),
      meta: { title: 'Solicitudes de Clientes', breadcrumbParent: 'Inicio / Comercial' },
    },
    {
      path: '/presupuestos',
      name: 'presupuestos',
      component: () => import('@/views/PresupuestosView.vue'),
      meta: { title: 'Presupuestos', breadcrumbParent: 'Inicio / Comercial' },
    },
    {
      path: '/clientes',
      name: 'clientes',
      component: () => import('@/views/ClientesView.vue'),
      meta: { title: 'Gestión de Clientes', breadcrumbParent: 'Inicio / Gestión', roles: ['admin'] },
    },
    {
      path: '/documentos',
      name: 'documentos',
      component: () => import('@/views/DocumentosView.vue'),
      meta: { title: 'Documentos', breadcrumbParent: 'Inicio / Gestión' },
    },
    {
      path: '/configuracion',
      name: 'configuracion',
      component: () => import('@/views/ConfiguracionView.vue'),
      meta: { title: 'Configuración', breadcrumbParent: 'Inicio / Sistema', roles: ['admin'] },
    },
    {
      path: '/datos-maestros',
      name: 'datos-maestros',
      component: () => import('@/views/DatosMaestrosView.vue'),
      meta: { title: 'Datos Maestros', breadcrumbParent: 'Inicio / Sistema', roles: ['admin'] },
    },
  ],
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  const role = useRoleStore()

  if (!to.meta.public && !auth.isAuthenticated) {
    return { name: 'login' }
  }
  if (to.name === 'login' && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }
  // Verificación de rol
  if (to.meta.roles && !to.meta.roles.includes(role.currentRole)) {
    return { name: 'dashboard' }
  }
})

export default router
