<script setup>
const props = defineProps({
  activeFilter: { type: String, default: 'Todos' },
  searchQuery: { type: String, default: '' },
})

const emit = defineEmits(['update:activeFilter', 'update:searchQuery'])

const filters = ['Todos', 'Enviada', 'Presupuestada']
</script>

<template>
  <div class="solicitudes-filters">
    <div class="solicitudes-filters-search">
      <svg class="solicitudes-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
      <input type="text" class="solicitudes-filters-search-input" placeholder="Buscar por referencia o cliente..."
        :value="searchQuery" @input="emit('update:searchQuery', $event.target.value)" id="solicitudes-search" />
    </div>

    <div class="solicitudes-filters-buttons">
      <button v-for="f in filters" :key="f"
        :class="['solicitudes-filters-btn', { 'solicitudes-filters-btn--active': activeFilter === f }]"
        @click="emit('update:activeFilter', f)">
        {{ f }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.solicitudes-filters {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 16px 22px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.solicitudes-filters-search {
  position: relative;
  display: flex;
  align-items: center;
  flex: 1;
}

.solicitudes-filters-search-icon {
  position: absolute;
  left: 14px;
  color: var(--text-muted);
  pointer-events: none;
}

.solicitudes-filters-search-input {
  width: 100%;
  height: 40px;
  background: var(--page-bg);
  border-radius: 20px;
  padding: 0 16px 0 40px;
  font-size: 13px;
  color: var(--text-primary);
}

.solicitudes-filters-search-input::placeholder {
  color: var(--text-muted);
}

.solicitudes-filters-search-input:focus {
  box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.solicitudes-filters-buttons {
  display: flex;
  gap: 8px;
  flex-shrink: 0;
}

.solicitudes-filters-btn {
  padding: 8px 18px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-secondary);
  background: var(--page-bg);
  transition: all 0.15s ease;
}

.solicitudes-filters-btn:hover {
  color: var(--text-primary);
  background: #e2e5ea;
}

.solicitudes-filters-btn--active {
  background: var(--sidebar-bg);
  color: #ffffff;
}

.solicitudes-filters-btn--active:hover {
  background: var(--sidebar-bg);
  color: #ffffff;
}

@media (max-width: 768px) {
  .solicitudes-filters {
    flex-direction: column;
    align-items: stretch;
  }

  .solicitudes-filters-buttons {
    flex-wrap: wrap;
  }
}
</style>
