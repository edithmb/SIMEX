import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: () => import('@/views/DashboardView.vue'),
      meta: {
        title: 'Panel de Control',
        breadcrumbParent: 'Inicio',
      },
    },
    {
      path: '/seguimiento',
      name: 'seguimiento',
      component: () => import('@/views/SeguimientoView.vue'),
      meta: {
        title: 'Seguimiento e Incoterms',
        breadcrumbParent: 'Inicio / Operaciones',
      },
    },
    {
      path: '/solicitudes',
      name: 'solicitudes',
      component: () => import('@/views/SolicitudesView.vue'),
      meta: {
        title: 'Solicitudes de Clientes',
        breadcrumbParent: 'Inicio / Comercial',
      },
    },
    {
      path: '/presupuestos',
      name: 'presupuestos',
      component: () => import('@/views/PresupuestosView.vue'),
      meta: {
        title: 'Presupuestos',
        breadcrumbParent: 'Inicio / Comercial',
      },
    },
    {
      path: '/clientes',
      name: 'clientes',
      component: () => import('@/views/ClientesView.vue'),
      meta: {
        title: 'Gestión de Clientes',
        breadcrumbParent: 'Inicio / Gestión',
      },
    },
    {
      path: '/documentos',
      name: 'documentos',
      component: () => import('@/views/DocumentosView.vue'),
      meta: {
        title: 'Documentos',
        breadcrumbParent: 'Inicio / Gestión',
      },
    },
    {
      path: '/configuracion',
      name: 'configuracion',
      component: () => import('@/views/ConfiguracionView.vue'),
      meta: {
        title: 'Configuración',
        breadcrumbParent: 'Inicio / Sistema',
      },
    }
  ],
})

export default router
