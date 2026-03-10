<script setup>
import { ref, computed } from 'vue'
import SolicitudesStats from '@/components/solicitudes/SolicitudesStats.vue'
import SolicitudesFilters from '@/components/solicitudes/SolicitudesFilters.vue'
import SolicitudesTable from '@/components/solicitudes/SolicitudesTable.vue'
import CrearPresupuestoModal from '@/components/solicitudes/CrearPresupuestoModal.vue'
import ClienteSolicitudesTable from '@/components/solicitudes/ClienteSolicitudesTable.vue'
import NuevaSolicitudModal from '@/components/solicitudes/NuevaSolicitudModal.vue'

const solicitudes = [
  {
    ref: 'SOL-2024-120',
    client: 'Textiles Mediterráneo S.A.',
    goods: '2 Contenedores 40HC - Ropa',
    routeFrom: 'Shanghai',
    routeTo: 'Valencia',
    transport: 'ship',
    date: '15/03/2024',
    status: 'Pendiente',
    statusColor: '#fef3c7',
    statusTextColor: '#b45309',
  },
  {
    ref: 'L-202-4-1',
    client: 'Importaciones García S.L.',
    goods: '5 Pallets - Electrónica',
    routeFrom: 'Miami',
    routeTo: 'Madrid',
    transport: 'plane',
    date: '20/02/2024',
    status: 'Presupuestada',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
  },
  {
    ref: 'SOL-2024-122',
    client: 'Alimentación Ibérica S.L.',
    goods: 'Camión Frigorífico Completo',
    routeFrom: 'Rotterdam',
    routeTo: 'Barcelona',
    transport: 'truck',
    date: '25/02/2024',
    status: 'Pendiente',
    statusColor: '#fef3c7',
    statusTextColor: '#b45309',
  },
  {
    ref: 'SOL-2024-123',
    client: 'Electrónica Levante S.A.',
    goods: '1 Contenedor 20DV - Componentes',
    routeFrom: 'Shenzhen',
    routeTo: 'Bilbao',
    transport: 'ship',
    date: '10/03/2024',
    status: 'Cancelada',
    statusColor: '#e5e7eb',
    statusTextColor: '#6b7280',
  },
  {
    ref: 'SOL-2024-124',
    client: 'Maquinaria Industrial Norte',
    goods: 'Maquinaria Pesada (Oversized)',
    routeFrom: 'Frankfurt',
    routeTo: 'Zaragoza',
    transport: 'truck',
    date: '18/03/2024',
    status: 'Presupuestada',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
  },
]

// Toggle vista
const vistaCliente = ref(false)

// Datos vista cliente
const solicitudesCliente = ref([
  {
    ref: 'SOL-2024-130',
    tipoMercancia: 'Electronica de consumo',
    origen: 'Shanghai',
    destino: 'Valencia',
    transport: 'ship',
    fechaDeseada: '20/04/2024',
    status: 'Enviada',
  },
  {
    ref: 'SOL-2024-131',
    tipoMercancia: 'Textiles',
    origen: 'Mumbai',
    destino: 'Barcelona',
    transport: 'ship',
    fechaDeseada: '15/05/2024',
    status: 'Aceptada',
  },
  {
    ref: 'SOL-2024-132',
    tipoMercancia: 'Maquinaria industrial',
    origen: 'Frankfurt',
    destino: 'Madrid',
    transport: 'truck',
    fechaDeseada: '01/04/2024',
    status: 'Pendiente',
  },
  {
    ref: 'SOL-2024-133',
    tipoMercancia: 'Alimentos perecederos',
    origen: 'Rotterdam',
    destino: 'Bilbao',
    transport: 'truck',
    fechaDeseada: '10/03/2024',
    status: 'Rechazada',
  },
])

// Modal nueva solicitud
const showNuevaSolicitud = ref(false)

function openNuevaSolicitud() {
  showNuevaSolicitud.value = true
}

function closeNuevaSolicitud() {
  showNuevaSolicitud.value = false
}

function handleNuevaSolicitud(data) {
  const newRef = `SOL-${new Date().getFullYear()}-${100 + solicitudesCliente.value.length + 1}`
  solicitudesCliente.value.unshift({
    ref: newRef,
    tipoMercancia: data.tipoMercancia || '—',
    origen: data.origen || '—',
    destino: data.destino || '—',
    transport: data.tipoTransporte,
    fechaDeseada: data.fechaDeseada || '—',
    status: 'Enviada',
  })
  closeNuevaSolicitud()
}

const activeFilter = ref('Todos')
const searchQuery = ref('')

const filteredSolicitudes = computed(() => {
  let result = solicitudes

  if (activeFilter.value !== 'Todos') {
    result = result.filter((s) => s.status === activeFilter.value)
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(
      (s) =>
        s.ref.toLowerCase().includes(q) ||
        s.client.toLowerCase().includes(q),
    )
  }

  return result
})

// Modal state
const showModal = ref(false)
const selectedSolicitud = ref(null)

function openModal(solicitud) {
  selectedSolicitud.value = solicitud
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  selectedSolicitud.value = null
}

function handleSubmit(data) {
  // TODO: connect to backend
  console.log('Presupuesto generado:', data)
  closeModal()
}
</script>

<template>
  <div class="solicitudes">
    <!-- Page Heading -->
    <div class="solicitudes-header">
      <div class="solicitudes-header-top">
        <div>
          <h2 class="solicitudes-header-title">
            {{ vistaCliente ? 'Mis Solicitudes' : 'Solicitudes de Clientes' }}
          </h2>
          <p class="solicitudes-header-subtitle">
            {{ vistaCliente ? 'Textiles Mediterraneo S.A.' : 'Peticiones de transporte recibidas pendientes de cotizacion.' }}
          </p>
        </div>
        <button class="solicitudes-toggle-btn" @click="vistaCliente = !vistaCliente">
          👁 {{ vistaCliente ? 'Vista Admin' : 'Vista Cliente' }}
        </button>
      </div>
    </div>

    <!-- Vista Admin -->
    <template v-if="!vistaCliente">
      <SolicitudesStats />
      <SolicitudesFilters :active-filter="activeFilter" :search-query="searchQuery"
        @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />
      <SolicitudesTable :solicitudes="filteredSolicitudes" @crear-presupuesto="openModal" />
      <CrearPresupuestoModal :visible="showModal" :solicitud="selectedSolicitud" @close="closeModal"
        @submit="handleSubmit" />
    </template>

    <!-- Vista Cliente -->
    <template v-else>
      <div class="solicitudes-cliente-header">
        <span></span>
        <button class="solicitudes-nueva-btn" @click="openNuevaSolicitud">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          Nueva Solicitud
        </button>
      </div>
      <ClienteSolicitudesTable :solicitudes="solicitudesCliente" />
      <NuevaSolicitudModal :visible="showNuevaSolicitud" @close="closeNuevaSolicitud"
        @submit="handleNuevaSolicitud" />
    </template>
  </div>
</template>

<style scoped>
.solicitudes {
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.solicitudes-header {
  display: flex;
  flex-direction: column;
}

.solicitudes-header-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
}

.solicitudes-header-title {
  font-size: 22px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 4px;
}

.solicitudes-header-subtitle {
  font-size: 13.5px;
  color: var(--text-secondary);
}

.solicitudes-toggle-btn {
  flex-shrink: 0;
  padding: 8px 18px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-secondary);
  border: 1.5px solid var(--border-color);
  border-radius: 20px;
  background: #ffffff;
  transition: all 0.15s ease;
  white-space: nowrap;
}

.solicitudes-toggle-btn:hover {
  border-color: var(--accent-blue);
  color: var(--accent-blue);
  background: #f0f7ff;
}

.solicitudes-cliente-header {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.solicitudes-nueva-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 20px;
  border-radius: 8px;
  background: var(--sidebar-bg);
  color: #ffffff;
  font-size: 13px;
  font-weight: 600;
  transition: background 0.15s ease;
  white-space: nowrap;
}

.solicitudes-nueva-btn:hover {
  background: #0d2440;
}
</style>
