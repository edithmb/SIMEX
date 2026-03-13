<script setup>
import { ref, reactive, computed } from 'vue'
import { useRoleStore } from '@/stores/role'
import PresupuestosStats from '@/components/presupuestos/PresupuestosStats.vue'
import PresupuestosFilters from '@/components/presupuestos/PresupuestosFilters.vue'
import PresupuestosTable from '@/components/presupuestos/PresupuestosTable.vue'
import AprobarPresupuestoModal from '@/components/presupuestos/AprobarPresupuestoModal.vue'
import RechazarPresupuestoModal from '@/components/presupuestos/RechazarPresupuestoModal.vue'

const roleStore = useRoleStore()

const presupuestos = reactive([
    { id: 1, reference: 'PR-2024-089', client_request_id: 1, clientName: 'Textiles Mediterráneo S.A.', incoterm: 'CIF', origin_port: 'Puerto de Shanghai', destination_port: 'Puerto de Valencia', container_type: "40' High Cube", price: 12450, valid_until: '15/04/2024', status: 'Enviado', rejection_reason: '', comments: '' },
    { id: 2, reference: 'PR-2024-090', client_request_id: 2, clientName: 'Importaciones García S.L.', incoterm: 'EXW', origin_port: 'Puerto de Miami', destination_port: 'Puerto de Barcelona', container_type: "20' Standard", price: 4200, valid_until: '20/04/2024', status: 'Enviado', rejection_reason: '', comments: '' },
    { id: 3, reference: 'PR-2024-091', client_request_id: 3, clientName: 'Alimentación Ibérica S.L.', incoterm: 'DDP', origin_port: 'Puerto de Rotterdam', destination_port: 'Puerto de Barcelona', container_type: "40' Reefer", price: 3800, valid_until: '25/03/2024', status: 'Aceptado', rejection_reason: '', comments: '' },
    { id: 4, reference: 'PR-2024-092', client_request_id: 4, clientName: 'Electrónica Levante S.A.', incoterm: 'FOB', origin_port: 'Puerto de Shenzhen', destination_port: 'Puerto de Bilbao', container_type: "20' Standard", price: 28500, valid_until: '10/03/2024', status: 'Rechazado', rejection_reason: 'Precio demasiado elevado para el volumen solicitado', comments: '' },
    { id: 5, reference: 'PR-2024-093', client_request_id: 5, clientName: 'Maquinaria Industrial Norte', incoterm: 'DAP', origin_port: 'Puerto de Hamburgo', destination_port: 'Puerto de Bilbao', container_type: "40' Standard", price: 5600, valid_until: '18/04/2024', status: 'Borrador', rejection_reason: '', comments: '' },
    { id: 6, reference: 'PR-2024-094', client_request_id: 6, clientName: 'Farmacéutica del Sur S.A.', incoterm: 'CIP', origin_port: 'Puerto de Singapur', destination_port: 'Puerto de Algeciras', container_type: "20' Reefer", price: 15200, valid_until: '01/04/2024', status: 'Enviado', rejection_reason: '', comments: '' },
])

const activeFilter = ref('Todos')
const searchQuery = ref('')

const showApproveModal = ref(false)
const showRejectModal = ref(false)
const selectedPresupuesto = ref(null)
const successMessage = ref('')

const filteredPresupuestos = computed(() => {
    let result = presupuestos
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

function openApproveModal(p) {
    selectedPresupuesto.value = p
    showApproveModal.value = true
}

function openRejectModal(p) {
    selectedPresupuesto.value = p
    showRejectModal.value = true
}

function handleApprove() {
    if (selectedPresupuesto.value) {
        selectedPresupuesto.value.status = 'Aceptado'
        successMessage.value = `Presupuesto ${selectedPresupuesto.value.reference} aprobado correctamente.`
        showApproveModal.value = false
        selectedPresupuesto.value = null
        setTimeout(() => {
            successMessage.value = ''
        }, 3000)
    }
}

function handleReject(reason) {
    if (selectedPresupuesto.value) {
        selectedPresupuesto.value.status = 'Rechazado'
        selectedPresupuesto.value.rejection_reason = reason
        showRejectModal.value = false
        selectedPresupuesto.value = null
    }
}
</script>

<template>
    <div class="presupuestos">
        <div class="presupuestos-header">
            <h2 class="presupuestos-header-title">Presupuestos</h2>
            <p class="presupuestos-header-subtitle">Ofertas económicas enviadas a los clientes.</p>
        </div>

        <Transition name="success-banner">
            <div v-if="successMessage" class="presupuestos-success">
                {{ successMessage }}
            </div>
        </Transition>

        <PresupuestosStats />
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

.presupuestos-success {
    background: #10b981;
    color: #ffffff;
    padding: 14px 22px;
    border-radius: var(--card-radius);
    font-size: 14px;
    font-weight: 600;
}

.success-banner-enter-active,
.success-banner-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.success-banner-enter-from,
.success-banner-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
