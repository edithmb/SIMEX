<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const breadcrumbParent = computed(() => route.meta.breadcrumbParent || 'Inicio')
const pageTitle = computed(() => route.meta.title || 'Panel de Control')
</script>

<template>
  <header class="topbar">
    <div class="topbar-left">
      <div class="topbar-breadcrumb">
        <span class="topbar-breadcrumb-parent">{{ breadcrumbParent }}</span>
        <span class="topbar-breadcrumb-separator">/</span>
        <h1 class="topbar-title">{{ pageTitle }}</h1>
      </div>
    </div>

    <div class="topbar-right">
      <div class="topbar-search">
        <svg class="topbar-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input
          type="text"
          class="topbar-search-input"
          placeholder="Buscar..."
          id="global-search"
        />
      </div>

      <button class="topbar-notification" id="btn-notifications" title="Notificaciones">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
        <span class="topbar-notification-badge">4</span>
      </button>

      <div class="topbar-avatar" id="user-avatar">
        <span class="topbar-avatar-initials">MG</span>
        <svg class="topbar-avatar-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9" />
        </svg>
      </div>
    </div>
  </header>
</template>

<style scoped>
.topbar {
  height: var(--topbar-height);
  min-height: var(--topbar-height);
  background: var(--topbar-bg);
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
}

.topbar-left {
  display: flex;
  align-items: center;
}

.topbar-breadcrumb {
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.topbar-breadcrumb-parent {
  font-size: 12px;
  color: var(--text-muted);
}

.topbar-breadcrumb-separator {
  font-size: 11px;
  color: var(--text-muted);
}

.topbar-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

.topbar-search {
  position: relative;
  display: flex;
  align-items: center;
}

.topbar-search-icon {
  position: absolute;
  left: 12px;
  color: var(--text-muted);
  pointer-events: none;
}

.topbar-search-input {
  width: 200px;
  height: 36px;
  background: var(--page-bg);
  border-radius: 8px;
  padding: 0 12px 0 36px;
  font-size: 13px;
  color: var(--text-primary);
}

.topbar-search-input::placeholder {
  color: var(--text-muted);
}

.topbar-search-input:focus {
  width: 260px;
  box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.topbar-notification {
  position: relative;
  color: var(--text-secondary);
  padding: 6px;
  border-radius: 8px;
}

.topbar-notification:hover {
  background: var(--page-bg);
  color: var(--text-primary);
}

.topbar-notification-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  min-width: 16px;
  height: 16px;
  background: var(--accent-red);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  padding: 0 4px;
}

.topbar-avatar {
  display: flex;
  align-items: center;
  gap: 4px;
  cursor: pointer;
  padding: 4px;
  border-radius: 8px;
}

.topbar-avatar:hover {
  background: var(--page-bg);
}

.topbar-avatar-initials {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: var(--sidebar-bg);
  color: #fff;
  font-size: 13px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.topbar-avatar-chevron {
  color: var(--text-muted);
}
</style>
