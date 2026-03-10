<script setup>
defineProps({
  solicitudes: { type: Array, required: true },
})
</script>

<template>
  <div class="cliente-table-wrapper">
    <table class="cliente-table">
      <thead>
        <tr>
          <th>Referencia</th>
          <th>Tipo de Mercancía</th>
          <th>Ruta</th>
          <th>Transp.</th>
          <th>Fecha Deseada</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="sol in solicitudes" :key="sol.ref">
          <!-- Referencia -->
          <td>
            <span class="cliente-table-ref">{{ sol.ref }}</span>
          </td>

          <!-- Tipo de Mercancía -->
          <td>
            <span class="cliente-table-goods">{{ sol.tipoMercancia }}</span>
          </td>

          <!-- Ruta -->
          <td>
            <span class="cliente-table-route">{{ sol.origen }} → {{ sol.destino }}</span>
          </td>

          <!-- Transporte icon -->
          <td>
            <span class="cliente-table-transport">
              <svg v-if="sol.transport === 'ship'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
                <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
                <path d="M12 10V2" />
              </svg>
              <svg v-else-if="sol.transport === 'plane'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
              </svg>
              <svg v-else-if="sol.transport === 'truck'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13" rx="1" />
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                <circle cx="5.5" cy="18.5" r="2.5" />
                <circle cx="18.5" cy="18.5" r="2.5" />
              </svg>
            </span>
          </td>

          <!-- Fecha Deseada -->
          <td>
            <span class="cliente-table-date">{{ sol.fechaDeseada }}</span>
          </td>

          <!-- Estado -->
          <td>
            <span class="cliente-table-status" :class="`cliente-status-${sol.status.toLowerCase()}`">
              {{ sol.status }}
            </span>
          </td>
        </tr>
        <tr v-if="solicitudes.length === 0">
          <td colspan="6" class="cliente-table-empty">
            No tienes solicitudes registradas todavía.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.cliente-table-wrapper {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  overflow: hidden;
}

.cliente-table {
  width: 100%;
  border-collapse: collapse;
}

.cliente-table thead th {
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

.cliente-table tbody tr {
  transition: background 0.12s ease;
}

.cliente-table tbody tr:hover {
  background: #f8f9fb;
}

.cliente-table tbody td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  font-size: 13.5px;
  vertical-align: middle;
}

.cliente-table tbody tr:last-child td {
  border-bottom: none;
}

.cliente-table-ref {
  font-size: 12px;
  font-weight: 600;
  color: var(--accent-blue);
  word-break: break-all;
}

.cliente-table-goods {
  color: var(--text-secondary);
  font-size: 13px;
}

.cliente-table-route {
  color: var(--text-secondary);
  font-size: 13px;
  white-space: nowrap;
}

.cliente-table-transport {
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
}

.cliente-table-date {
  color: var(--text-primary);
  font-size: 13px;
  white-space: nowrap;
}

.cliente-table-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.cliente-status-enviada {
  background: #dbeafe;
  color: #1a6fb5;
}

.cliente-status-aceptada {
  background: #dcfce7;
  color: #16a34a;
}

.cliente-status-pendiente {
  background: #fef3c7;
  color: #b45309;
}

.cliente-status-rechazada {
  background: #fee2e2;
  color: #dc2626;
}

.cliente-table-empty {
  text-align: center;
  padding: 40px 16px !important;
  color: var(--text-muted);
  font-size: 14px;
}
</style>
