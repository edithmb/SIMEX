<script setup>
import { computed } from 'vue'
import StatCard from '@/components/dashboard/StatCard.vue'

const props = defineProps({
    solicitudes: { type: Array, default: () => [] },
})

const total = computed(() => props.solicitudes.length)
const sinOferta = computed(() => props.solicitudes.filter(s => !s.hasOffer).length)
const conOferta = computed(() => props.solicitudes.filter(s => s.hasOffer).length)
</script>

<template>
    <div class="solicitudes-stats">
        <StatCard title="Total Solicitudes" :value="String(total)" icon="offers" />
        <StatCard title="Pendientes de Presupuesto" :value="String(sinOferta)" icon="offers" />
        <StatCard title="Presupuestadas" :value="String(conOferta)" icon="check" />
    </div>
</template>

<style scoped>
.solicitudes-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .solicitudes-stats {
        grid-template-columns: 1fr;
    }
}
</style>
