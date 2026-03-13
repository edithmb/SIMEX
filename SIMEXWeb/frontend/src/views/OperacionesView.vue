<script setup>
import { ref, computed } from 'vue'
import OperacionesStats from '@/components/operaciones/OperacionesStats.vue'
import OperacionesFilters from '@/components/operaciones/OperacionesFilters.vue'
import OperacionesTable from '@/components/operaciones/OperacionesTable.vue'
import NuevaOperacionModal from '@/components/operaciones/NuevaOperacionModal.vue'

const operaciones = [
    { ref: 'OP-2024-158', oferta: 'OF-2024-089', client: 'Textiles Mediterráneo S.A.', routeFrom: 'Shanghai', routeTo: 'Valencia', transport: 'ship', incoterm: 'CIF', status: 'En Tránsito', inicio: '15/01/2024', entrega: '28/02/2024' },
    { ref: 'OP-2024-159', oferta: 'OF-2024-090', client: 'Importaciones García S.L.', routeFrom: 'Miami', routeTo: 'Madrid', transport: 'plane', incoterm: 'EXW', status: 'Preparación', inicio: '20/01/2024', entrega: '25/01/2024' },
    { ref: 'OP-2024-160', oferta: 'OF-2024-085', client: 'Alimentación Ibérica S.L.', routeFrom: 'Rotterdam', routeTo: 'Barcelona', transport: 'truck', incoterm: 'DDP', status: 'En Aduana', inicio: '10/01/2024', entrega: '22/01/2024' },
    { ref: 'OP-2024-161', oferta: 'OF-2024-082', client: 'Electrónica Levante S.A.', routeFrom: 'Shenzhen', routeTo: 'Bilbao', transport: 'ship', incoterm: 'FOB', status: 'Entregado', inicio: '05/12/2023', entrega: '18/01/2024' },
    { ref: 'OP-2024-162', oferta: 'OF-2024-093', client: 'Maquinaria Industrial Norte', routeFrom: 'Frankfurt', routeTo: 'Zaragoza', transport: 'truck', incoterm: 'DAP', status: 'En Tránsito', inicio: '18/01/2024', entrega: '21/01/2024' },
    { ref: 'OP-2024-163', oferta: 'OF-2024-078', client: 'Farmacéutica del Sur S.A.', routeFrom: 'Mumbai', routeTo: 'Sevilla', transport: 'plane', incoterm: 'CIP', status: 'Incidencia', inicio: '12/01/2024', entrega: '16/01/2024' },
    { ref: 'OP-2024-164', oferta: 'OF-2024-095', client: 'Vinos y Licores Rioja S.L.', routeFrom: 'Logroño', routeTo: 'New York', transport: 'ship', incoterm: 'FCA', status: 'Preparación', inicio: '22/01/2024', entrega: '15/02/2024' },
    { ref: 'OP-2024-165', oferta: 'OF-2024-088', client: 'Autopartes Castilla S.A.', routeFrom: 'Stuttgart', routeTo: 'Valladolid', transport: 'truck', incoterm: 'CPT', status: 'Entregado', inicio: '08/01/2024', entrega: '12/01/2024' },
]

const activeTransport = ref('Todos')
const activeStatus = ref('Todos los Estados')
const searchQuery = ref('')

const filteredOperaciones = computed(() => {
    let result = operaciones
    if (activeTransport.value !== 'Todos') {
        result = result.filter((o) => o.transport === activeTransport.value)
    }
    if (activeStatus.value !== 'Todos los Estados') {
        result = result.filter((o) => o.status === activeStatus.value)
    }
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(
            (o) => o.ref.toLowerCase().includes(q) || o.client.toLowerCase().includes(q),
        )
    }
    return result
})

const showModal = ref(false)

function handleSubmit(data) {
    console.log('Nueva operación:', data)
    showModal.value = false
}
</script>

<template>
    <div class="operaciones">
        <div class="operaciones-header">
            <div class="operaciones-header-left">
                <h2 class="operaciones-header-title">Operaciones Logísticas</h2>
            </div>
            <button class="operaciones-header-btn" @click="showModal = true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Nueva Operación
            </button>
        </div>
        <OperacionesStats />
        <OperacionesFilters :active-transport="activeTransport" :active-status="activeStatus"
            :search-query="searchQuery" @update:active-transport="activeTransport = $event"
            @update:active-status="activeStatus = $event" @update:search-query="searchQuery = $event" />
        <OperacionesTable :operaciones="filteredOperaciones" />

        <NuevaOperacionModal :visible="showModal" @close="showModal = false" @submit="handleSubmit" />
    </div>
</template>

<style scoped>
.operaciones {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.operaciones-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.operaciones-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.operaciones-header-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: var(--sidebar-bg);
    color: #ffffff;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    transition: background 0.15s ease;
}

.operaciones-header-btn:hover {
    background: #0d2440;
}
</style>
