<script setup>
import { shallowRef } from 'vue'

const emit = defineEmits(['update:search', 'update:transport', 'update:status', 'update:incoterm'])

const activeTransport = shallowRef('todos')

const transportFilters = [
  { key: 'todos', label: 'Todos', icon: 'filter' },
  { key: 'maritimo', label: 'Marítimo', icon: 'ship' },
  { key: 'aereo', label: 'Aéreo', icon: 'plane' },
  { key: 'terrestre', label: 'Terrestre', icon: 'truck' },
]

function selectTransport(key) {
  activeTransport.value = key
}
</script>

<template>
  <div class="tracking-filters">
    <div class="tracking-filters-row">
      <!-- Search -->
      <div class="tracking-filters-search">
        <svg class="tracking-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <input
          type="text"
          class="tracking-filters-search-input"
          placeholder="Buscar por referencia, cliente..."
          id="tracking-search"
        />
      </div>

      <!-- Transport type buttons -->
      <div class="tracking-filters-transport">
        <button
          v-for="t in transportFilters"
          :key="t.key"
          :class="['tracking-filters-btn', { 'tracking-filters-btn--active': activeTransport === t.key }]"
          @click="selectTransport(t.key)"
        >
          <!-- Filter icon -->
          <svg v-if="t.icon === 'filter'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
          </svg>
          <!-- Ship -->
          <svg v-else-if="t.icon === 'ship'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
            <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
            <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
            <line x1="12" y1="1" x2="12" y2="5" />
          </svg>
          <!-- Plane -->
          <svg v-else-if="t.icon === 'plane'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
          </svg>
          <!-- Truck -->
          <svg v-else-if="t.icon === 'truck'" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13" rx="1" />
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
            <circle cx="5.5" cy="18.5" r="2.5" />
            <circle cx="18.5" cy="18.5" r="2.5" />
          </svg>
          <span>{{ t.label }}</span>
        </button>
      </div>

      <!-- Incoterm dropdown -->
      <div class="tracking-filters-dropdown">
        <select class="tracking-filters-select" id="filter-incoterm">
          <option>Todos los Incoterms</option>
          <option>CIF</option>
          <option>FOB</option>
          <option>DDP</option>
          <option>EXW</option>
          <option>DAP</option>
        </select>
      </div>
    </div>

    <div class="tracking-filters-row">
      <!-- Status dropdown -->
      <div class="tracking-filters-dropdown">
        <select class="tracking-filters-select" id="filter-status">
          <option>Todos los Estados</option>
          <option>En Tránsito</option>
          <option>En Aduana</option>
          <option>Entregado</option>
          <option>Pendiente</option>
          <option>Incidencia</option>
        </select>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tracking-filters {
  background: var(--card-bg);
  border: 2px solid var(--accent-blue);
  border-radius: var(--card-radius);
  padding: 16px 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.tracking-filters-row {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

/* Search */
.tracking-filters-search {
  position: relative;
  flex: 1;
  min-width: 200px;
}

.tracking-filters-search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-muted);
  pointer-events: none;
}

.tracking-filters-search-input {
  width: 100%;
  height: 38px;
  background: var(--page-bg);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 0 12px 0 36px;
  font-size: 13px;
  color: var(--text-primary);
}

.tracking-filters-search-input::placeholder {
  color: var(--text-muted);
}

/* Transport buttons */
.tracking-filters-transport {
  display: flex;
  gap: 0;
}

.tracking-filters-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-secondary);
  background: var(--page-bg);
  border: 1px solid var(--border-color);
  cursor: pointer;
}

.tracking-filters-btn:first-child {
  border-radius: 8px 0 0 8px;
}

.tracking-filters-btn:last-child {
  border-radius: 0 8px 8px 0;
}

.tracking-filters-btn + .tracking-filters-btn {
  margin-left: -1px;
}

.tracking-filters-btn--active {
  background: var(--sidebar-bg);
  color: #ffffff;
  border-color: var(--sidebar-bg);
  z-index: 1;
}

/* Dropdowns */
.tracking-filters-dropdown {
  position: relative;
}

.tracking-filters-select {
  height: 38px;
  padding: 0 32px 0 12px;
  font-size: 13px;
  font-weight: 500;
  font-family: var(--font-family);
  color: var(--text-primary);
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  cursor: pointer;
  appearance: auto;
}
</style>
