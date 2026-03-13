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
    statusLabel: 'Transporte',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 30,
    progressColor: '#10b981',
    etd: '2024-01-20',
    eta: '2024-02-18',
    atd: '2024-01-22',
    ata: null,
    timeline: [
      { name: 'Embalaje y Verificación', location: 'Fábrica Origen', date: '15 Ene 2024', state: 'completed' },
      { name: 'Carga', location: 'Puerto de Shanghai', date: '22 Ene 2024', state: 'completed' },
      { name: 'Transporte', location: null, date: null, state: 'active', badge: 'En Curso', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Buque: Ever Given - Posición actual: Mar Mediterráneo' },
      { name: 'Aduana de Exportación', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Origen', location: null, date: null, state: 'pending' },
      { name: 'Flete', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Destino', location: null, date: null, state: 'pending' },
      { name: 'Aduana de Importación', location: 'Barcelona', date: 'Est. 18 Feb 2024', state: 'pending' },
      { name: 'Transporte a Destino', location: null, date: null, state: 'pending' },
      { name: 'Descarga', location: null, date: null, state: 'pending' },
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
    status: 'aduana_imp',
    statusLabel: 'Aduana de Importación',
    statusColor: '#fef3c7',
    statusTextColor: '#b45309',
    progress: 80,
    progressColor: '#f59e0b',
    etd: '2024-01-14',
    eta: '2024-01-30',
    atd: '2024-01-15',
    ata: '2024-01-28',
    timeline: [
      { name: 'Embalaje y Verificación', location: 'Fábrica Origen', date: '10 Ene 2024', state: 'completed' },
      { name: 'Carga', location: 'Puerto de Rotterdam', date: '13 Ene 2024', state: 'completed' },
      { name: 'Transporte', location: null, date: '14 Ene 2024', state: 'completed' },
      { name: 'Aduana de Exportación', location: 'Rotterdam', date: '14 Ene 2024', state: 'completed' },
      { name: 'Manipulación Origen', location: 'Puerto de Rotterdam', date: '14 Ene 2024', state: 'completed' },
      { name: 'Flete', location: null, date: '15 Ene 2024', state: 'completed' },
      { name: 'Manipulación Destino', location: 'Puerto de Valencia', date: '28 Ene 2024', state: 'completed' },
      { name: 'Aduana de Importación', location: 'Valencia', date: null, state: 'active', badge: 'En Proceso', badgeColor: '#fef3c7', badgeTextColor: '#b45309' },
      { name: 'Transporte a Destino', location: null, date: null, state: 'pending' },
      { name: 'Descarga', location: null, date: null, state: 'pending' },
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
    statusLabel: 'Transporte',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 30,
    progressColor: '#10b981',
    etd: '2024-01-26',
    eta: '2024-01-28',
    atd: '2024-01-26',
    ata: null,
    timeline: [
      { name: 'Embalaje y Verificación', location: 'Almacén Shenzhen', date: '25 Ene 2024', state: 'completed' },
      { name: 'Carga', location: 'Aeropuerto Shenzhen', date: '26 Ene 2024', state: 'completed' },
      { name: 'Transporte', location: null, date: null, state: 'active', badge: 'En Vuelo', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Vuelo: CX5420 - ETA: 28 Ene 2024' },
      { name: 'Aduana de Exportación', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Origen', location: null, date: null, state: 'pending' },
      { name: 'Flete', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Destino', location: null, date: null, state: 'pending' },
      { name: 'Aduana de Importación', location: 'Madrid Barajas', date: null, state: 'pending' },
      { name: 'Transporte a Destino', location: null, date: null, state: 'pending' },
      { name: 'Descarga', location: null, date: null, state: 'pending' },
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
    statusLabel: 'Transporte',
    statusColor: '#dbeafe',
    statusTextColor: '#1a6fb5',
    progress: 30,
    progressColor: '#10b981',
    etd: '2024-01-22',
    eta: '2024-01-25',
    atd: '2024-01-22',
    ata: null,
    timeline: [
      { name: 'Embalaje y Verificación', location: 'Fábrica Frankfurt', date: '22 Ene 2024', state: 'completed' },
      { name: 'Carga', location: null, date: '22 Ene 2024', state: 'completed' },
      { name: 'Transporte', location: null, date: null, state: 'active', badge: 'En Ruta', badgeColor: '#dbeafe', badgeTextColor: '#1a6fb5', detail: 'Camión: SP-4521-AB - Posición: Sur de Francia' },
      { name: 'Aduana de Exportación', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Origen', location: null, date: null, state: 'pending' },
      { name: 'Flete', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Destino', location: null, date: null, state: 'pending' },
      { name: 'Aduana de Importación', location: null, date: null, state: 'pending' },
      { name: 'Transporte a Destino', location: 'Zaragoza', date: null, state: 'pending' },
      { name: 'Descarga', location: null, date: null, state: 'pending' },
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
    status: 'embalaje',
    statusLabel: 'Embalaje y Verificación',
    statusColor: '#e5e7eb',
    statusTextColor: '#4b5563',
    progress: 10,
    progressColor: '#9ca3af',
    etd: '2024-02-10',
    eta: '2024-03-05',
    atd: null,
    ata: null,
    timeline: [
      { name: 'Embalaje y Verificación', location: 'Almacén Miami', date: null, state: 'active', badge: 'Preparando', badgeColor: '#e5e7eb', badgeTextColor: '#4b5563' },
      { name: 'Carga', location: null, date: null, state: 'pending' },
      { name: 'Transporte', location: null, date: null, state: 'pending' },
      { name: 'Aduana de Exportación', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Origen', location: null, date: null, state: 'pending' },
      { name: 'Flete', location: null, date: null, state: 'pending' },
      { name: 'Manipulación Destino', location: null, date: null, state: 'pending' },
      { name: 'Aduana de Importación', location: null, date: null, state: 'pending' },
      { name: 'Transporte a Destino', location: 'Barcelona', date: null, state: 'pending' },
      { name: 'Descarga', location: null, date: null, state: 'pending' },
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

  const cfg = statusMap[newStatus]
  if (cfg) {
    shipment.progress = cfg.progress
    shipment.progressColor = cfg.progressColor
    shipment.statusLabel = cfg.statusLabel
    shipment.statusColor = cfg.statusColor
    shipment.statusTextColor = cfg.statusTextColor
  }

  // Recalculate timeline step states — 1:1 mapping (10 statuses, 10 steps)
  const steps = shipment.timeline
  const statusOrder = ['embalaje', 'carga', 'transporte', 'aduana_exp', 'manip_origen', 'flete', 'manip_destino', 'aduana_imp', 'transporte_destino', 'descarga']
  const currentIdx = statusOrder.indexOf(newStatus)

  steps.forEach((step, i) => {
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
  const shipment = shipments.find((s) => s.id === id)
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
          :shipment="selectedShipment"
          :role="roleStore.currentRole"
          @update-status="updateShipmentStatus"
          @upload-document="handleUploadDocument"
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
