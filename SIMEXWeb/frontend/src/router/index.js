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
  ],
})

export default router
