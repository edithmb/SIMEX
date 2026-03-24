<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useRoleStore } from '@/stores/role'

const route = useRoute()
const roleStore = useRoleStore()

const allMenuSections = [
  {
    label: 'PRINCIPAL',
    items: [
      { name: 'Panel de Control', icon: 'dashboard', route: '/', roles: ['admin', 'cliente'] },
      { name: 'Seguimiento', icon: 'tracking', route: '/seguimiento', roles: ['admin', 'cliente'] },
    ],
  },
  {
    label: 'COMERCIAL',
    items: [
      { name: 'Solicitudes', icon: 'solicitudes', route: '/solicitudes', roles: ['admin', 'cliente'] },
      { name: 'Presupuestos', icon: 'presupuestos', route: '/presupuestos', roles: ['admin', 'cliente'] },
    ],
  },
  {
    label: 'GESTIÓN',
    items: [
      { name: 'Clientes', icon: 'clientes', route: '/clientes', roles: ['admin'] },
      { name: 'Documentos', icon: 'documentos', route: '/documentos', roles: ['admin', 'cliente'] },
    ],
  },
  {
    label: 'SISTEMA',
    items: [
      { name: 'Configuración', icon: 'configuracion', route: '/configuracion', roles: ['admin'] },
      { name: 'Datos Maestros', icon: 'maestros', route: '/datos-maestros', roles: ['admin'] },
    ],
  },
]

const menuSections = computed(() => {
  return allMenuSections
    .map(section => ({
      ...section,
      items: section.items.filter(item => item.roles.includes(roleStore.currentRole))
    }))
    .filter(section => section.items.length > 0)
})

const isActive = (itemRoute) => {
  return route.path === itemRoute
}
</script>

<template>
  <aside class="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="12" cy="12" r="10" />
          <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z" />
        </svg>
      </div>
      <div class="sidebar-logo-text">
        <span class="sidebar-logo-title">SIMEX</span>
        <span class="sidebar-logo-subtitle">Comercio Internacional</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <div
        v-for="section in menuSections"
        :key="section.label"
        class="sidebar-section"
      >
        <span class="sidebar-section-label">{{ section.label }}</span>
        <RouterLink
          v-for="item in section.items"
          :key="item.route"
          :to="item.route"
          :class="['sidebar-item', { 'sidebar-item--active': isActive(item.route) }]"
        >
          <span class="sidebar-item-icon">
            <!-- Dashboard -->
            <svg v-if="item.icon === 'dashboard'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1" />
              <rect x="14" y="3" width="7" height="7" rx="1" />
              <rect x="3" y="14" width="7" height="7" rx="1" />
              <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>
            <!-- Tracking -->
            <svg v-else-if="item.icon === 'tracking'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
              <circle cx="12" cy="9" r="2.5" />
            </svg>
            <!-- Solicitudes -->
            <svg v-else-if="item.icon === 'solicitudes'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="16" y1="13" x2="8" y2="13" />
              <line x1="16" y1="17" x2="8" y2="17" />
              <polyline points="10 9 9 9 8 9" />
            </svg>
            <!-- Presupuestos -->
            <svg v-else-if="item.icon === 'presupuestos'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23" />
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
            <!-- Clientes -->
            <svg v-else-if="item.icon === 'clientes'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <!-- Documentos -->
            <svg v-else-if="item.icon === 'documentos'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
            </svg>
            <!-- Configuración -->
            <svg v-else-if="item.icon === 'configuracion'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3" />
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
            </svg>
            <!-- Usuarios -->
            <svg v-else-if="item.icon === 'usuarios'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <!-- Maestros -->
            <svg v-else-if="item.icon === 'maestros'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <ellipse cx="12" cy="5" rx="9" ry="3" />
              <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5" />
              <path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3" />
            </svg>
          </span>
          <span class="sidebar-item-label">{{ item.name }}</span>
        </RouterLink>
      </div>
    </nav>

    <!-- Role Switcher -->
    <div class="sidebar-role-switcher">
      <span class="sidebar-role-switcher-label">VISTA ACTIVA</span>
      <select
        class="sidebar-role-switcher-select"
        :value="roleStore.currentRole"
        @change="roleStore.setRole($event.target.value)"
      >
        <option value="admin">Administrador</option>
        <option value="cliente">Cliente</option>
      </select>
    </div>

    <!-- User Footer -->
    <div class="sidebar-user">
      <div class="sidebar-user-avatar">{{ roleStore.isAdmin ? 'MG' : 'JR' }}</div>
      <div class="sidebar-user-info">
        <span class="sidebar-user-name">{{ roleStore.isAdmin ? 'María García' : 'Javier Ruiz' }}</span>
        <span class="sidebar-user-role">{{ roleStore.isAdmin ? 'Administradora' : 'Cliente' }}</span>
      </div>
      <button class="sidebar-user-logout" title="Cerrar sesión">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
      </button>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  width: var(--sidebar-width);
  min-width: var(--sidebar-width);
  background: var(--sidebar-bg);
  display: flex;
  flex-direction: column;
  color: var(--sidebar-text);
  overflow-y: auto;
}

/* Logo */
.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 20px 24px;
}

.sidebar-logo-icon {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

.sidebar-logo-text {
  display: flex;
  flex-direction: column;
}

.sidebar-logo-title {
  font-size: 16px;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.2px;
}

.sidebar-logo-subtitle {
  font-size: 11px;
  color: var(--sidebar-section);
  margin-top: 1px;
}

/* Navigation */
.sidebar-nav {
  flex: 1;
  padding: 0 12px;
}

.sidebar-section {
  margin-bottom: 8px;
}

.sidebar-section-label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: var(--sidebar-section);
  text-transform: uppercase;
  letter-spacing: 1.2px;
  padding: 12px 12px 6px;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 12px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 500;
  color: var(--sidebar-text);
  cursor: pointer;
}

.sidebar-item:hover {
  background: rgba(255, 255, 255, 0.08);
  color: #ffffff;
}

.sidebar-item--active {
  background: var(--sidebar-active);
  color: #ffffff;
}

.sidebar-item--active:hover {
  background: var(--sidebar-active);
}

.sidebar-item-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.sidebar-item-label {
  white-space: nowrap;
}

/* Role Switcher */
.sidebar-role-switcher {
  padding: 12px 16px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-role-switcher-label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: var(--sidebar-section);
  text-transform: uppercase;
  letter-spacing: 1.2px;
  margin-bottom: 8px;
}

.sidebar-role-switcher-select {
  width: 100%;
  height: 36px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 8px;
  padding: 0 10px;
  font-size: 13px;
  font-family: var(--font-family);
  font-weight: 600;
  color: #ffffff;
  cursor: pointer;
  appearance: auto;
}

.sidebar-role-switcher-select option {
  background: var(--sidebar-bg);
  color: #ffffff;
}

/* User Footer */
.sidebar-user {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px 16px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: var(--sidebar-active);
  color: #fff;
  font-size: 13px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.sidebar-user-info {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.sidebar-user-name {
  font-size: 13px;
  font-weight: 600;
  color: #ffffff;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-user-role {
  font-size: 11px;
  color: var(--sidebar-section);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-user-logout {
  margin-left: auto;
  color: var(--sidebar-section);
  padding: 4px;
}

.sidebar-user-logout:hover {
  color: #ffffff;
}
</style>
