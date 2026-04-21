<script setup>
/**
 * @component PresupuestosStats
 * @description KPIs del listado de presupuestos: total, aceptados,
 * enviados (pendientes) y valor agregado. El valor total se formatea de
 * forma compacta (`€1,2M`, `€350K`) a partir de €1.000.
 *
 * @prop {object[]} [presupuestos=[]] Presupuestos mapeados (ver `PresupuestosView.mapPresupuesto`).
 */
import { computed } from 'vue'
import StatCard from '@/components/dashboard/StatCard.vue'

const props = defineProps({
    presupuestos: { type: Array, default: () => [] },
})

const total = computed(() => props.presupuestos.length)
const aceptados = computed(() => props.presupuestos.filter(p => p.status === 'Aceptado').length)
const pendientes = computed(() => props.presupuestos.filter(p => p.status === 'Enviado').length)
/**
 * Suma total de los `price` formateada de forma compacta (`€…M`/`€…K`).
 *
 * @type {import('vue').ComputedRef<string>}
 */
const valorTotal = computed(() => {
    const sum = props.presupuestos.reduce((acc, p) => acc + Number(p.price || 0), 0)
    if (sum >= 1_000_000) return '€' + (sum / 1_000_000).toFixed(1) + 'M'
    if (sum >= 1_000) return '€' + (sum / 1_000).toFixed(1) + 'K'
    return '€' + sum.toLocaleString('es-ES')
})
</script>

<template>
    <div class="presupuestos-stats">
        <StatCard title="Total Presupuestos" :value="String(total)" icon="offers" />
        <StatCard title="Aceptados" :value="String(aceptados)" icon="check" />
        <StatCard title="Enviados (Pendientes)" :value="String(pendientes)" icon="offers" />
        <StatCard title="Valor Total" :value="valorTotal" icon="money" />
    </div>
</template>

<style scoped>
.presupuestos-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1200px) {
    .presupuestos-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .presupuestos-stats {
        grid-template-columns: 1fr;
    }
}
</style>
