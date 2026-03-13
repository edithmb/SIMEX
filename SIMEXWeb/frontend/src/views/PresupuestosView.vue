<script setup>
import { ref, computed } from 'vue'
import PresupuestosStats from '@/components/presupuestos/PresupuestosStats.vue'
import PresupuestosFilters from '@/components/presupuestos/PresupuestosFilters.vue'
import PresupuestosTable from '@/components/presupuestos/PresupuestosTable.vue'

const presupuestos = [
    { ref: 'PR-2024-089', solicitud: 'SOL-2024-115', client: 'Textiles Mediterráneo S.A.', routeFrom: 'Shanghai', routeTo: 'Valencia', incoterm: 'CIF', transport: 'ship', value: '€12,450', status: 'Caducado', validity: '15/02/2024' },
    { ref: 'PR-2024-090', solicitud: 'SOL-2024-121', client: 'Importaciones García S.L.', routeFrom: 'Miami', routeTo: 'Madrid', incoterm: 'EXW', transport: 'plane', value: '€4,200', status: 'Enviado', validity: '20/02/2024' },
    { ref: 'PR-2024-091', solicitud: 'SOL-2024-118', client: 'Alimentación Ibérica S.L.', routeFrom: 'Rotterdam', routeTo: 'Barcelona', incoterm: 'DDP', transport: 'truck', value: '€3,800', status: 'Caducado', validity: '25/02/2024' },
    { ref: 'PR-2024-092', solicitud: 'SOL-2024-112', client: 'Electrónica Levante S.A.', routeFrom: 'Shenzhen', routeTo: 'Bilbao', incoterm: 'FOB', transport: 'ship', value: '€28,500', status: 'Rechazado', validity: '10/02/2024' },
    { ref: 'PR-2024-093', solicitud: 'SOL-2024-124', client: 'Maquinaria Industrial Norte', routeFrom: 'Frankfurt', routeTo: 'Zaragoza', incoterm: 'DAP', transport: 'truck', value: '€5,600', status: 'Aceptado', validity: '18/02/2024' },
    { ref: 'PR-2024-094', solicitud: 'SOL-2024-105', client: 'Farmacéutica del Sur S.A.', routeFrom: 'Mumbai', routeTo: 'Sevilla', incoterm: 'CIP', transport: 'plane', value: '€15,200', status: 'Caducado', validity: '01/02/2024' },
]

const activeFilter = ref('Todos')
const searchQuery = ref('')

const filteredPresupuestos = computed(() => {
    let result = presupuestos
    if (activeFilter.value !== 'Todos') {
        result = result.filter((p) => p.status === activeFilter.value)
    }
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(
            (p) => p.ref.toLowerCase().includes(q) || p.client.toLowerCase().includes(q),
        )
    }
    return result
})
</script>

<template>
    <div class="presupuestos">
        <div class="presupuestos-header">
            <h2 class="presupuestos-header-title">Presupuestos</h2>
            <p class="presupuestos-header-subtitle">Ofertas económicas enviadas a los clientes.</p>
        </div>
        <PresupuestosStats />
        <PresupuestosFilters :active-filter="activeFilter" :search-query="searchQuery"
            @update:active-filter="activeFilter = $event" @update:search-query="searchQuery = $event" />
        <PresupuestosTable :presupuestos="filteredPresupuestos" />
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
