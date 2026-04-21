<script setup>
/**
 * @component PresupuestosView
 * @description Listado de ofertas comerciales (presupuestos) con filtros,
 * búsqueda y acciones de aprobar/rechazar.
 *
 * Cambia de endpoint según el rol real (admin ve todo; cliente ve sólo
 * los suyos vía `/commercial-offers/mine`). El estado del backend se
 * mapea a etiquetas en español para la tabla.
 */
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useRoleStore } from '@/stores/role'
import { useAuthStore } from '@/stores/auth'
import PresupuestosStats from '@/components/presupuestos/PresupuestosStats.vue'
import PresupuestosFilters from '@/components/presupuestos/PresupuestosFilters.vue'
import PresupuestosTable from '@/components/presupuestos/PresupuestosTable.vue'
import AprobarPresupuestoModal from '@/components/presupuestos/AprobarPresupuestoModal.vue'
import RechazarPresupuestoModal from '@/components/presupuestos/RechazarPresupuestoModal.vue'
import Spinner from '@/components/common/Spinner.vue'

const roleStore = useRoleStore()
const auth = useAuthStore()

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const headers = { Authorization: `Bearer ${auth.token}` }

const presupuestos = ref([])
const loading = ref(false)
const submittingApprove = ref(false)
const submittingReject = ref(false)

const statusMap = { draft: 'Enviado', accepted: 'Aceptado', rejected: 'Rechazado' }

/**
 * Aplana una oferta del backend a la forma que espera la tabla: nombres
 * ya resueltos, precio numérico y fecha formateada en es-ES. Cualquier
 * valor ausente se sustituye por `'—'` para no romper el render.
 *
 * @param {object} item Oferta con relaciones `client`, `incoterm`, `origin_port`, …
 * @returns {object}
 */
function mapPresupuesto(item) {
    return {
        id: item.id,
        reference: item.reference ?? '—',
        client_request_id: item.client_request_id,
        clientName: item.client?.company_name ?? '—',
        incoterm: item.incoterm?.incoterm_type?.code ?? '—',
        origin_port: item.origin_port?.name ?? '—',
        destination_port: item.destination_port?.name ?? '—',
        container_type: item.container_type?.type_name ?? '—',
        price: Number(item.price),
        valid_until: item.valid_until ? new Date(item.valid_until).toLocaleDateString('es-ES') : '—',
        status: statusMap[item.status] ?? item.status,
        rejection_reason: item.rejection_reason ?? '',
        comments: item.comments ?? '',
    }
}

/**
 * Carga el listado paginado de ofertas desde el endpoint adecuado y mapea
 * los resultados. La respuesta del backend es un paginator (`res.data.data`).
 *
 * @returns {Promise<void>}
 */
async function fetchPresupuestos() {
    loading.value = true
    try {
        const endpoint = auth.backendIsAdmin ? '/commercial-offers' : '/commercial-offers/mine'
        const res = await axios.get(LARAVEL + endpoint, { headers })
        presupuestos.value = res.data.data.map(mapPresupuesto)
    } catch (error) {
        console.error('Error al cargar presupuestos:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchPresupuestos()
})

// Re-fetch cuando cambia el rol para usar el endpoint correcto
watch(() => roleStore.currentRole, () => {
    fetchPresupuestos()
})

/**
 * Resultado de aplicar filtro (estado traducido) y búsqueda (referencia
 * + nombre de cliente) sobre `presupuestos`.
 *
 * @type {import('vue').ComputedRef<object[]>}
 */

const activeFilter = ref('Todos')
const searchQuery = ref('')

const filteredPresupuestos = computed(() => {
    let result = presupuestos.value
    if (activeFilter.value !== 'Todos') {
        result = result.filter((p) => p.status === activeFilter.value)
    }
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(
            (p) => p.reference.toLowerCase().includes(q) || p.clientName.toLowerCase().includes(q),
        )
    }
    return result
})

const showApproveModal = ref(false)
const showRejectModal = ref(false)
const selectedPresupuesto = ref(null)

/**
 * Abre el modal de aprobación para el presupuesto seleccionado.
 *
 * @param {object} p Presupuesto mapeado.
 */
function openApproveModal(p) {
    selectedPresupuesto.value = p
    showApproveModal.value = true
}

/**
 * Abre el modal de rechazo para el presupuesto seleccionado.
 *
 * @param {object} p Presupuesto mapeado.
 */
function openRejectModal(p) {
    selectedPresupuesto.value = p
    showRejectModal.value = true
}

/**
 * Llama al endpoint `approve` del backend. Al aprobarse se crea la
 * `LogisticsOperation` asociada (lo hace el backend dentro de una
 * transacción). El listado se refresca siempre (éxito o error) para que
 * la tabla refleje el estado real.
 *
 * @param {number|string} id Id de la oferta a aprobar.
 * @returns {Promise<void>}
 */
async function handleApprove(id) {
    if (submittingApprove.value) return
    submittingApprove.value = true
    try {
        await axios.put(LARAVEL + `/commercial-offers/${id}/approve`, {}, { headers })
        showApproveModal.value = false
        selectedPresupuesto.value = null
    } catch (error) {
        console.error('Error al aprobar presupuesto:', error)
    } finally {
        submittingApprove.value = false
        await fetchPresupuestos()
    }
}

/**
 * Rechaza una oferta enviando el motivo proporcionado por el usuario.
 * Reentrancia bloqueada por `submittingReject`.
 *
 * @param {string} reason Motivo de rechazo validado por el modal.
 * @param {number|string} id Id de la oferta a rechazar.
 * @returns {Promise<void>}
 */
async function handleReject(reason, id) {
    if (submittingReject.value) return
    submittingReject.value = true
    try {
        await axios.put(
            LARAVEL + `/commercial-offers/${id}/reject`,
            { rejection_reason: reason },
            { headers },
        )
        showRejectModal.value = false
        selectedPresupuesto.value = null
    } catch (error) {
        console.error('Error al rechazar presupuesto:', error)
    } finally {
        submittingReject.value = false
        await fetchPresupuestos()
    }
}
</script>

<template>
    <div class="presupuestos">
        <div class="presupuestos-header">
            <h2 class="presupuestos-header-title">Presupuestos</h2>
            <p class="presupuestos-header-subtitle">Ofertas económicas enviadas a los clientes.</p>
        </div>

        <PresupuestosStats :presupuestos="presupuestos" />
        <PresupuestosFilters :active-filter="activeFilter" :search-query="searchQuery"
            @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />

        <div v-if="loading" class="view-loading">
            <Spinner :size="40" />
        </div>
        <PresupuestosTable v-else :presupuestos="filteredPresupuestos" :role="roleStore.currentRole"
            @aprobar="openApproveModal" @rechazar="openRejectModal" />

        <AprobarPresupuestoModal :visible="showApproveModal" :presupuesto="selectedPresupuesto"
            :submitting="submittingApprove"
            @close="showApproveModal = false" @confirm="handleApprove" />
        <RechazarPresupuestoModal :visible="showRejectModal" :presupuesto="selectedPresupuesto"
            :submitting="submittingReject"
            @close="showRejectModal = false" @confirm="handleReject" />
    </div>
</template>

<style scoped>
.presupuestos {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.presupuestos-header {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.presupuestos-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.presupuestos-header-subtitle {
    font-size: 13.5px;
    color: var(--text-secondary);
}

.view-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 0;
    color: var(--accent-blue);
}
</style>
