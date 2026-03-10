<script setup>
import { onMounted, shallowRef } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const canvasRef = shallowRef(null)

onMounted(() => {
  const ctx = canvasRef.value.getContext('2d')

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Marítimo', 'Aéreo', 'Terrestre'],
      datasets: [
        {
          data: [60, 25, 15],
          backgroundColor: ['#1a3a5c', '#8b9e3a', '#c4d46a'],
          borderWidth: 0,
          hoverOffset: 6,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a2332',
          titleFont: { family: "'Source Sans 3', sans-serif", size: 12 },
          bodyFont: { family: "'Source Sans 3', sans-serif", size: 13, weight: 600 },
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: (ctx) => `${ctx.label}: ${ctx.parsed}%`,
          },
        },
      },
    },
  })
})
</script>

<template>
  <div class="donut-card">
    <h2 class="donut-card-title">Distribución por Transporte</h2>
    <div class="donut-card-body">
      <div class="donut-card-chart-wrapper">
        <canvas ref="canvasRef"></canvas>
        <div class="donut-card-center-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1a6fb5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
        </div>
      </div>
    </div>
    <div class="donut-card-legend">
      <div class="donut-card-legend-item">
        <span class="donut-card-legend-dot donut-card-legend-dot--maritimo"></span>
        <span class="donut-card-legend-label">Marítimo</span>
      </div>
      <div class="donut-card-legend-item">
        <span class="donut-card-legend-dot donut-card-legend-dot--aereo"></span>
        <span class="donut-card-legend-label">Aéreo</span>
      </div>
      <div class="donut-card-legend-item">
        <span class="donut-card-legend-dot donut-card-legend-dot--terrestre"></span>
        <span class="donut-card-legend-label">Terrestre</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.donut-card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 22px 24px;
  display: flex;
  flex-direction: column;
}

.donut-card-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 12px;
}

.donut-card-body {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.donut-card-chart-wrapper {
  position: relative;
  width: 200px;
  height: 200px;
}

.donut-card-center-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 48px;
  height: 48px;
  background: rgba(26, 111, 181, 0.08);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.donut-card-legend {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 16px;
}

.donut-card-legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
}

.donut-card-legend-dot {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  flex-shrink: 0;
}

.donut-card-legend-dot--maritimo {
  background: #1a3a5c;
}

.donut-card-legend-dot--aereo {
  background: #8b9e3a;
}

.donut-card-legend-dot--terrestre {
  background: #c4d46a;
}

.donut-card-legend-label {
  font-size: 12px;
  color: var(--text-secondary);
  font-weight: 500;
}
</style>
