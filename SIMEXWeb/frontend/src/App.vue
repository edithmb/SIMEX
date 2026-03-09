<script setup>
import AppLayout from '@/components/layout/AppLayout.vue'
</script>

<template>
  <AppLayout>
    <RouterView />
  </AppLayout>
</template>

<style>
/* ═══════════════════════════════════════════
   GLOBAL STYLES — LogiTrack Pro.
   ═══════════════════════════════════════════ */

:root {
  /* — Sidebar — */
  --sidebar-bg: #0f2a4a;
  --sidebar-width: 240px;
  --sidebar-active: #1a6fb5;
  --sidebar-text: #c0cfe0;
  --sidebar-section: #7a8fa6;

  /* — Topbar — */
  --topbar-bg: #ffffff;
  --topbar-height: 64px;

  /* — Page — */
  --page-bg: #f0f2f5;
  --card-bg: #ffffff;

  /* — Typography — */
  --text-primary: #1a2332;
  --text-secondary: #6b7280;
  --text-muted: #9ca3af;

  /* — Accent / Status — */
  --accent-blue: #1a6fb5;
  --accent-green: #10b981;
  --accent-red: #ef4444;
  --accent-orange: #f59e0b;

  /* — Borders & Shadows — */
  --border-color: #e5e7eb;
  --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  --card-radius: 12px;

  /* — Font — */
  --font-family: 'Source Sans 3', 'Segoe UI', sans-serif;
}

*,
*::before,
*::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html,
body {
  height: 100%;
  overflow: hidden;
}

body {
  font-family: var(--font-family);
  font-size: 14px;
  color: var(--text-primary);
  background: var(--page-bg);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

#app {
  height: 100%;
}

a {
  color: inherit;
  text-decoration: none;
}

ul, ol {
  list-style: none;
}

button {
  font-family: inherit;
  cursor: pointer;
  border: none;
  background: none;
}

input {
  font-family: inherit;
  border: none;
  outline: none;
}

table {
  border-collapse: collapse;
  width: 100%;
}
</style>
