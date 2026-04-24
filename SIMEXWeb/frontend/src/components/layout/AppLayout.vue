<script setup>
/**
 * @component AppLayout
 * @description Layout principal de la aplicación: sidebar fijo a la
 * izquierda, topbar arriba y un `<main>` con scroll propio que contiene
 * la ruta actual (pasada vía `<slot />`).
 *
 * Oculta el layout automáticamente en la ruta /login al no envolverse
 * con este componente en esa página (ver `LoginView.vue`).
 */
import AppSidebar from '@/components/layout/AppSidebar.vue'
import AppTopbar from '@/components/layout/AppTopbar.vue'
import ChatWidget from '@/components/chat/ChatWidget.vue'
</script>

<template>
  <div class="app-layout">
    <AppSidebar />
    <div class="app-layout-main">
      <AppTopbar />
      <main class="app-layout-content">
        <slot />
      </main>
    </div>
    <ChatWidget />
  </div>
</template>

<style scoped>
.app-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.app-layout-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  min-width: 0;
}

.app-layout-content {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
  background: var(--page-bg);
}
</style>
