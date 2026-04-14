<script setup>
import { ref, shallowRef, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import TrackingFilters from '@/components/seguimiento/TrackingFilters.vue'
import ShipmentList from '@/components/seguimiento/ShipmentList.vue'
import ShipmentDetail from '@/components/seguimiento/ShipmentDetail.vue'
import { useRoleStore } from '@/stores/role'
import { useAuthStore } from '@/stores/auth'

const roleStore = useRoleStore()
const auth = useAuthStore()
const router = useRouter()
const LARAVEL = import.meta.env.VITE_LARAVEL_API
const NET = import.meta.env.VITE_NET_API

const STEP_COLORS = {
  pending:   { statusColor: '#e5e7eb', statusTextColor: '#4b5563', progressColor: '#9ca3af' },
  active:    { statusColor: '#dbeafe', statusTextColor: '#1a6fb5', progressColor: '#1a6fb5' },
  completed: { statusColor: '#d1fae5', statusTextColor: '#047857', progressColor: '#6b8e23' },
}

const shipments = ref([])
const loading = ref(false)
const loadError = ref(null)
const selectedId = shallowRef(null)

function formatPrice(value) {
  const n = Number(value)
  if (!Number.isFinite(n)) return '—'
  return n.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 2 })
}

function formatShortDate(value) {
  if (!value) return '—'
  const d = new Date(value)
  if (Number.isNaN(d.getTime())) return '—'
  return d.toLocaleDateString('es-ES')
}

function mapToShipment(op) {
  const offer = op.commercial_offer || {}
  const req = offer.client_request || {}
  const incotermCode = offer.incoterm?.incoterm_type?.code?.trim() || '—'

  const incotermSteps = Array.isArray(op.incoterm_steps) ? op.incoterm_steps : []
  const stepNames = incotermSteps.map((s) => s.name).filter(Boolean)

  let currentIdx = stepNames.indexOf(op.status)
  if (currentIdx === -1) currentIdx = 0

  const timeline = stepNames.map((name, i) => ({
    name,
    state: i < currentIdx ? 'completed' : (i === currentIdx ? 'active' : 'pending'),
  }))

  const total = stepNames.length
  const progress = total > 0 ? Math.round(((currentIdx + 1) / total) * 100) : 0
  const statusLabel = stepNames[currentIdx] || op.status || '—'
  const colors = total > 0 && currentIdx === total - 1 ? STEP_COLORS.completed : STEP_COLORS.active

  return {
    id: String(op.id),
    ref: op.reference,
    client: op.client?.company_name || '—',
    routeFrom: offer.origin_port?.name || '—',
    routeTo:   offer.destination_port?.name || '—',
    incoterm: incotermCode,
    incotermColor: '#1a6fb5',
    responsability: req.responsability || null,
    status: op.status,
    statusLabel,
    statusColor: colors.statusColor,
    statusTextColor: colors.statusTextColor,
    progress,
    progressColor: colors.progressColor,
    etd: op.etd,
    eta: op.eta,
    atd: op.atd,
    ata: op.ata,
    steps: stepNames,
    timeline,
    data: [
      { label: 'Ref. Presupuesto', value: offer.reference || '—' },
      { label: 'Responsabilidad',  value: req.responsability || '—' },
      { label: 'Peso Bruto',       value: req.gross_weight_kg ? `${req.gross_weight_kg} kg` : '—' },
      { label: 'Volumen',          value: req.volume_m3 ? `${req.volume_m3} m³` : '—' },
      { label: 'Tipo Contenedor',  value: offer.container_type?.type_name || '—' },
      { label: 'Puerto Origen',    value: offer.origin_port?.name || '—' },
      { label: 'Puerto Destino',   value: offer.destination_port?.name || '—' },
      { label: 'Precio',           value: formatPrice(offer.price) },
      { label: 'Válido Hasta',     value: formatShortDate(offer.valid_until) },
    ],
  }
}

onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get(LARAVEL + '/logistics-operations', {
      headers: { Authorization: `Bearer ${auth.token}` },
    })
    const rows = Array.isArray(res.data) ? res.data : (res.data.data || [])
    shipments.value = rows.map(mapToShipment)
    if (shipments.value.length) selectedId.value = shipments.value[0].id
  } catch (e) {
    if (e.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al cargar operaciones logísticas:', e)
    loadError.value = 'No se pudieron cargar las operaciones'
  } finally {
    loading.value = false
  }
})

const selectedShipment = computed(() => {
  if (!shipments.value.length) return null
  return shipments.value.find((s) => s.id === selectedId.value) || shipments.value[0]
})

function handleSelect(id) {
  selectedId.value = id
}

async function updateShipmentStatus(id, newStatus) {
  const shipment = shipments.value.find((s) => s.id === id)
  if (!shipment) return

  const steps = shipment.steps || []
  const currentIdx = steps.indexOf(newStatus)
  if (currentIdx === -1) return

  shipment.status = newStatus
  shipment.statusLabel = newStatus

  const total = steps.length
  shipment.progress = total > 0 ? Math.round(((currentIdx + 1) / total) * 100) : 0

  const colors = currentIdx === total - 1 ? STEP_COLORS.completed : STEP_COLORS.active
  shipment.statusColor = colors.statusColor
  shipment.statusTextColor = colors.statusTextColor
  shipment.progressColor = colors.progressColor

  shipment.timeline.forEach((step, i) => {
    step.state = i < currentIdx ? 'completed' : (i === currentIdx ? 'active' : 'pending')
  })

  // Persiste en el backend
  try {
    await axios.put(`${NET}/LogisticsOperations/${id}/status`, { status: newStatus })
  } catch (e) {
    console.error('Error al actualizar estado en el backend:', e)
  }
}
</script>

<template>
  <div class="seguimiento">
    <!-- Filters -->
    <TrackingFilters />

    <!-- Content: List + Detail -->
    <div class="seguimiento-content">
      <div class="seguimiento-list-col">
        <ShipmentList
          :shipments="shipments"
          :selected-id="selectedId"
          @select="handleSelect"
        />
      </div>
      <div class="seguimiento-detail-col">
        <ShipmentDetail
          v-if="selectedShipment"
          :shipment="selectedShipment"
          :role="roleStore.currentRole"
          @update-status="updateShipmentStatus"
        />
        <div v-else-if="loading" class="seguimiento-placeholder">Cargando operaciones…</div>
        <div v-else-if="loadError" class="seguimiento-placeholder error">{{ loadError }}</div>
        <div v-else class="seguimiento-placeholder">No hay operaciones logísticas.</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.seguimiento {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.seguimiento-content {
  display: grid;
  grid-template-columns: 340px 1fr;
  gap: 20px;
  align-items: start;
}

.seguimiento-list-col {
  min-width: 0;
}

.seguimiento-detail-col {
  min-width: 0;
}

.seguimiento-placeholder {
  padding: 24px;
  background: #fff;
  border-radius: 8px;
  color: #6b7280;
  text-align: center;
}

.seguimiento-placeholder.error {
  color: #b91c1c;
}

@media (max-width: 1100px) {
  .seguimiento-content {
    grid-template-columns: 1fr;
  }
}
</style>
