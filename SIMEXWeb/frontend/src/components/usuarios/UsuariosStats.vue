<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="usuarios-stats">
        <StatCard title="Total Usuarios" value="45" icon="offers" />
        <StatCard title="Administradores" value="3" icon="check" />
        <StatCard title="Operadores Activos" value="12" icon="offers" />
    </div>
</template>

<style scoped>
.usuarios-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .usuarios-stats {
        grid-template-columns: 1fr;
    }
}
</style>
