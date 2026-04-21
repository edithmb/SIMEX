<script setup>
/**
 * @component TrackingFilters
 * @description Fila de filtros sobre el panel de seguimiento: búsqueda
 * libre, selector de Incoterm y selector de estado.
 *
 * Actualmente sólo declara los emits — los selectores todavía no están
 * enlazados a un estado reactivo (demo). Al integrar enlazar con
 * `v-model` usando estos eventos.
 *
 * @emits update:search
 * @emits update:status
 * @emits update:incoterm
 */
const emit = defineEmits(['update:search', 'update:status', 'update:incoterm'])
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
