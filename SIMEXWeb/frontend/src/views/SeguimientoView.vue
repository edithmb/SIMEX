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

const statusOrder = ['embalaje', 'carga', 'transporte', 'aduana_exp', 'manip_origen', 'flete', 'manip_destino', 'aduana_imp', 'transporte_destino', 'descarga']

const statusMap = {
  embalaje:           { progress: 10,  progressColor: '#9ca3af', statusLabel: 'Embalaje y Verificación', statusColor: '#e5e7eb', statusTextColor: '#4b5563' },
  carga:              { progress: 20,  progressColor: '#10b981', statusLabel: 'Carga',                    statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  transporte:         { progress: 30,  progressColor: '#10b981', statusLabel: 'Transporte',               statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  aduana_exp:         { progress: 40,  progressColor: '#f59e0b', statusLabel: 'Aduana de Exportación',   statusColor: '#fef3c7', statusTextColor: '#b45309' },
  manip_origen:       { progress: 50,  progressColor: '#10b981', statusLabel: 'Manipulación Origen',      statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  flete:              { progress: 60,  progressColor: '#10b981', statusLabel: 'Flete',                    statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  manip_destino:      { progress: 70,  progressColor: '#10b981', statusLabel: 'Manipulación Destino',     statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  aduana_imp:         { progress: 80,  progressColor: '#f59e0b', statusLabel: 'Aduana de Importación',   statusColor: '#fef3c7', statusTextColor: '#b45309' },
  transporte_destino: { progress: 90,  progressColor: '#10b981', statusLabel: 'Transporte a Destino',     statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
  descarga:           { progress: 100, progressColor: '#6b8e23', statusLabel: 'Descarga',                 statusColor: '#d1fae5', statusTextColor: '#047857' },
}

const stepNames = ['Embalaje y Verificación', 'Carga', 'Transporte', 'Aduana de Exportación', 'Manipulación Origen', 'Flete', 'Manipulación Destino', 'Aduana de Importación', 'Transporte a Destino', 'Descarga']

const shipments = ref([])
const loading = ref(false)
const loadError = ref(null)
const selectedId = shallowRef(null)

function mapToShipment(op) {
  const offer = op.commercialOffer || {}
  const req = offer.clientRequest || {}
  const incotermCode = offer.incoterm?.incotermType?.code || '—'

  const statusKey = statusMap[op.status] ? op.status : 'embalaje'
  const cfg = statusMap[statusKey]

  const currentIdx = statusOrder.indexOf(statusKey)
  const timeline = stepNames.map((name, i) => ({
    name,
    location: null,
    date: null,
    state: i < currentIdx ? 'completed' : (i === currentIdx ? 'active' : 'pending'),
  }))

  return {
    id: String(op.id),
    ref: op.reference,
    client: op.client?.company_name || '—',
    routeFrom: offer.originPort?.name || req.origin?.city?.name || req.origin?.name || '—',
    routeTo:   offer.destinationPort?.name || req.destination?.city?.name || req.destination?.name || '—',
    transport: 'ship',
    transportLabel: 'marítimo',
    incoterm: incotermCode,
    incotermColor: '#1a6fb5',
    status: statusKey,
    statusLabel: cfg.statusLabel,
    statusColor: cfg.statusColor,
    statusTextColor: cfg.statusTextColor,
    progress: cfg.progress,
    progressColor: cfg.progressColor,
    etd: op.etd,
    eta: op.eta,
    atd: op.atd,
    ata: op.ata,
    timeline,
    data: [
      { label: 'Peso Bruto',      value: req.gross_weight_kg ? `${req.gross_weight_kg} kg` : '—' },
      { label: 'Volumen',         value: req.volume_m3 ? `${req.volume_m3} m³` : '—' },
      { label: 'Tipo Contenedor', value: offer.containerType?.type_name || '—' },
    ],
    documents: [],
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

function updateShipmentStatus(id, newStatus) {
  const shipment = shipments.value.find((s) => s.id === id)
  if (!shipment) return

  shipment.status = newStatus

  const cfg = statusMap[newStatus]
  if (cfg) {
    shipment.progress = cfg.progress
    shipment.progressColor = cfg.progressColor
    shipment.statusLabel = cfg.statusLabel
    shipment.statusColor = cfg.statusColor
    shipment.statusTextColor = cfg.statusTextColor
  }

  const currentIdx = statusOrder.indexOf(newStatus)
  shipment.timeline.forEach((step, i) => {
    if (i < currentIdx) {
      step.state = 'completed'
    } else if (i === currentIdx) {
      step.state = 'active'
    } else {
      step.state = 'pending'
    }
  })
}

function handleUploadDocument(id, newDoc) {
  const shipment = shipments.value.find((s) => s.id === id)
  if (!shipment) return
  shipment.documents.push(newDoc)
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
          @upload-document="handleUploadDocument"
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
