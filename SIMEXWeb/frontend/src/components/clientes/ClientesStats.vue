<script setup>
/**
 * @component ClientesStats
 * @description Fila con dos KPIs en la pantalla de clientes: número de
 * empresas y usuarios totales. Delega el render en `StatCard`.
 *
 * @prop {number} [totalEmpresas=0]
 * @prop {number} [totalUsuarios=0]
 */
import StatCard from '@/components/dashboard/StatCard.vue'

const props = defineProps({
    totalEmpresas: { type: Number, default: 0 },
    totalUsuarios: { type: Number, default: 0 },
})
</script>

<template>
    <div class="clientes-stats">
        <StatCard title="Total Empresas" :value="String(totalEmpresas)" icon="offers" />
        <StatCard title="Total Usuarios" :value="String(totalUsuarios)" icon="offers" />
    </div>
</template>

<style scoped>
.clientes-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

@media (max-width: 1000px) {
    .clientes-stats {
        grid-template-columns: 1fr;
    }
}
</style>
