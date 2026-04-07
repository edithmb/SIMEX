<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import MaestroNav from '@/components/datos-maestros/MaestroNav.vue'
import MaestroTable from '@/components/datos-maestros/MaestroTable.vue'
import MaestroFormModal from '@/components/datos-maestros/MaestroFormModal.vue'

const LARAVEL = import.meta.env.VITE_LARAVEL_API || 'http://127.0.0.1:8000/api'
const auth = useAuthStore()

// ─── DATA ─────────────────────────────────────────────────────────────────────

const paises        = ref([])
const ciudades      = ref([])
const puertos       = ref([])
const aeropuertos   = ref([])
const navieras      = ref([])
const transportistas= ref([])
const contenedores  = ref([])

const tablaRefMap = {
  paises:         { ref: paises,         tabla: 'countries' },
  ciudades:       { ref: ciudades,       tabla: 'cities' },
  puertos:        { ref: puertos,        tabla: 'ports' },
  aeropuertos:    { ref: aeropuertos,    tabla: 'airports' },
  navieras:       { ref: navieras,       tabla: 'shipping-lines' },
  transportistas: { ref: transportistas, tabla: 'carriers' },
  contenedores:   { ref: contenedores,   tabla: 'container-types' },
}

const loading = ref(false)
const errorMessage = ref('')

function getAuthHeaders() {
  return { Authorization: `Bearer ${auth.token}` }
}

function normalizeApiRows(payload) {
  if (Array.isArray(payload)) return payload
  if (Array.isArray(payload?.data)) return payload.data
  return []
}

async function fetchTabla(key) {
  if (!auth.token) return
  const { ref: dataRef, tabla } = tablaRefMap[key]
  const res = await axios.get(LARAVEL + '/' + tabla, { headers: getAuthHeaders() })
  dataRef.value = normalizeApiRows(res.data)
}

async function cargarDatosIniciales() {
  if (!LARAVEL) {
    errorMessage.value = 'Falta configurar la URL de API (VITE_LARAVEL_API).'
    return
  }

  if (!auth.token) {
    errorMessage.value = 'No hay sesion activa. Inicia sesion nuevamente.'
    return
  }

  loading.value = true
  errorMessage.value = ''
  try {
    await Promise.all([fetchTabla('paises'), fetchTabla('ciudades')])
    await fetchTabla(activeKey.value)
  } catch (err) {
    const message = err.response?.data?.message || err.message || 'No se pudieron cargar los datos maestros.'
    errorMessage.value = message
  } finally {
    loading.value = false
  }
}

// ─── MAESTRO CONFIG ──────────────────────────────────────────────────────────

const maestroConfig = {
  paises: {
    label: 'Países',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
    ],
    get data() { return paises.value },
  },
  ciudades: {
    label: 'Ciudades',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'country_id', label: 'País', type: 'select', relatedKey: 'paises', displayField: 'name' },
    ],
    get data() { return ciudades.value },
  },
  puertos: {
    label: 'Puertos',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city_id', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    get data() { return puertos.value },
  },
  aeropuertos: {
    label: 'Aeropuertos',
    columns: [
      { key: 'code', label: 'Código IATA', type: 'text' },
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city_id', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    get data() { return aeropuertos.value },
  },
  navieras: {
    label: 'Navieras',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city_id', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    get data() { return navieras.value },
  },
  transportistas: {
    label: 'Transportistas',
    columns: [
      { key: 'name', label: 'Nombre', type: 'text' },
      { key: 'city_id', label: 'Ciudad', type: 'select', relatedKey: 'ciudades', displayField: 'name' },
    ],
    get data() { return transportistas.value },
  },
  contenedores: {
    label: 'Tipos de Contenedor',
    columns: [
      { key: 'type_name', label: 'Tipo', type: 'text' },
    ],
    get data() { return contenedores.value },
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
    ],
  },
]

// ─── ACTIVE TABLE ─────────────────────────────────────────────────────────────

const activeKey = ref('paises')
const activeMaestro = computed(() => maestroConfig[activeKey.value])

watch(activeKey, async (key) => {
  if (!auth.token) return
  try {
    loading.value = true
    errorMessage.value = ''
    await fetchTabla(key)
  } catch (err) {
    const message = err.response?.data?.message || err.message || 'No se pudo cargar la tabla seleccionada.'
    errorMessage.value = message
  } finally {
    loading.value = false
  }
})

watch(() => auth.token, (token) => {
  if (token) cargarDatosIniciales()
}, { immediate: true })

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

async function handleSave(formData) {
  const { tabla } = tablaRefMap[activeKey.value]
  errorMessage.value = ''
  if (editingRow.value) {
    await axios.put(LARAVEL + '/' + tabla + '/' + editingRow.value.id, formData, { headers: getAuthHeaders() })
  } else {
    await axios.post(LARAVEL + '/' + tabla, formData, { headers: getAuthHeaders() })
  }
  await fetchTabla(activeKey.value)
  closeModal()
}

async function handleDelete(row) {
  const { tabla } = tablaRefMap[activeKey.value]
  errorMessage.value = ''
  await axios.delete(LARAVEL + '/' + tabla + '/' + row.id, { headers: getAuthHeaders() })
  await fetchTabla(activeKey.value)
}

const relatedData = computed(() => ({
  paises: paises.value,
  ciudades: ciudades.value,
}))

// ─── INIT ─────────────────────────────────────────────────────────────────────

onMounted(() => {
  if (auth.token) cargarDatosIniciales()
})
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

    <p v-if="loading" class="maestros-status">Cargando datos maestros...</p>
    <p v-else-if="errorMessage" class="maestros-error">{{ errorMessage }}</p>

    <!-- Content -->
    <div class="maestros-content">
      <MaestroNav
        :groups="navGroups"
        :active-key="activeKey"
        @select="activeKey = $event"
      />
      <MaestroTable
        :maestro="activeMaestro"
        :related-data="relatedData"
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
.maestros-status {
  margin: 6px 0 12px;
  font-size: 13px;
  color: var(--text-muted);
}

.maestros-error {
  margin: 6px 0 12px;
  font-size: 13px;
  color: #dc2626;
}

.maestros-content {
  display: flex;
  gap: 20px;
  min-height: 0;
}

@media (max-width: 768px) {
  .maestros-content {
    flex-direction: column;
  }

  :deep(.maestro-nav) {
    width: 100%;
    min-width: 0;
  }
}
</style>
