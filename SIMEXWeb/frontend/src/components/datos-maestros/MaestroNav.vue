<script setup>
defineProps({
  groups: { type: Array, required: true },
  activeKey: { type: String, required: true },
})
defineEmits(['select'])
</script>

<template>
  <div class="maestro-nav">
    <div v-for="group in groups" :key="group.label" class="nav-group">
      <span class="nav-group-label">{{ group.label }}</span>
      <button
        v-for="item in group.items"
        :key="item.key"
        :class="['nav-item', { 'nav-item--active': activeKey === item.key }]"
        @click="$emit('select', item.key)"
      >
        {{ item.label }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.maestro-nav {
  width: 210px;
  min-width: 210px;
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 16px 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-self: start;
}

.nav-group {
  margin-bottom: 4px;
}

.nav-group-label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 8px 10px 4px;
}

.nav-item {
  display: block;
  width: 100%;
  text-align: left;
  padding: 7px 12px;
  border-radius: 7px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-secondary);
  cursor: pointer;
  background: none;
  border: none;
  font-family: var(--font-family);
  transition: all 0.12s;
}

.nav-item:hover {
  background: var(--page-bg);
  color: var(--text-primary);
}

.nav-item--active {
  background: #eff6ff;
  color: #1a6fb5;
  font-weight: 600;
}
</style>
