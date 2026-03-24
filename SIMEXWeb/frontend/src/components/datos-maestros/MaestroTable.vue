<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  maestro: { type: Object, required: true },
})
defineEmits(['add', 'edit', 'delete'])

const search = ref('')

const filtered = computed(() => {
  const q = search.value.toLowerCase()
  if (!q) return props.maestro.data
  return props.maestro.data.filter(row =>
    props.maestro.columns.some(col =>
      String(row[col.key] ?? '').toLowerCase().includes(q)
    )
  )
})
</script>

<template>
  <div class="maestro-table-panel">
    <!-- Header -->
    <div class="panel-header">
      <h2 class="panel-title">{{ maestro.label }}</h2>
      <button class="btn-add" @click="$emit('add')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19" />
          <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Añadir
      </button>
    </div>

    <!-- Search -->
    <div class="panel-search">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
      <input
        v-model="search"
        type="text"
        :placeholder="'Buscar en ' + maestro.label + '...'"
        class="search-input"
      />
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th class="th-id">ID</th>
            <th v-for="col in maestro.columns" :key="col.key">{{ col.label }}</th>
            <th class="th-actions">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filtered.length === 0">
            <td :colspan="maestro.columns.length + 2" class="empty-row">Sin resultados</td>
          </tr>
          <tr v-for="row in filtered" :key="row.id" class="data-row">
            <td class="td-id">{{ row.id }}</td>
            <td v-for="col in maestro.columns" :key="col.key">{{ row[col.key] }}</td>
            <td class="td-actions">
              <button class="action-btn action-btn--edit" @click="$emit('edit', row)" title="Editar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
              </button>
              <button class="action-btn action-btn--delete" @click="$emit('delete', row)" title="Eliminar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6" />
                  <path d="M19 6l-1 14H6L5 6" />
                  <path d="M10 11v6M14 11v6" />
                  <path d="M9 6V4h6v2" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Count -->
    <p class="panel-count">{{ filtered.length }} registro{{ filtered.length !== 1 ? 's' : '' }}</p>
  </div>
</template>

<style scoped>
.maestro-table-panel {
  flex: 1;
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  min-width: 0;
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.panel-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
}

.btn-add {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: #1a6fb5;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  font-family: var(--font-family);
  transition: background 0.15s;
}

.btn-add:hover {
  background: #1558a0;
}

.panel-search {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 14px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  background: var(--page-bg);
  height: 38px;
}

.search-input {
  flex: 1;
  border: none;
  background: none;
  font-size: 13px;
  color: var(--text-primary);
  font-family: var(--font-family);
  outline: none;
}

.table-wrap {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 10px 14px;
  font-size: 11.5px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 1px solid var(--border-color);
}

.th-id {
  width: 56px;
}

.th-actions {
  width: 88px;
}

.data-row td {
  padding: 11px 14px;
  font-size: 13px;
  color: var(--text-primary);
  border-bottom: 1px solid var(--border-color);
}

.data-row:last-child td {
  border-bottom: none;
}

.data-row:hover td {
  background: var(--page-bg);
}

.td-id {
  color: var(--text-muted);
  font-size: 12px;
}

.td-actions {
  display: flex;
  gap: 6px;
  align-items: center;
}

.action-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  background: var(--card-bg);
  cursor: pointer;
  transition: all 0.12s;
}

.action-btn--edit {
  color: #1a6fb5;
}

.action-btn--edit:hover {
  background: #eff6ff;
  border-color: #1a6fb5;
}

.action-btn--delete {
  color: #dc2626;
}

.action-btn--delete:hover {
  background: #fef2f2;
  border-color: #dc2626;
}

.empty-row {
  text-align: center;
  color: var(--text-muted);
  padding: 32px;
  font-size: 13px;
}

.panel-count {
  font-size: 12px;
  color: var(--text-muted);
  margin-top: 4px;
}
</style>
