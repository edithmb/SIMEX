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
        breadcrumbParent: 'Inicio / Comercial',
      },
    },
    {
      path: '/operaciones',
      name: 'operaciones',
      component: () => import('@/views/OperacionesView.vue'),
      meta: {
        title: 'Operaciones',
        breadcrumbParent: 'Inicio / Operaciones',
      },
    },
    {
      path: '/productos',
      name: 'productos',
      component: () => import('@/views/ProductosView.vue'),
      meta: {
        title: 'Productos Importados',
        breadcrumbParent: 'Inicio / Operaciones',
      },
    },
    {
      path: '/documentos',
      name: 'documentos',
      component: () => import('@/views/DocumentosView.vue'),
      meta: {
        title: 'Documentos',
        breadcrumbParent: 'Inicio / Operaciones',
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
    },
    {
      path: '/usuarios',
      name: 'usuarios',
      component: () => import('@/views/UsuariosView.vue'),
      meta: {
        title: 'Gestión de Usuarios',
        breadcrumbParent: 'Inicio / Sistema',
      },
    },
  ],
})

export default router
