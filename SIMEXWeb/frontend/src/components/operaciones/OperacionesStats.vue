<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="operaciones-stats">
        <StatCard title="Operaciones Activas" value="32" icon="truck" />
        <StatCard title="En Tránsito" value="18" icon="truck" />
        <StatCard title="En Aduana" value="7" icon="offers" />
        <StatCard title="Completadas Este Mes" value="24" trend="+4" trend-label="vs mes anterior" trend-direction="up"
            icon="check" />
    </div>
</template>

<style scoped>
.operaciones-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1200px) {
    .operaciones-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .operaciones-stats {
        grid-template-columns: 1fr;
    }
}
</style>
