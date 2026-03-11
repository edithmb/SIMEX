<script setup>
import { shallowRef, computed, reactive } from 'vue'
import TrackingFilters from '@/components/seguimiento/TrackingFilters.vue'
import ShipmentList from '@/components/seguimiento/ShipmentList.vue'
import ShipmentDetail from '@/components/seguimiento/ShipmentDetail.vue'
import { useRoleStore } from '@/stores/role'

const roleStore = useRoleStore()

const shipments = reactive([
  {
    id: 'ENV-2024-4521',
    ref: 'ENV-2024-4521',
    client: 'Importaciones García S.L.',
    routeFrom: 'Shanghai',
    routeTo: 'Barcelona',
    transport: 'ship',
    transportLabel: 'marítimo',
    incoterm: 'CIF',
    incotermColor: '#1a6fb5',
    status: 'transporte',
    statusLabel: 'En Tránsito',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 40,
    progressColor: '#10b981',
    etd: '2024-01-20',
    eta: '2024-02-18',
    atd: '2024-01-22',
    ata: null,
    timeline: [
      { name: 'Preparación Mercadería', location: 'Fábrica Origen', date: '15 Ene 2024', state: 'completed' },
      { name: 'Transporte Interior Origen', location: 'Shanghai', date: '18 Ene 2024', state: 'completed' },
      { name: 'Terminal / Puerto Origen', location: 'Puerto de Shanghai', date: '20 Ene 2024', state: 'completed' },
      { name: 'Carga a Bordo', location: null, date: '22 Ene 2024', state: 'completed' },
      { name: 'Transporte Marítimo Principal', location: null, date: null, state: 'active', badge: 'En Curso', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Buque: Ever Given - Posición actual: Mar Mediterráneo' },
      { name: 'Puerto Destino', location: 'Puerto de Barcelona', date: 'Est. 18 Feb 2024', state: 'pending' },
    ],
    data: [
      { label: 'Peso Bruto', value: '12,450 kg' },
      { label: 'Volumen', value: '45 m³' },
      { label: 'Tipo Contenedor', value: "40' HC" },
      { label: 'Naviera/Carrier', value: 'Maersk Line' },
    ],
    documents: [
      { name: 'Bill of Lading (BL)', ready: true },
      { name: 'Factura Comercial', ready: true },
      { name: 'Packing List', ready: true },
      { name: 'Certificado de Origen', ready: false },
      { name: 'DUA Importación', ready: false },
    ],
  },
  {
    id: 'ENV-2024-4520',
    ref: 'ENV-2024-4520',
    client: 'Textiles Mediterráneo S.A.',
    routeFrom: 'Rotterdam',
    routeTo: 'Valencia',
    transport: 'ship',
    transportLabel: 'marítimo',
    incoterm: 'FOB',
    incotermColor: '#1a6fb5',
    status: 'aduanas',
    statusLabel: 'En Aduana',
    statusColor: '#fef3c7',
    statusTextColor: '#b45309',
    progress: 70,
    progressColor: '#f59e0b',
    etd: '2024-01-14',
    eta: '2024-01-30',
    atd: '2024-01-15',
    ata: '2024-01-28',
    timeline: [
      { name: 'Preparación Mercadería', location: 'Fábrica Origen', date: '10 Ene 2024', state: 'completed' },
      { name: 'Transporte Interior Origen', location: 'Rotterdam', date: '12 Ene 2024', state: 'completed' },
      { name: 'Puerto Origen', location: 'Puerto de Rotterdam', date: '14 Ene 2024', state: 'completed' },
      { name: 'Transporte Marítimo', location: null, date: '15 Ene 2024', state: 'completed' },
      { name: 'Puerto Destino', location: 'Puerto de Valencia', date: '28 Ene 2024', state: 'completed' },
      { name: 'Despacho Aduana', location: 'Valencia', date: null, state: 'active', badge: 'En Proceso', badgeColor: '#fef3c7', badgeTextColor: '#b45309' },
      { name: 'Entrega Final', location: null, date: null, state: 'pending' },
    ],
    data: [
      { label: 'Peso Bruto', value: '8,200 kg' },
      { label: 'Volumen', value: '32 m³' },
      { label: 'Tipo Contenedor', value: "20' ST" },
      { label: 'Naviera/Carrier', value: 'MSC' },
    ],
    documents: [
      { name: 'Bill of Lading (BL)', ready: true },
      { name: 'Factura Comercial', ready: true },
      { name: 'Packing List', ready: true },
      { name: 'Certificado de Origen', ready: true },
      { name: 'DUA Importación', ready: false },
    ],
  },
  {
    id: 'ENV-2024-4519',
    ref: 'ENV-2024-4519',
    client: 'Electrónica Levante S.A.',
    routeFrom: 'Shenzhen',
    routeTo: 'Madrid',
    transport: 'plane',
    transportLabel: 'aéreo',
    incoterm: 'DDP',
    incotermColor: '#1a6fb5',
    status: 'transporte',
    statusLabel: 'En Tránsito',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 40,
    progressColor: '#10b981',
    etd: '2024-01-26',
    eta: '2024-01-28',
    atd: '2024-01-26',
    ata: null,
    timeline: [
      { name: 'Recogida Mercadería', location: 'Almacén Shenzhen', date: '25 Ene 2024', state: 'completed' },
      { name: 'Consolidación Carga', location: 'Aeropuerto Shenzhen', date: '26 Ene 2024', state: 'completed' },
      { name: 'Transporte Aéreo', location: null, date: null, state: 'active', badge: 'En Vuelo', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Vuelo: CX5420 - ETA: 28 Ene 2024' },
      { name: 'Llegada Aeropuerto Destino', location: 'Madrid Barajas', date: null, state: 'pending' },
      { name: 'Despacho Aduana', location: null, date: null, state: 'pending' },
      { name: 'Entrega Final', location: null, date: null, state: 'pending' },
    ],
    data: [
      { label: 'Peso Bruto', value: '1,850 kg' },
      { label: 'Volumen', value: '6.5 m³' },
      { label: 'Tipo Carga', value: 'Paquetería' },
      { label: 'Aerolínea', value: 'Cathay Pacific' },
    ],
    documents: [
      { name: 'Air Waybill (AWB)', ready: true },
      { name: 'Factura Comercial', ready: true },
      { name: 'Packing List', ready: true },
      { name: 'DUA Importación', ready: false },
    ],
  },
  {
    id: 'ENV-2024-4518',
    ref: 'ENV-2024-4518',
    client: 'Maquinaria Industrial Norte',
    routeFrom: 'Frankfurt',
    routeTo: 'Zaragoza',
    transport: 'truck',
    transportLabel: 'terrestre',
    incoterm: 'DAP',
    incotermColor: '#1a6fb5',
    status: 'transporte',
    statusLabel: 'En Tránsito',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 40,
    progressColor: '#10b981',
    etd: '2024-01-22',
    eta: '2024-01-25',
    atd: '2024-01-22',
    ata: null,
    timeline: [
      { name: 'Recogida Mercadería', location: 'Fábrica Frankfurt', date: '22 Ene 2024', state: 'completed' },
      { name: 'Transporte Terrestre', location: null, date: null, state: 'active', badge: 'En Ruta', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Camión: SP-4521-AB - Posición: Sur de Francia' },
      { name: 'Llegada Destino', location: 'Zaragoza', date: null, state: 'pending' },
      { name: 'Entrega Final', location: null, date: null, state: 'pending' },
    ],
    data: [
      { label: 'Peso Bruto', value: '18,500 kg' },
      { label: 'Volumen', value: '55 m³' },
      { label: 'Tipo Transporte', value: 'Camión Completo' },
      { label: 'Transportista', value: 'DB Schenker' },
    ],
    documents: [
      { name: 'CMR', ready: true },
      { name: 'Factura Comercial', ready: true },
      { name: 'Packing List', ready: true },
    ],
  },
  {
    id: 'ENV-2024-4517',
    ref: 'ENV-2024-4517',
    client: 'Alimentación Ibérica S.L.',
    routeFrom: 'Miami',
    routeTo: 'Barcelona',
    transport: 'ship',
    transportLabel: 'marítimo',
    incoterm: 'CIF',
    incotermColor: '#1a6fb5',
    status: 'preparacion',
    statusLabel: 'Pendiente',
    statusColor: '#e5e7eb',
    statusTextColor: '#4b5563',
    progress: 10,
    progressColor: '#9ca3af',
    etd: '2024-02-10',
    eta: '2024-03-05',
    atd: null,
    ata: null,
    timeline: [
      { name: 'Preparación Mercadería', location: 'Almacén Miami', date: null, state: 'active', badge: 'Preparando', badgeColor: '#e5e7eb', badgeTextColor: '#4b5563' },
      { name: 'Transporte Interior Origen', location: null, date: null, state: 'pending' },
      { name: 'Puerto Origen', location: 'Puerto de Miami', date: null, state: 'pending' },
      { name: 'Transporte Marítimo', location: null, date: null, state: 'pending' },
      { name: 'Puerto Destino', location: 'Puerto de Barcelona', date: null, state: 'pending' },
      { name: 'Entrega Final', location: null, date: null, state: 'pending' },
    ],
    data: [
      { label: 'Peso Bruto', value: '5,600 kg' },
      { label: 'Volumen', value: '22 m³' },
      { label: 'Tipo Contenedor', value: "20' RF" },
      { label: 'Naviera/Carrier', value: 'Hapag-Lloyd' },
    ],
    documents: [
      { name: 'Bill of Lading (BL)', ready: false },
      { name: 'Factura Comercial', ready: false },
      { name: 'Packing List', ready: false },
      { name: 'Certificado Sanitario', ready: false },
    ],
  },
])

const selectedId = shallowRef('ENV-2024-4521')

const selectedShipment = computed(() => {
  return shipments.find((s) => s.id === selectedId.value) || shipments[0]
})

function handleSelect(id) {
  selectedId.value = id
}

function updateShipmentStatus(id, newStatus) {
  const shipment = shipments.find((s) => s.id === id)
  if (!shipment) return

  shipment.status = newStatus

  const statusMap = {
    preparacion: { progress: 10, progressColor: '#9ca3af', statusLabel: 'Preparación', statusColor: '#e5e7eb', statusTextColor: '#4b5563' },
    transporte: { progress: 40, progressColor: '#10b981', statusLabel: 'En Tránsito', statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
    aduanas: { progress: 70, progressColor: '#f59e0b', statusLabel: 'En Aduana', statusColor: '#fef3c7', statusTextColor: '#b45309' },
    entrega: { progress: 90, progressColor: '#10b981', statusLabel: 'En Entrega', statusColor: '#dbeafe', statusTextColor: '#1a6fb5' },
    completado: { progress: 100, progressColor: '#6b8e23', statusLabel: 'Completado', statusColor: '#d1fae5', statusTextColor: '#047857' },
  }

  const cfg = statusMap[newStatus]
  if (cfg) {
    shipment.progress = cfg.progress
    shipment.progressColor = cfg.progressColor
    shipment.statusLabel = cfg.statusLabel
    shipment.statusColor = cfg.statusColor
    shipment.statusTextColor = cfg.statusTextColor
  }

  // Recalculate timeline step states
  const steps = shipment.timeline
  const statusOrder = ['preparacion', 'transporte', 'aduanas', 'entrega', 'completado']
  const currentIdx = statusOrder.indexOf(newStatus)
  const totalSteps = steps.length

  if (newStatus === 'completado') {
    steps.forEach((step) => { step.state = 'completed' })
  } else {
    // Map status index to approximate timeline step
    const activeStepIdx = Math.min(Math.round((currentIdx / (statusOrder.length - 1)) * (totalSteps - 1)), totalSteps - 1)
    steps.forEach((step, i) => {
      if (i < activeStepIdx) {
        step.state = 'completed'
      } else if (i === activeStepIdx) {
        step.state = 'active'
      } else {
        step.state = 'pending'
      }
    })
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
          :shipment="selectedShipment"
          :role="roleStore.currentRole"
          @update-status="updateShipmentStatus"
        />
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

@media (max-width: 1100px) {
  .seguimiento-content {
    grid-template-columns: 1fr;
  }
}
</style>
