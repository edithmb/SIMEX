<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="presupuestos-stats">
        <StatCard title="Total Presupuestos" value="89" icon="offers" />
        <StatCard title="Aceptados" value="45" trend="+12%" trend-label="vs mes anterior" trend-direction="up"
            icon="check" />
        <StatCard title="Enviados (Pendientes)" value="23" icon="offers" />
        <StatCard title="Valor Total" value="€4.2M" trend="+5.4%" trend-label="vs mes anterior" trend-direction="up"
            icon="money" />
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
