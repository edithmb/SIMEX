<script setup>
const operations = [
  {
    id: 1,
    ref: 'OP-2024-1-58',
    client: 'Importaciones García S.L.',
    routeFrom: 'Shanghai',
    routeTo: 'Barcelona',
    incoterm: 'CIF',
    incotermColor: '#1a6fb5',
    transport: 'truck',
    status: 'En Tránsito',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
  },
  {
    id: 2,
    ref: 'OP-2024-1-57',
    client: 'Textiles Mediterráneo S.A.',
    routeFrom: 'Rotterdam',
    routeTo: 'Valencia',
    incoterm: 'FOB',
    incotermColor: '#1a6fb5',
    transport: 'truck',
    status: 'En Aduana',
    statusColor: '#fef3c7',
    statusTextColor: '#b45309',
  },
  {
    id: 3,
    ref: 'OP-2024-1-56',
    client: 'Alimentación Ibérica S.L.',
    routeFrom: 'Miami',
    routeTo: 'Madrid',
    incoterm: 'DDP',
    incotermColor: '#1a6fb5',
    transport: 'plane',
    status: 'Entregado',
    statusColor: '#d1fae5',
    statusTextColor: '#047857',
  },
  {
    id: 4,
    ref: 'OP-2024-1-55',
    client: 'Electrónica Levante S.A.',
    routeFrom: 'Shenzhen',
    routeTo: 'Bilbao',
    incoterm: 'EXW',
    incotermColor: '#1a6fb5',
    transport: 'truck',
    status: 'Pendiente',
    statusColor: '#e5e7eb',
    statusTextColor: '#4b5563',
  },
  {
    id: 5,
    ref: 'OP-2024-1-54',
    client: 'Maquinaria Industrial Norte',
    routeFrom: 'Frankfurt',
    routeTo: 'Zaragoza',
    incoterm: 'DAP',
    incotermColor: '#1a6fb5',
    transport: 'ship',
    status: 'Incidencia',
    statusColor: '#fee2e2',
    statusTextColor: '#b91c1c',
  },
]
</script>

<template>
  <div class="ops-card">
    <h2 class="ops-card-title">Operaciones Recientes</h2>
    <div class="ops-card-table-wrap">
      <table class="ops-table">
        <thead>
          <tr>
            <th class="ops-table-th">Referencia</th>
            <th class="ops-table-th">Cliente</th>
            <th class="ops-table-th">Ruta</th>
            <th class="ops-table-th">Incoterm</th>
            <th class="ops-table-th">Transporte</th>
            <th class="ops-table-th">Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="op in operations" :key="op.id" class="ops-table-row">
            <td class="ops-table-td">
              <span class="ops-ref">{{ op.ref }}</span>
            </td>
            <td class="ops-table-td">{{ op.client }}</td>
            <td class="ops-table-td">
              <span class="ops-route">{{ op.routeFrom }} → {{ op.routeTo }}</span>
            </td>
            <td class="ops-table-td">
              <span class="ops-incoterm" :style="{ background: op.incotermColor }">
                {{ op.incoterm }}
              </span>
            </td>
            <td class="ops-table-td">
              <span class="ops-transport-icon">
                <!-- Truck -->
                <svg v-if="op.transport === 'truck'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="1" y="3" width="15" height="13" rx="1" />
                  <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                  <circle cx="5.5" cy="18.5" r="2.5" />
                  <circle cx="18.5" cy="18.5" r="2.5" />
                </svg>
                <!-- Plane -->
                <svg v-else-if="op.transport === 'plane'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
                </svg>
                <!-- Ship -->
                <svg v-else-if="op.transport === 'ship'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                  <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
                  <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
                  <line x1="12" y1="1" x2="12" y2="5" />
                </svg>
              </span>
            </td>
            <td class="ops-table-td">
              <span
                class="ops-status"
                :style="{ background: op.statusColor, color: op.statusTextColor }"
              >
                {{ op.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.ops-card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 22px 24px;
}

.ops-card-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 16px;
}

.ops-card-table-wrap {
  overflow-x: auto;
}

.ops-table {
  width: 100%;
  border-collapse: collapse;
}

.ops-table-th {
  text-align: left;
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: none;
  padding: 8px 10px;
  border-bottom: 1px solid var(--border-color);
  white-space: nowrap;
}

.ops-table-row:hover {
  background: rgba(0, 0, 0, 0.015);
}

.ops-table-td {
  padding: 12px 10px;
  font-size: 13px;
  color: var(--text-primary);
  border-bottom: 1px solid var(--border-color);
  vertical-align: middle;
}

.ops-ref {
  font-weight: 600;
  color: var(--accent-blue);
  font-size: 12.5px;
}

.ops-route {
  font-size: 12.5px;
  white-space: nowrap;
}

.ops-incoterm {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  color: #ffffff;
}

.ops-transport-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
}

.ops-status {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 11.5px;
  font-weight: 600;
  white-space: nowrap;
}
</style>
