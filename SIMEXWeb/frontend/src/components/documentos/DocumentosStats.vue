<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="documentos-stats">
        <StatCard title="Total Documentos" value="1,248" icon="offers" />
        <StatCard title="Pendientes de Revisión" value="15" icon="offers" />
        <StatCard title="Almacenamiento Usado" value="4.2 GB" icon="offers" />
    </div>
</template>

<style scoped>
.documentos-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .documentos-stats {
        grid-template-columns: 1fr;
    }
}
</style>
