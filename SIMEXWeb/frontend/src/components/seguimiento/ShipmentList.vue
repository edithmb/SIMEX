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
