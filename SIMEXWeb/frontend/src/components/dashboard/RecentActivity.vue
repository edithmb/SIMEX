<script setup>
const activities = [
  {
    id: 1,
    icon: 'offer',
    iconColor: '#1a6fb5',
    iconBg: '#dbeafe',
    text: 'Oferta #OF-2024-089 aceptada por Textiles Mediterráneo',
    time: 'Hace 15 min',
  },
  {
    id: 2,
    icon: 'shipment',
    iconColor: '#047857',
    iconBg: '#d1fae5',
    text: 'Envío #ENV-4521 llegó a Puerto de Barcelona',
    time: 'Hace 1 hora',
  },
  {
    id: 3,
    icon: 'document',
    iconColor: '#7c3aed',
    iconBg: '#ede9fe',
    text: 'Documento BL subido para operación #OP-2024-156',
    time: 'Hace 2 horas',
  },
  {
    id: 4,
    icon: 'incident',
    iconColor: '#b91c1c',
    iconBg: '#fee2e2',
    text: 'Incidencia reportada en envío -451',
    time: 'Hace 3 horas',
  },
  {
    id: 5,
    icon: 'new',
    iconColor: '#047857',
    iconBg: '#d1fae5',
    text: 'Nueva oferta creada para Alimentación Ibérica',
    time: 'Hace 4 horas',
  },
]
</script>

<template>
  <div class="activity-card">
    <h2 class="activity-card-title">Actividad Reciente</h2>
    <div class="activity-list">
      <div
        v-for="item in activities"
        :key="item.id"
        class="activity-item"
      >
        <div
          class="activity-item-icon"
          :style="{ background: item.iconBg, color: item.iconColor }"
        >
          <!-- Offer -->
          <svg v-if="item.icon === 'offer'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
          <!-- Shipment -->
          <svg v-else-if="item.icon === 'shipment'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13" rx="1" />
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
            <circle cx="5.5" cy="18.5" r="2.5" />
            <circle cx="18.5" cy="18.5" r="2.5" />
          </svg>
          <!-- Document -->
          <svg v-else-if="item.icon === 'document'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
          </svg>
          <!-- Incident -->
          <svg v-else-if="item.icon === 'incident'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
          </svg>
          <!-- New -->
          <svg v-else-if="item.icon === 'new'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="16" />
            <line x1="8" y1="12" x2="16" y2="12" />
          </svg>
        </div>
        <div class="activity-item-content">
          <p class="activity-item-text">{{ item.text }}</p>
          <span class="activity-item-time">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
            {{ item.time }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.activity-card {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 22px 24px;
}

.activity-card-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 16px;
}

.activity-list {
  display: flex;
  flex-direction: column;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.activity-item:first-child {
  padding-top: 0;
}

.activity-item-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.activity-item-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.activity-item-text {
  font-size: 13px;
  color: var(--text-primary);
  line-height: 1.4;
  font-weight: 500;
}

.activity-item-time {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 11.5px;
  color: var(--text-muted);
}
</style>
