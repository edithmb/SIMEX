<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="clientes-stats">
        <StatCard title="Total Empresas" value="8" icon="offers" />
        <StatCard title="Total Contactos" value="12" trend="+2" trend-label="vs mes anterior" trend-direction="up"
            icon="offers" />
        <StatCard title="Operaciones en Curso" value="47" icon="money" />
    </div>
</template>

<style scoped>
.clientes-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .clientes-stats {
        grid-template-columns: 1fr;
    }
}
</style>
