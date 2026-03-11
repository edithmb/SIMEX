<script setup>
import { ref, reactive, computed } from 'vue'
import { useRoleStore } from '@/stores/role'
import SolicitudesStats from '@/components/solicitudes/SolicitudesStats.vue'
import SolicitudesFilters from '@/components/solicitudes/SolicitudesFilters.vue'
import SolicitudesTable from '@/components/solicitudes/SolicitudesTable.vue'
import CrearPresupuestoModal from '@/components/solicitudes/CrearPresupuestoModal.vue'
import CrearSolicitudModal from '@/components/solicitudes/CrearSolicitudModal.vue'

const roleStore = useRoleStore()

const solicitudes = reactive([
  { id: 1, client_id: 1, clientName: 'Textiles Mediterráneo S.A.', volume_m3: 45, gross_weight_kg: 12450, originName: 'Almacén Shanghai, Pudong District, China', destinationName: 'Nave Industrial, Pol. Ind. Fuente del Jarro, Valencia', comments: '2 contenedores 40HC con ropa de temporada', created_at: '15/03/2024', hasOffer: false },
  { id: 2, client_id: 2, clientName: 'Importaciones García S.L.', volume_m3: 6.5, gross_weight_kg: 1850, originName: 'Almacén Miami, NW 25th St, Florida, EEUU', destinationName: 'Centro Logístico, Coslada, Madrid', comments: '5 pallets de electrónica', created_at: '20/02/2024', hasOffer: true },
  { id: 3, client_id: 3, clientName: 'Alimentación Ibérica S.L.', volume_m3: 32, gross_weight_kg: 8200, originName: 'Fábrica Rotterdam, Europoort, Países Bajos', destinationName: 'Almacén Frigorífico, Zona Franca, Barcelona', comments: 'Camión frigorífico completo', created_at: '25/02/2024', hasOffer: false },
  { id: 4, client_id: 4, clientName: 'Electrónica Levante S.A.', volume_m3: 12, gross_weight_kg: 3200, originName: 'Fábrica Shenzhen, Guangdong, China', destinationName: 'Almacén Bilbao, Pol. Ind. Arriaga', comments: '1 contenedor 20DV con componentes electrónicos', created_at: '10/03/2024', hasOffer: true },
  { id: 5, client_id: 5, clientName: 'Maquinaria Industrial Norte', volume_m3: 55, gross_weight_kg: 18500, originName: 'Fábrica Frankfurt, Hessen, Alemania', destinationName: 'Polígono Industrial, Zaragoza', comments: 'Maquinaria pesada oversized', created_at: '18/03/2024', hasOffer: false },
])

const activeFilter = ref('Todos')
const searchQuery = ref('')

const filteredSolicitudes = computed(() => {
  let result = solicitudes

  if (activeFilter.value !== 'Todos') {
    result = result.filter((s) => {
      const status = s.hasOffer ? 'Presupuestada' : 'Enviada'
      return status === activeFilter.value
    })
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    result = result.filter(
      (s) =>
        String(s.id).includes(q) ||
        s.clientName.toLowerCase().includes(q),
    )
  }

  return result
})

// Modal state — admin: presupuesto modal
const showPresupuestoModal = ref(false)
const selectedSolicitud = ref(null)

function openPresupuestoModal(solicitud) {
  selectedSolicitud.value = solicitud
  showPresupuestoModal.value = true
}

function closePresupuestoModal() {
  showPresupuestoModal.value = false
  selectedSolicitud.value = null
}

function handlePresupuestoSubmit(data) {
  console.log('Presupuesto generado:', data)
  closePresupuestoModal()
}

// Modal state — client: solicitud modal
const showSolicitudModal = ref(false)

function openSolicitudModal() {
  showSolicitudModal.value = true
}

function closeSolicitudModal() {
  showSolicitudModal.value = false
}

function handleSolicitudSubmit(data) {
  console.log('Solicitud creada:', data)
  closeSolicitudModal()
}
</script>

<template>
  <div class="solicitudes">
    <!-- Page Heading -->
    <div :class="['solicitudes-header', { 'solicitudes-header--with-btn': roleStore.isCliente }]">
      <div class="solicitudes-header-text">
        <h2 class="solicitudes-header-title">Solicitudes de Clientes</h2>
        <p class="solicitudes-header-subtitle">Peticiones de transporte recibidas pendientes de cotización.</p>
      </div>
      <button v-if="roleStore.isCliente" class="solicitudes-header-btn" @click="openSolicitudModal">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19" />
          <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Crear Solicitud
      </button>
    </div>

    <!-- Stats Row -->
    <SolicitudesStats />

    <!-- Filters -->
    <SolicitudesFilters :active-filter="activeFilter" :search-query="searchQuery"
      @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />

    <!-- Table -->
    <SolicitudesTable :solicitudes="filteredSolicitudes" :role="roleStore.currentRole"
      @crear-presupuesto="openPresupuestoModal" />

    <!-- Modal: Crear Presupuesto (admin) -->
    <CrearPresupuestoModal :visible="showPresupuestoModal" :solicitud="selectedSolicitud"
      @close="closePresupuestoModal" @submit="handlePresupuestoSubmit" />

    <!-- Modal: Crear Solicitud (client) -->
    <CrearSolicitudModal :visible="showSolicitudModal" @close="closeSolicitudModal"
      @submit="handleSolicitudSubmit" />
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

.solicitudes-header--with-btn {
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
}

.solicitudes-header-text {
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

.solicitudes-header-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 22px;
  background: var(--sidebar-bg);
  color: #ffffff;
  border-radius: 10px;
  font-size: 13.5px;
  font-weight: 600;
  transition: background 0.15s ease;
  white-space: nowrap;
}

.solicitudes-header-btn:hover {
  background: #0d2440;
}
</style>
