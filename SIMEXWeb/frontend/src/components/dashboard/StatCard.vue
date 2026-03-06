<script setup>
const props = defineProps({
  title: { type: String, required: true },
  value: { type: String, required: true },
  trend: { type: String, default: '' },
  trendLabel: { type: String, default: 'vs mes anterior' },
  trendDirection: { type: String, default: 'up' },
  icon: { type: String, default: 'box' },
})
</script>

<template>
  <div class="stat-card">
    <div class="stat-card-body">
      <div class="stat-card-content">
        <span class="stat-card-title">{{ title }}</span>
        <span class="stat-card-value">{{ value }}</span>
      </div>
      <div class="stat-card-icon">
        <!-- Truck / Envíos -->
        <svg v-if="icon === 'truck'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="3" width="15" height="13" rx="1" />
          <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
          <circle cx="5.5" cy="18.5" r="2.5" />
          <circle cx="18.5" cy="18.5" r="2.5" />
        </svg>
        <!-- Money / Ingresos -->
        <svg v-else-if="icon === 'money'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
          <polyline points="17 6 23 6 23 12" />
        </svg>
        <!-- Offers / Ofertas -->
        <svg v-else-if="icon === 'offers'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
          <polyline points="14 2 14 8 20 8" />
          <line x1="16" y1="13" x2="8" y2="13" />
          <line x1="16" y1="17" x2="8" y2="17" />
        </svg>
        <!-- Check / Completadas -->
        <svg v-else-if="icon === 'check'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
          <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
      </div>
    </div>
    <div v-if="trend" class="stat-card-trend">
      <span :class="['stat-card-trend-value', `stat-card-trend-value--${trendDirection}`]">
        <svg v-if="trendDirection === 'up'" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
        </svg>
        <svg v-else width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
        </svg>
        {{ trend }}
      </span>
      <span class="stat-card-trend-label">{{ trendLabel }}</span>
    </div>
  </div>
</template>

<style scoped>
.stat-card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 20px 22px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.stat-card-body {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.stat-card-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-card-title {
  font-size: 12.5px;
  color: var(--text-secondary);
  font-weight: 500;
}

.stat-card-value {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-primary);
  line-height: 1.1;
}

.stat-card-icon {
  width: 42px;
  height: 42px;
  background: var(--page-bg);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-secondary);
  flex-shrink: 0;
}

.stat-card-trend {
  display: flex;
  align-items: center;
  gap: 6px;
}

.stat-card-trend-value {
  display: flex;
  align-items: center;
  gap: 3px;
  font-size: 12px;
  font-weight: 600;
}

.stat-card-trend-value--up {
  color: var(--accent-green);
}

.stat-card-trend-value--down {
  color: var(--accent-red);
}

.stat-card-trend-label {
  font-size: 11.5px;
  color: var(--text-muted);
}
</style>
