<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useRoleStore } from '@/stores/role'
import { useAuthStore } from '@/stores/auth'
import SolicitudesStats from '@/components/solicitudes/SolicitudesStats.vue'
import SolicitudesFilters from '@/components/solicitudes/SolicitudesFilters.vue'
import SolicitudesTable from '@/components/solicitudes/SolicitudesTable.vue'
import CrearPresupuestoModal from '@/components/solicitudes/CrearPresupuestoModal.vue'
import CrearSolicitudModal from '@/components/solicitudes/CrearSolicitudModal.vue'

const roleStore = useRoleStore()
const auth = useAuthStore()
const router = useRouter()

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const headers = { Authorization: `Bearer ${auth.token}` }

const solicitudes = ref([])
const loading = ref(false)

// 1. Creamos las listas reactivas para los desplegables del modal
const clientesList = ref([])
const localizacionesList = ref([])

function mapSolicitud(item) {
  return {
    id: item.id,
    client_id: item.client_id,
    clientName: item.client?.company_name ?? '—',
    volume_m3: item.volume_m3,
    gross_weight_kg: item.gross_weight_kg,
    originName: item.origin?.name ?? '—',
    destinationName: item.destination?.name ?? '—',
    comments: item.comments,
    created_at: item.created_at ? new Date(item.created_at).toLocaleDateString('es-ES') : '—',
    hasOffer: (item.commercial_offers?.length ?? 0) > 0,
  }
}

async function fetchSolicitudes() {
  loading.value = true
  try {
    const endpoint = roleStore.isAdmin ? '/client-requests-admin' : '/client-requests-client'
    const res = await axios.get(LARAVEL + endpoint, { headers })
    solicitudes.value = res.data.map(mapSolicitud)
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al cargar solicitudes:', error)
  } finally {
    loading.value = false
  }
}

// 2. Función para cargar localizaciones y clientes
async function cargarDatos() {
  try {
    const peticionLocalizaciones = axios.get(LARAVEL + '/locations', { headers })

    let peticionClientes = Promise.resolve({ data: [] })
    if (roleStore.isAdmin) {
      peticionClientes = axios.get(LARAVEL + '/clients', { headers })
    }

    const [resLocalizaciones, resClientes] = await Promise.all([
      peticionLocalizaciones,
      peticionClientes,
    ])

    localizacionesList.value = resLocalizaciones.data
    clientesList.value = resClientes.data
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al cargar datos para los desplegables:', error)
  }
}

// 3. Ejecutamos las llamadas a la API cuando el componente se monta
onMounted(() => {
  fetchSolicitudes()
  cargarDatos()
})

const activeFilter = ref('Todos')
const searchQuery = ref('')

const filteredSolicitudes = computed(() => {
  let result = solicitudes.value

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

// Modal state — client/admin: solicitud modal
const showSolicitudModal = ref(false)

function openSolicitudModal() {
  showSolicitudModal.value = true
}

function closeSolicitudModal() {
  showSolicitudModal.value = false
}

async function handleSolicitudSubmit(data) {
  try {
    const endpoint = roleStore.isAdmin ? '/client-requests-admin' : '/client-requests-client'
    await axios.post(LARAVEL + endpoint, data, { headers })
    closeSolicitudModal()
    await fetchSolicitudes()
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al enviar la solicitud:', error)
  }
}
</script>

<template>
  <div class="solicitudes">
    <!-- Page Heading -->
    <div :class="['solicitudes-header', { 'solicitudes-header--with-btn': roleStore.isCliente || roleStore.isAdmin }]">
      <div class="solicitudes-header-text">
        <h2 class="solicitudes-header-title">Solicitudes de Clientes</h2>
        <p class="solicitudes-header-subtitle">Peticiones de transporte recibidas pendientes de cotización.</p>
      </div>
      <button v-if="roleStore.isCliente || roleStore.isAdmin" class="solicitudes-header-btn" @click="openSolicitudModal">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19" />
          <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Crear Solicitud
      </button>
    </div>

    <!-- Stats Row -->
    <SolicitudesStats :solicitudes="solicitudes" />

    <!-- Filters -->
    <SolicitudesFilters :active-filter="activeFilter" :search-query="searchQuery"
      @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />

    <!-- Table -->
    <SolicitudesTable :solicitudes="filteredSolicitudes" :role="roleStore.currentRole"
      @crear-presupuesto="openPresupuestoModal" />

    <!-- Modal: Crear Presupuesto (admin) -->
    <CrearPresupuestoModal :visible="showPresupuestoModal" :solicitud="selectedSolicitud"
      @close="closePresupuestoModal" @submit="handlePresupuestoSubmit" />

    <!-- Modal: Crear Solicitud (client/admin) -->
    <CrearSolicitudModal :visible="showSolicitudModal" :role="roleStore.currentRole"
      :clientes="clientesList" :localizaciones="localizacionesList"
      @close="closeSolicitudModal" @submit="handleSolicitudSubmit" />
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
