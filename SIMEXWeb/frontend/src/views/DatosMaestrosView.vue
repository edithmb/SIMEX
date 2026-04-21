<script setup>
/**
 * @component DatosMaestrosView
 * @description Pantalla CRUD genérica para los datos maestros del
 * sistema (países, puertos, aeropuertos, navieras, transportistas, tipos
 * de contenedor, incoterms).
 *
 * La navegación lateral está declarada en `navGroups` y cada maestro en
 * `maestrosConfig` describe endpoint, columnas y referencia reactiva a
 * su lista. El mismo `MaestroFormModal` sirve para crear y editar,
 * distinguido por `editingRow`.
 */
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import MaestroNav from '@/components/datos-maestros/MaestroNav.vue'
import MaestroTable from '@/components/datos-maestros/MaestroTable.vue'
import MaestroFormModal from '@/components/datos-maestros/MaestroFormModal.vue'

const auth = useAuthStore()
const router = useRouter()

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const headers = { Authorization: `Bearer ${auth.token}` }

// ── Estado general ──
const loading = ref(false)
const errorMessage = ref('')

// ── Datos de cada maestro ──
const countriesList = ref([])
const portsList = ref([])
const airportsList = ref([])
const shippingLinesList = ref([])
const carriersList = ref([])
const containerTypesList = ref([])
const incotermsList = ref([])

// ── Configuración de la navegación lateral ──
const navGroups = [
  {
    label: 'Geografía',
    items: [
      { key: 'countries', label: 'Países' },
      { key: 'ports', label: 'Puertos' },
      { key: 'airports', label: 'Aeropuertos' },
    ],
  },
  {
    label: 'Transporte',
    items: [
      { key: 'shipping-lines', label: 'Líneas de Transporte' },
      { key: 'carriers', label: 'Modos de Transporte' },
      { key: 'container-types', label: 'Tipos de Contenedor' },
    ],
  },
  {
    label: 'Comercial',
    items: [
      { key: 'incoterms', label: 'Incoterms' },
    ],
  },
]

// ── Configuración de columnas por maestro ──
const maestrosConfig = {
  countries: {
    label: 'Países',
    endpoint: '/countries',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: countriesList,
  },
  ports: {
    label: 'Puertos',
    endpoint: '/ports',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: portsList,
  },
  airports: {
    label: 'Aeropuertos',
    endpoint: '/airports',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: airportsList,
  },
  'shipping-lines': {
    label: 'Líneas de Transporte',
    endpoint: '/shipping-lines',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: shippingLinesList,
  },
  carriers: {
    label: 'Modos de Transporte',
    endpoint: '/carriers',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: carriersList,
  },
  'container-types': {
    label: 'Tipos de Contenedor',
    endpoint: '/container-types',
    columns: [
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: containerTypesList,
  },
  incoterms: {
    label: 'Incoterms',
    endpoint: '/incoterms',
    columns: [
      { key: 'code', label: 'Código' },
      { key: 'name', label: 'Nombre' },
    ],
    dataRef: incotermsList,
  },
}

// ── Maestro activo ──
const activeKey = ref('countries')

/**
 * Información compacta del maestro actualmente activo (etiqueta,
 * columnas visibles y datos). Se recalcula automáticamente cuando
 * cambia `activeKey` o la lista reactiva subyacente.
 *
 * @type {import('vue').ComputedRef<{label:string, columns:object[], data:object[]}>}
 */
const activeMaestro = computed(() => {
  const config = maestrosConfig[activeKey.value]
  return {
    label: config.label,
    columns: config.columns,
    data: config.dataRef.value,
  }
})

const relatedData = ref({})

/**
 * Carga el maestro identificado por `key` desde su endpoint y refresca
 * la referencia reactiva asociada. Si no hay token fija un mensaje
 * "no autenticado"; si el backend responde 401 fuerza logout.
 *
 * @param {string} key Clave definida en `maestrosConfig`.
 * @returns {Promise<void>}
 */
async function fetchMaestro(key) {
  if (!auth.token) {
    errorMessage.value = 'No autenticado. Por favor inicia sesión.'
    return
  }

  loading.value = true
  errorMessage.value = ''
  try {
    const config = maestrosConfig[key]
    const res = await axios.get(LARAVEL + config.endpoint, { headers })
    config.dataRef.value = res.data
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    errorMessage.value = `Error al cargar ${maestrosConfig[key].label}.`
    console.error(`Error al cargar ${key}:`, error)
  } finally {
    loading.value = false
  }
}

// ── Carga inicial ──
onMounted(() => {
  fetchMaestro(activeKey.value)
})

// ── Cargar maestro cuando cambia la pestaña ──
watch(activeKey, (newKey) => {
  fetchMaestro(newKey)
})

// ── Modal ──
const modalVisible = ref(false)
const editingRow = ref(null)

/** Abre el modal en modo creación (sin fila seleccionada). */
function openAdd() {
  editingRow.value = null
  modalVisible.value = true
}

/**
 * Abre el modal en modo edición, precargando los datos de la fila.
 *
 * @param {object} row
 */
function openEdit(row) {
  editingRow.value = row
  modalVisible.value = true
}

/** Cierra el modal y limpia la fila en edición. */
function closeModal() {
  modalVisible.value = false
  editingRow.value = null
}

/**
 * Persiste el formulario del modal. Si había fila en edición hace PUT al
 * endpoint del maestro activo; en caso contrario POST. Refresca el
 * maestro al terminar con éxito.
 *
 * @param {object} data Payload validado por el modal.
 * @returns {Promise<void>}
 */
async function handleSave(data) {
  const config = maestrosConfig[activeKey.value]
  try {
    if (editingRow.value) {
      await axios.put(LARAVEL + config.endpoint + '/' + editingRow.value.id, data, { headers })
    } else {
      await axios.post(LARAVEL + config.endpoint, data, { headers })
    }
    closeModal()
    await fetchMaestro(activeKey.value)
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al guardar:', error)
  }
}

/**
 * Elimina la fila indicada haciendo DELETE al endpoint del maestro
 * activo y refresca la lista. Sin confirmación — el botón ya la pide en
 * el componente hijo.
 *
 * @param {object} row
 * @returns {Promise<void>}
 */
async function handleDelete(row) {
  const config = maestrosConfig[activeKey.value]
  try {
    await axios.delete(LARAVEL + config.endpoint + '/' + row.id, { headers })
    await fetchMaestro(activeKey.value)
  } catch (error) {
    if (error.response?.status === 401) {
      await auth.logout()
      router.push({ name: 'login' })
      return
    }
    console.error('Error al eliminar:', error)
  }
}
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
