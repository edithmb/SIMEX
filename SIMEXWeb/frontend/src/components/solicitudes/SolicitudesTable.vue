<script setup>
const props = defineProps({
  solicitudes: { type: Array, required: true },
  role: { type: String, default: 'admin' },
})

const emit = defineEmits(['crear-presupuesto'])
</script>

<template>
  <div class="solicitudes-table-wrapper">
    <table class="solicitudes-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Volumen (m³)</th>
          <th>Peso (kg)</th>
          <th>Origen</th>
          <th>Destino</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th v-if="role === 'admin'">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="sol in solicitudes" :key="sol.id">
          <!-- ID -->
          <td>
            <span class="solicitudes-table-ref">{{ sol.id }}</span>
          </td>

          <!-- Cliente -->
          <td>
            <span class="solicitudes-table-client">{{ sol.clientName }}</span>
          </td>

          <!-- Volumen -->
          <td>
            <span class="solicitudes-table-volume">{{ sol.volume_m3 }}</span>
          </td>

          <!-- Peso -->
          <td>
            <span class="solicitudes-table-weight">{{ sol.gross_weight_kg.toLocaleString() }}</span>
          </td>

          <!-- Origen -->
          <td>
            <span class="solicitudes-table-origin">{{ sol.originName }}</span>
          </td>

          <!-- Destino -->
          <td>
            <span class="solicitudes-table-destination">{{ sol.destinationName }}</span>
          </td>

          <!-- Fecha -->
          <td>
            <span class="solicitudes-table-date">{{ sol.created_at }}</span>
          </td>

          <!-- Estado -->
          <td>
            <span
              class="solicitudes-table-status"
              :style="{
                background: sol.hasOffer ? '#dbeafe' : '#fef3c7',
                color: sol.hasOffer ? '#1a6fb5' : '#b45309',
              }"
            >
              {{ sol.hasOffer ? 'Presupuestada' : 'Enviada' }}
            </span>
          </td>

          <!-- Acciones (admin only) -->
          <td v-if="role === 'admin'">
            <button v-if="!sol.hasOffer" class="solicitudes-table-action-btn"
              @click="emit('crear-presupuesto', sol)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
              </svg>
              Crear Presupuesto
            </button>
            <span v-else class="solicitudes-table-action-sent">
              Presupuesto enviado
            </span>
          </td>
        </tr>
        <tr v-if="solicitudes.length === 0">
          <td :colspan="role === 'admin' ? 9 : 8" class="solicitudes-table-empty">
            No se encontraron solicitudes con los filtros aplicados.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.solicitudes-table-wrapper {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  overflow: hidden;
}

.solicitudes-table {
  width: 100%;
  border-collapse: collapse;
}

.solicitudes-table thead th {
  text-align: left;
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  white-space: nowrap;
}

.solicitudes-table tbody tr {
  transition: background 0.12s ease;
}

.solicitudes-table tbody tr:hover {
  background: #f8f9fb;
}

.solicitudes-table tbody td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  font-size: 13.5px;
  vertical-align: middle;
}

.solicitudes-table tbody tr:last-child td {
  border-bottom: none;
}

/* ID */
.solicitudes-table-ref {
  font-size: 12px;
  font-weight: 600;
  color: var(--accent-blue);
  line-height: 1.5;
}

/* Client */
.solicitudes-table-client {
  font-weight: 600;
  color: var(--text-primary);
}

/* Volume */
.solicitudes-table-volume {
  color: var(--text-secondary);
  font-size: 13px;
}

/* Weight */
.solicitudes-table-weight {
  color: var(--text-secondary);
  font-size: 13px;
}

/* Origin */
.solicitudes-table-origin {
  color: var(--text-secondary);
  font-size: 13px;
}

/* Destination */
.solicitudes-table-destination {
  color: var(--text-secondary);
  font-size: 13px;
}

/* Date */
.solicitudes-table-date {
  color: var(--text-primary);
  font-size: 13px;
  white-space: nowrap;
}

/* Status Badge */
.solicitudes-table-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

/* Action Button */
.solicitudes-table-action-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 8px;
  background: var(--sidebar-bg);
  color: #ffffff;
  font-size: 12.5px;
  font-weight: 600;
  transition: background 0.15s ease;
  white-space: nowrap;
}

.solicitudes-table-action-btn:hover {
  background: #0d2440;
}

/* Sent label */
.solicitudes-table-action-sent {
  font-size: 12.5px;
  color: var(--text-muted);
  font-weight: 500;
  white-space: nowrap;
}

/* Empty state */
.solicitudes-table-empty {
  text-align: center;
  padding: 40px 16px !important;
  color: var(--text-muted);
  font-size: 14px;
}
</style>
