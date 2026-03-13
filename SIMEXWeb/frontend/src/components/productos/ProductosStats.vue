<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
</script>

<template>
    <div class="productos-stats">
        <StatCard title="Total Productos" value="248" icon="offers" />
        <StatCard title="En Stock" value="189" icon="check" />
        <StatCard title="Bajo Stock" value="12" icon="offers" />
    </div>
</template>

<style scoped>
.productos-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .productos-stats {
        grid-template-columns: 1fr;
    }
}
</style>
