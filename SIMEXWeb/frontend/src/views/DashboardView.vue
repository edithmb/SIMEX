<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import StatCard from '@/components/dashboard/StatCard.vue'
import ShipmentVolumeChart from '@/components/dashboard/ShipmentVolumeChart.vue'
import TransportDistribution from '@/components/dashboard/TransportDistribution.vue'
import RecentOperations from '@/components/dashboard/RecentOperations.vue'
import RecentActivity from '@/components/dashboard/RecentActivity.vue'

const auth = useAuthStore()
const LARAVEL = import.meta.env.VITE_LARAVEL_API
const headers = { Authorization: `Bearer ${auth.token}` }

// KPI state
const enviosActivos = ref('—')
const ofertasPendientes = ref('—')
const operacionesCompletadas = ref('—')
const totalClientes = ref('—')

const completedStatuses = ['descarga', 'completed', 'completado']

onMounted(async () => {
    try {
        const [opsRes, offersRes, clientsRes] = await Promise.all([
            axios.get(LARAVEL + '/logistics-operations?per_page=1000', { headers }),
            axios.get(LARAVEL + '/commercial-offers?per_page=1000', { headers }),
            axios.get(LARAVEL + '/clients', { headers }),
        ])

        const ops = Array.isArray(opsRes.data) ? opsRes.data : (opsRes.data.data || [])
        const offers = Array.isArray(offersRes.data) ? offersRes.data : (offersRes.data.data || [])
        const clients = Array.isArray(clientsRes.data) ? clientsRes.data : []

        const activos = ops.filter((o) => !completedStatuses.includes(o.status)).length
        const completadas = ops.filter((o) => completedStatuses.includes(o.status)).length
        const pendientes = offers.filter((o) => o.status === 'draft').length

        enviosActivos.value = String(activos)
        operacionesCompletadas.value = String(completadas)
        ofertasPendientes.value = String(pendientes)
        totalClientes.value = String(clients.length)
    } catch (e) {
        console.error('Error al cargar KPIs del dashboard:', e)
    }
})
</script>

<template>
  <div class="dashboard">
    <!-- KPI Cards Row -->
    <div class="dashboard-stats">
      <StatCard
        title="Envíos Activos"
        :value="enviosActivos"
        icon="truck"
      />
      <StatCard
        title="Clientes"
        :value="totalClientes"
        icon="offers"
      />
      <StatCard
        title="Ofertas Pendientes"
        :value="ofertasPendientes"
        icon="offers"
      />
      <StatCard
        title="Operaciones Completadas"
        :value="operacionesCompletadas"
        icon="check"
      />
    </div>

    <!-- Charts Row -->
    <div class="dashboard-charts">
      <ShipmentVolumeChart class="dashboard-charts-volume" />
      <TransportDistribution class="dashboard-charts-distribution" />
    </div>

    <!-- Bottom Row: Operations + Activity -->
    <div class="dashboard-bottom">
      <RecentOperations class="dashboard-bottom-operations" />
      <RecentActivity class="dashboard-bottom-activity" />
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.dashboard-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.dashboard-charts {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
}

.dashboard-bottom {
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 20px;
}

/* Responsiveness */
@media (max-width: 1200px) {
  .dashboard-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .dashboard-charts {
    grid-template-columns: 1fr;
  }

  .dashboard-bottom {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .dashboard-stats {
    grid-template-columns: 1fr;
  }
}
</style>
