<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRoleStore } from '@/stores/role'
import { useAuthStore } from '@/stores/auth'
import PresupuestosStats from '@/components/presupuestos/PresupuestosStats.vue'
import PresupuestosFilters from '@/components/presupuestos/PresupuestosFilters.vue'
import PresupuestosTable from '@/components/presupuestos/PresupuestosTable.vue'
import AprobarPresupuestoModal from '@/components/presupuestos/AprobarPresupuestoModal.vue'
import RechazarPresupuestoModal from '@/components/presupuestos/RechazarPresupuestoModal.vue'

const roleStore = useRoleStore()
const auth = useAuthStore()

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const headers = { Authorization: `Bearer ${auth.token}` }

const presupuestos = ref([])
const loading = ref(false)

const statusMap = { draft: 'Enviado', accepted: 'Aceptado', rejected: 'Rechazado' }

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

async function fetchPresupuestos() {
    loading.value = true
    try {
        const endpoint = roleStore.isAdmin ? '/commercial-offers' : '/commercial-offers/mine'
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

function openApproveModal(p) {
    selectedPresupuesto.value = p
    showApproveModal.value = true
}

function openRejectModal(p) {
    selectedPresupuesto.value = p
    showRejectModal.value = true
}

async function handleApprove() {
    try {
        await axios.put(LARAVEL + `/commercial-offers/${selectedPresupuesto.value.id}/approve`, {}, { headers })
        showApproveModal.value = false
        selectedPresupuesto.value = null
        await fetchPresupuestos()
    } catch (error) {
        console.error('Error al aprobar presupuesto:', error)
    }
}

async function handleReject(reason) {
    try {
        await axios.put(
            LARAVEL + `/commercial-offers/${selectedPresupuesto.value.id}/reject`,
            { rejection_reason: reason },
            { headers },
        )
        showRejectModal.value = false
        selectedPresupuesto.value = null
        await fetchPresupuestos()
    } catch (error) {
        console.error('Error al rechazar presupuesto:', error)
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
        <PresupuestosTable :presupuestos="filteredPresupuestos" :role="roleStore.currentRole"
            @aprobar="openApproveModal" @rechazar="openRejectModal" />

        <AprobarPresupuestoModal :visible="showApproveModal" :presupuesto="selectedPresupuesto"
            @close="showApproveModal = false" @confirm="handleApprove" />
        <RechazarPresupuestoModal :visible="showRejectModal" :presupuesto="selectedPresupuesto"
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

</style>
