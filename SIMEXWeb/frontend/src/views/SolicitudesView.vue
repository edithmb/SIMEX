<script setup>
import { ref, computed } from 'vue'
import SolicitudesStats from '@/components/solicitudes/SolicitudesStats.vue'
import SolicitudesFilters from '@/components/solicitudes/SolicitudesFilters.vue'
import SolicitudesTable from '@/components/solicitudes/SolicitudesTable.vue'
import CrearPresupuestoModal from '@/components/solicitudes/CrearPresupuestoModal.vue'

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
      <h2 class="solicitudes-header-title">Solicitudes de Clientes</h2>
      <p class="solicitudes-header-subtitle">Peticiones de transporte recibidas pendientes de cotización.</p>
    </div>

    <!-- Stats Row -->
    <SolicitudesStats />

    <!-- Filters -->
    <SolicitudesFilters :active-filter="activeFilter" :search-query="searchQuery"
      @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />

    <!-- Table -->
    <SolicitudesTable :solicitudes="filteredSolicitudes" @crear-presupuesto="openModal" />

    <!-- Modal -->
    <CrearPresupuestoModal :visible="showModal" :solicitud="selectedSolicitud" @close="closeModal"
      @submit="handleSubmit" />
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
  gap: 4px;
}

.solicitudes-header-title {
  font-size: 22px;
  font-weight: 700;
  color: var(--text-primary);
}

.solicitudes-header-subtitle {
  font-size: 13.5px;
  color: var(--text-secondary);
}
</style>
