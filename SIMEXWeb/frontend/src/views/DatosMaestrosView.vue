<script setup>
import { ref, reactive, computed } from 'vue'
import MaestroNav from '@/components/datos-maestros/MaestroNav.vue'
import MaestroTable from '@/components/datos-maestros/MaestroTable.vue'
import MaestroFormModal from '@/components/datos-maestros/MaestroFormModal.vue'

// ─── MOCK DATA ───────────────────────────────────────────────────────────────

const paises = reactive([
  { id: 1, name: 'España' },
  { id: 2, name: 'China' },
  { id: 3, name: 'Alemania' },
  { id: 4, name: 'Países Bajos' },
  { id: 5, name: 'Francia' },
  { id: 6, name: 'Estados Unidos' },
  { id: 7, name: 'Bélgica' },
  { id: 8, name: 'Italia' },
])

const ciudades = reactive([
  { id: 1, name: 'Barcelona', country: 'España' },
  { id: 2, name: 'Madrid', country: 'España' },
  { id: 3, name: 'Valencia', country: 'España' },
  { id: 4, name: 'Shanghai', country: 'China' },
  { id: 5, name: 'Shenzhen', country: 'China' },
  { id: 6, name: 'Rotterdam', country: 'Países Bajos' },
  { id: 7, name: 'Frankfurt', country: 'Alemania' },
  { id: 8, name: 'Hamburgo', country: 'Alemania' },
  { id: 9, name: 'Miami', country: 'Estados Unidos' },
  { id: 10, name: 'Marsella', country: 'Francia' },
  { id: 11, name: 'Amberes', country: 'Bélgica' },
  { id: 12, name: 'Génova', country: 'Italia' },
])

const puertos = reactive([
  { id: 1, name: 'Puerto de Barcelona', city: 'Barcelona' },
  { id: 2, name: 'Puerto de Valencia', city: 'Valencia' },
  { id: 3, name: 'Puerto de Shanghai', city: 'Shanghai' },
  { id: 4, name: 'Puerto de Rotterdam', city: 'Rotterdam' },
  { id: 5, name: 'Puerto de Hamburgo', city: 'Hamburgo' },
  { id: 6, name: 'Puerto de Miami', city: 'Miami' },
  { id: 7, name: 'Puerto de Marsella', city: 'Marsella' },
  { id: 8, name: 'Puerto de Amberes', city: 'Amberes' },
])

const aeropuertos = reactive([
  { id: 1, code: 'BCN', name: 'Aeropuerto El Prat', city: 'Barcelona' },
  { id: 2, code: 'MAD', name: 'Aeropuerto Adolfo Suárez Barajas', city: 'Madrid' },
  { id: 3, code: 'SZX', name: "Aeropuerto Internacional Bao'an", city: 'Shenzhen' },
  { id: 4, code: 'PVG', name: 'Aeropuerto Internacional Pudong', city: 'Shanghai' },
  { id: 5, code: 'FRA', name: 'Aeropuerto Internacional Frankfurt', city: 'Frankfurt' },
  { id: 6, code: 'MIA', name: 'Aeropuerto Internacional de Miami', city: 'Miami' },
])

const navieras = reactive([
  { id: 1, name: 'Maersk Line', city: 'Hamburgo' },
  { id: 2, name: 'MSC', city: 'Génova' },
  { id: 3, name: 'Hapag-Lloyd', city: 'Hamburgo' },
  { id: 4, name: 'CMA CGM', city: 'Marsella' },
  { id: 5, name: 'Evergreen Marine', city: 'Rotterdam' },
  { id: 6, name: 'COSCO Shipping', city: 'Shanghai' },
])

const transportistas = reactive([
  { id: 1, name: 'DB Schenker', city: 'Frankfurt' },
  { id: 2, name: 'Geodis', city: 'Marsella' },
  { id: 3, name: 'Kuehne+Nagel', city: 'Hamburgo' },
  { id: 4, name: 'Transaher', city: 'Madrid' },
  { id: 5, name: 'Rhenus Logistics', city: 'Frankfurt' },
])

const contenedores = reactive([
  { id: 1, type_name: "20' ST (Standard)" },
  { id: 2, type_name: "40' ST (Standard)" },
  { id: 3, type_name: "40' HC (High Cube)" },
  { id: 4, type_name: "20' RF (Reefer)" },
  { id: 5, type_name: "40' RF (Reefer)" },
  { id: 6, type_name: 'OT (Open Top)' },
])

const incotermTypes = reactive([
  { id: 1, code: 'EXW', name: 'Ex Works' },
  { id: 2, code: 'FCA', name: 'Free Carrier' },
  { id: 3, code: 'FAS', name: 'Free Alongside Ship' },
  { id: 4, code: 'FOB', name: 'Free On Board' },
  { id: 5, code: 'CFR', name: 'Cost and Freight' },
  { id: 6, code: 'CIF', name: 'Cost Insurance Freight' },
  { id: 7, code: 'DAP', name: 'Delivered At Place' },
  { id: 8, code: 'DDP', name: 'Delivered Duty Paid' },
])

const pasosSegimiento = reactive([
  { id: 1, name: 'Embalaje y Verificación' },
  { id: 2, name: 'Carga' },
  { id: 3, name: 'Transporte' },
  { id: 4, name: 'Aduana de Exportación' },
  { id: 5, name: 'Manipulación Origen' },
  { id: 6, name: 'Flete' },
  { id: 7, name: 'Manipulación Destino' },
  { id: 8, name: 'Aduana de Importación' },
  { id: 9, name: 'Transporte a Destino' },
  { id: 10, name: 'Descarga' },
])

// ─── MAESTRO CONFIG ──────────────────────────────────────────────────────────

const maestroConfig = {
  paises: {
    label: 'Países',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
    ],
    data: paises,
  },
  ciudades: {
    label: 'Ciudades',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'country', label: 'País', type: 'select', relatedKey: 'paises', displayField: 'name' },
    ],
    data: ciudades,
  },
  puertos: {
    label: 'Puertos',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    data: puertos,
  },
  aeropuertos: {
    label: 'Aeropuertos',
    columns: [
      { key: 'code', label: 'Código IATA', type: 'text' },
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    data: aeropuertos,
  },
  navieras: {
    label: 'Navieras',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    data: navieras,
  },
  transportistas: {
    label: 'Transportistas',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    data: transportistas,
  },
  contenedores: {
    label: 'Tipos de Contenedor',
    columns: [
      { key: 'type_name', label: 'Tipo', type: 'text' },
    ],
    data: contenedores,
  },
  incoterms: {
    label: 'Incoterms',
    columns: [
      { key: 'code', label: 'Código', type: 'text' },
      { key: 'name', label: 'Nombre', type: 'text' },
    ],
    data: incotermTypes,
  },
  pasos: {
    label: 'Pasos de Seguimiento',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
    ],
    data: pasosSegimiento,
  },
}

// ─── NAV GROUPS ──────────────────────────────────────────────────────────────

const navGroups = [
  {
    label: 'Geografía',
    items: [
      { key: 'paises', label: 'Países' },
      { key: 'ciudades', label: 'Ciudades' },
    ],
  },
  {
    label: 'Infraestructura',
    items: [
      { key: 'puertos', label: 'Puertos' },
      { key: 'aeropuertos', label: 'Aeropuertos' },
    ],
  },
  {
    label: 'Transporte',
    items: [
      { key: 'navieras', label: 'Navieras' },
      { key: 'transportistas', label: 'Transportistas' },
    ],
  },
  {
    label: 'Operativo',
    items: [
      { key: 'contenedores', label: 'Tipos de Contenedor' },
      { key: 'incoterms', label: 'Incoterms' },
      { key: 'pasos', label: 'Pasos de Seguimiento' },
    ],
  },
]

// ─── ACTIVE TABLE ─────────────────────────────────────────────────────────────

const activeKey = ref('paises')
const activeMaestro = computed(() => maestroConfig[activeKey.value])

// ─── MODAL STATE ──────────────────────────────────────────────────────────────

const modalVisible = ref(false)
const editingRow = ref(null)

function openAdd() {
  editingRow.value = null
  modalVisible.value = true
}

function openEdit(row) {
  editingRow.value = row
  modalVisible.value = true
}

function closeModal() {
  modalVisible.value = false
}

// ─── CRUD HANDLERS ────────────────────────────────────────────────────────────

function handleSave(formData) {
  const data = activeMaestro.value.data
  if (editingRow.value) {
    const idx = data.findIndex(r => r.id === editingRow.value.id)
    if (idx !== -1) Object.assign(data[idx], formData)
  } else {
    const nextId = data.length ? Math.max(...data.map(r => r.id)) + 1 : 1
    data.push({ id: nextId, ...formData })
  }
  closeModal()
}

function handleDelete(row) {
  const data = activeMaestro.value.data
  const idx = data.findIndex(r => r.id === row.id)
  if (idx !== -1) data.splice(idx, 1)
}

const relatedData = computed(() => ({
  paises,
  ciudades,
}))
</script>

<template>
  <div class="maestros">
    <!-- Page header -->
    <div class="maestros-header">
      <div>
        <h1 class="maestros-title">Datos Maestros</h1>
        <p class="maestros-subtitle">Gestión de tablas de referencia del sistema</p>
      </div>
    </div>

    <!-- Content -->
    <div class="maestros-content">
      <MaestroNav
        :groups="navGroups"
        :active-key="activeKey"
        @select="activeKey = $event"
      />
      <MaestroTable
        :maestro="activeMaestro"
        @add="openAdd"
        @edit="openEdit"
        @delete="handleDelete"
      />
    </div>

    <MaestroFormModal
      :visible="modalVisible"
      :maestro-label="activeMaestro.label"
      :columns="activeMaestro.columns"
      :row="editingRow"
      :related-data="relatedData"
      @close="closeModal"
      @save="handleSave"
    />
  </div>
</template>

<style scoped>
.maestros {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.maestros-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.maestros-title {
  font-size: 22px;
  font-weight: 700;
  color: var(--text-primary);
}

.maestros-subtitle {
  font-size: 13px;
  color: var(--text-secondary);
  margin-top: 2px;
}

.maestros-content {
  display: flex;
  gap: 20px;
  align-items: start;
}
</style>
