<script setup>
import StatCard from '@/components/dashboard/StatCard.vue'
import ShipmentVolumeChart from '@/components/dashboard/ShipmentVolumeChart.vue'
import TransportDistribution from '@/components/dashboard/TransportDistribution.vue'
import RecentOperations from '@/components/dashboard/RecentOperations.vue'
import RecentActivity from '@/components/dashboard/RecentActivity.vue'
</script>

<template>
  <div class="dashboard">
    <!-- KPI Cards Row -->
    <div class="dashboard-stats">
      <StatCard
        title="Envíos Activos"
        value="47"
        trend="+12.5%"
        trend-label="vs mes anterior"
        trend-direction="up"
        icon="truck"
      />
      <StatCard
        title="Ingresos Totales"
        value="€2,847,350"
        trend="+8.3%"
        trend-label="vs mes anterior"
        trend-direction="up"
        icon="money"
      />
      <StatCard
        title="Ofertas Pendientes"
        value="23"
        trend="-5.2%"
        trend-label="vs mes anterior"
        trend-direction="down"
        icon="offers"
      />
      <StatCard
        title="Operaciones Completadas"
        value="156"
        trend="+15.7%"
        trend-label="vs mes anterior"
        trend-direction="up"
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
