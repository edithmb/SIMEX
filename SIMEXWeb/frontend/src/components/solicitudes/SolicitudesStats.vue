<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="solicitudes-stats">
        <StatCard title="Total Solicitudes" value="124" icon="offers" />
        <StatCard title="Pendientes de Presupuesto" value="18" trend="+3" trend-label="vs mes anterior"
            trend-direction="down" icon="offers" />
        <StatCard title="Presupuestadas" value="95" icon="check" />
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
