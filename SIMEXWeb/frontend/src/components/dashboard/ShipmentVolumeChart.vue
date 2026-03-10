<script setup>
import { onMounted, shallowRef } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const canvasRef = shallowRef(null)

onMounted(() => {
  const ctx = canvasRef.value.getContext('2d')

  const gradient = ctx.createLinearGradient(0, 0, 0, 300)
  gradient.addColorStop(0, 'rgba(26, 111, 181, 0.25)')
  gradient.addColorStop(1, 'rgba(26, 111, 181, 0.02)')

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      datasets: [
        {
          label: 'Envíos',
          data: [65, 78, 90, 105, 95, 110, 120, 115, 130, 145, 160, 195],
          borderColor: '#1a6fb5',
          backgroundColor: gradient,
          borderWidth: 2.5,
          fill: true,
          tension: 0.4,
          pointRadius: 0,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: '#1a6fb5',
          pointHoverBorderColor: '#fff',
          pointHoverBorderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      interaction: {
        intersect: false,
        mode: 'index',
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a2332',
          titleFont: { family: "'Source Sans 3', sans-serif", size: 12 },
          bodyFont: { family: "'Source Sans 3', sans-serif", size: 13, weight: 600 },
          padding: 10,
          cornerRadius: 8,
          displayColors: false,
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: {
            font: { family: "'Source Sans 3', sans-serif", size: 11 },
            color: '#9ca3af',
          },
          border: { display: false },
        },
        y: {
          min: 0,
          max: 280,
          ticks: {
            stepSize: 65,
            font: { family: "'Source Sans 3', sans-serif", size: 11 },
            color: '#9ca3af',
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.04)',
          },
          border: { display: false },
        },
      },
    },
  })
})
</script>

<template>
  <div class="chart-card">
    <h2 class="chart-card-title">Volumen de Envíos</h2>
    <div class="chart-card-body">
      <canvas ref="canvasRef"></canvas>
    </div>
  </div>
</template>

<style scoped>
.chart-card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 22px 24px;
  display: flex;
  flex-direction: column;
}

.chart-card-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 16px;
}

.chart-card-body {
  flex: 1;
  position: relative;
  min-height: 260px;
}
</style>
