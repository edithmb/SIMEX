<script setup>
import { shallowRef } from 'vue'

const props = defineProps({
  shipments: { type: Array, required: true },
  selectedId: { type: String, default: '' },
})

const emit = defineEmits(['select'])
</script>

<template>
  <div class="shipment-list">
    <div class="shipment-list-header">
      <h2 class="shipment-list-title">Envíos Activos</h2>
      <p class="shipment-list-count">{{ shipments.length }} envíos encontrados</p>
    </div>

    <div class="shipment-list-items">
      <div
        v-for="s in shipments"
        :key="s.id"
        :class="['shipment-card', { 'shipment-card--selected': selectedId === s.id }]"
        @click="emit('select', s.id)"
      >
        <div class="shipment-card-header">
          <span class="shipment-card-ref">{{ s.ref }}</span>
          <span class="shipment-card-transport-icon">
            <!-- Truck -->
            <svg v-if="s.transport === 'truck'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="3" width="15" height="13" rx="1" />
              <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
              <circle cx="5.5" cy="18.5" r="2.5" />
              <circle cx="18.5" cy="18.5" r="2.5" />
            </svg>
            <!-- Ship -->
            <svg v-else-if="s.transport === 'ship'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
              <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
              <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
              <line x1="12" y1="1" x2="12" y2="5" />
            </svg>
            <!-- Plane -->
            <svg v-else-if="s.transport === 'plane'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
            </svg>
          </span>
        </div>
        <p class="shipment-card-client">{{ s.client }}</p>
        <p class="shipment-card-route">{{ s.routeFrom }} → {{ s.routeTo }}</p>
        <div class="shipment-card-tags">
          <span
            v-if="s.incoterm"
            class="shipment-card-incoterm"
            :style="{ background: s.incotermColor || '#1a6fb5' }"
          >{{ s.incoterm }}</span>
          <span
            class="shipment-card-status"
            :style="{ background: s.statusColor, color: s.statusTextColor }"
          >{{ s.statusLabel }}</span>
        </div>
        <div class="shipment-card-progress">
          <div class="shipment-card-progress-bar">
            <div
              class="shipment-card-progress-fill"
              :style="{ width: s.progress + '%', background: s.progressColor || '#10b981' }"
            ></div>
          </div>
          <span class="shipment-card-progress-label">{{ s.progress }} % completado</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.shipment-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.shipment-list-header {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.shipment-list-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
}

.shipment-list-count {
  font-size: 12.5px;
  color: var(--text-secondary);
}

.shipment-list-items {
  display: flex;
  flex-direction: column;
  gap: 12px;
  overflow-y: auto;
  max-height: calc(100vh - 300px);
  padding-right: 4px;
}

/* Card */
.shipment-card {
  background: var(--card-bg);
  border: 2px solid var(--border-color);
  border-radius: 10px;
  padding: 16px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.shipment-card:hover {
  border-color: var(--accent-blue);
}

.shipment-card--selected {
  border-color: var(--accent-blue);
  box-shadow: 0 0 0 1px var(--accent-blue);
}

.shipment-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.shipment-card-ref {
  font-size: 14px;
  font-weight: 700;
  color: var(--accent-blue);
}

.shipment-card-transport-icon {
  color: var(--text-secondary);
}

.shipment-card-client {
  font-size: 13px;
  font-weight: 500;
  color: var(--text-primary);
}

.shipment-card-route {
  font-size: 12px;
  color: var(--text-secondary);
}

.shipment-card-tags {
  display: flex;
  gap: 6px;
  margin-top: 4px;
}

.shipment-card-incoterm {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 999px;
  font-size: 10.5px;
  font-weight: 700;
  color: #ffffff;
}

.shipment-card-status {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 6px;
  font-size: 10.5px;
  font-weight: 600;
}

/* Progress */
.shipment-card-progress {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 6px;
}

.shipment-card-progress-bar {
  flex: 1;
  height: 6px;
  background: #e5e7eb;
  border-radius: 999px;
  overflow: hidden;
}

.shipment-card-progress-fill {
  height: 100%;
  border-radius: 999px;
}

.shipment-card-progress-label {
  font-size: 11px;
  color: var(--text-muted);
  white-space: nowrap;
}
</style>
