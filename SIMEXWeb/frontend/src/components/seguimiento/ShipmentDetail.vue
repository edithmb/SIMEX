<script setup>
/**
 * @component ShipmentDetail
 * @description Panel derecho del seguimiento: detalle del envío activo
 * con estado, timeline de pasos Incoterm, fechas ETD/ETA/ATD/ATA y
 * datos clave de la oferta. Si el rol activo es admin, muestra botones
 * para avanzar el estado.
 *
 * @prop {object} shipment
 * @prop {string} [role='admin']
 *
 * @emits update-status  Con `(id, newStatus)`.
 */
import { computed } from 'vue'

const props = defineProps({
  shipment: { type: Object, required: true },
  role: { type: String, default: 'admin' },
})

defineEmits(['update-status'])

/**
 * Formatea una fecha a `d MMM yyyy` en español abreviado. Devuelve
 * `'Pendiente'` si el valor es falsy.
 *
 * @param {string|Date|null|undefined} dateStr
 * @returns {string}
 */
function formatDate(dateStr) {
  if (!dateStr) return 'Pendiente'
  const d = new Date(dateStr)
  const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
  return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`
}

/**
 * Opciones de los botones de estado (una por paso Incoterm). Todos
 * comparten estilo activo; el color por estado lo calcula el padre.
 *
 * @type {import('vue').ComputedRef<{value:string,label:string,color:string,textColor:string}[]>}
 */
const statusOptions = computed(() =>
  (props.shipment?.steps || []).map((name) => ({
    value: name,
    label: name,
    color: '#dbeafe',
    textColor: '#1a6fb5',
  })),
)
</script>

<template>
  <div class="detail" v-if="shipment">
    <!-- Header -->
    <div class="detail-header">
      <div class="detail-header-left">
        <h2 class="detail-ref">{{ shipment.ref }}</h2>
        <span
          class="detail-status"
          :style="{ background: shipment.statusColor, color: shipment.statusTextColor }"
        >{{ shipment.statusLabel }}</span>
      </div>
      <div class="detail-header-right">
        <span class="detail-incoterm" :style="{ background: shipment.incotermColor || '#1a6fb5' }">
          {{ shipment.incoterm }}
        </span>
      </div>
    </div>

    <!-- Admin Status Control -->
    <div v-if="role === 'admin' && statusOptions.length" class="detail-step-control">
      <span class="detail-step-label">Estado:</span>
      <div class="status-btn-row">
        <button
          v-for="opt in statusOptions"
          :key="opt.value"
          :class="['status-btn', { 'status-btn--active': shipment.status === opt.value }]"
          :style="shipment.status === opt.value
            ? { background: opt.color, color: opt.textColor, borderColor: opt.color }
            : {}"
          @click="$emit('update-status', shipment.id, opt.value)"
        >
          {{ opt.label }}
        </button>
      </div>
    </div>

    <!-- Client & Route -->
    <p class="detail-client">{{ shipment.client }}</p>
    <div class="detail-route">
      <span class="detail-route-flag">🏳️</span>
      <span>{{ shipment.routeFrom }}</span>
      <span class="detail-route-arrow">›</span>
      <span class="detail-route-flag">🏳️</span>
      <span>{{ shipment.routeTo }}</span>
    </div>

    <!-- Progress -->
    <div class="detail-progress">
      <div class="detail-progress-header">
        <span class="detail-progress-title">Progreso del Envío</span>
        <span class="detail-progress-pct">{{ shipment.progress }} %</span>
      </div>
      <div class="detail-progress-bar">
        <div
          class="detail-progress-fill"
          :style="{ width: shipment.progress + '%', background: shipment.progressColor || '#10b981' }"
        ></div>
      </div>
    </div>

    <!-- Body: Timeline + Data side by side -->
    <div class="detail-body">
      <!-- Left: Timeline -->
      <div class="detail-timeline-section">
        <h3 class="detail-section-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
          </svg>
          Seguimiento del Envío
        </h3>
        <div class="timeline">
          <div
            v-for="(step, idx) in shipment.timeline"
            :key="idx"
            :class="['timeline-step', `timeline-step--${step.state}`]"
          >
            <div class="timeline-dot-col">
              <div :class="['timeline-dot', `timeline-dot--${step.state}`]">
                <!-- Completed check -->
                <svg v-if="step.state === 'completed'" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12" />
                </svg>
                <!-- Active: inner dot -->
                <span v-else-if="step.state === 'active'" class="timeline-dot-inner"></span>
              </div>
              <div v-if="idx < shipment.timeline.length - 1" class="timeline-line"></div>
            </div>
            <div class="timeline-content">
              <div class="timeline-content-header">
                <span class="timeline-step-name">{{ step.name }}</span>
                <span v-if="step.date" class="timeline-step-date">{{ step.date }}</span>
                <span v-if="step.badge" class="timeline-step-badge" :style="{ background: step.badgeColor, color: step.badgeTextColor }">
                  {{ step.badge }}
                </span>
              </div>
              <p v-if="step.location" class="timeline-step-location">{{ step.location }}</p>
              <p v-if="step.detail" class="timeline-step-detail">{{ step.detail }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Data + Docs -->
      <div class="detail-data-section">
        <!-- Datos del Envío -->
        <div class="data-block">
          <h3 class="detail-section-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
              <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
              <line x1="12" y1="22.08" x2="12" y2="12" />
            </svg>
            Datos del Envío
          </h3>
          <div class="data-grid">
            <div v-for="item in shipment.data" :key="item.label" class="data-item">
              <span class="data-item-label">{{ item.label }}</span>
              <span class="data-item-value">{{ item.value }}</span>
            </div>
          </div>
        </div>

        <!-- Fechas del Envío -->
        <div class="data-block">
          <h3 class="detail-section-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Fechas del Envío
          </h3>
          <div class="data-grid">
            <div class="data-item">
              <span class="data-item-label">ETD (Salida Estimada)</span>
              <span class="data-item-value">{{ formatDate(shipment.etd) }}</span>
            </div>
            <div class="data-item">
              <span class="data-item-label">ETA (Llegada Estimada)</span>
              <span class="data-item-value">{{ formatDate(shipment.eta) }}</span>
            </div>
            <div class="data-item">
              <span class="data-item-label">ATD (Salida Real)</span>
              <span class="data-item-value">{{ formatDate(shipment.atd) }}</span>
            </div>
            <div class="data-item">
              <span class="data-item-label">ATA (Llegada Real)</span>
              <span class="data-item-value">{{ formatDate(shipment.ata) }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
.detail {
  background: var(--card-bg);
  border-radius: var(--card-radius);
  box-shadow: var(--card-shadow);
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* Header */
.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.detail-header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.detail-ref {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-primary);
}

.detail-status {
  padding: 3px 12px;
  border-radius: 6px;
  font-size: 11.5px;
  font-weight: 600;
}

.detail-header-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

.detail-transport-badge {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  background: var(--page-bg);
  border-radius: 8px;
  font-size: 12px;
  font-weight: 500;
  color: var(--text-secondary);
}

.detail-incoterm {
  padding: 4px 12px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  color: #ffffff;
}

/* Step Control */
.detail-step-control {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  background: var(--page-bg);
  border-radius: 8px;
}

.detail-step-label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-secondary);
  white-space: nowrap;
}

.status-btn-row {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.status-btn {
  padding: 5px 14px;
  border-radius: 8px;
  border: 1.5px solid var(--border-color);
  background: var(--card-bg);
  color: var(--text-secondary);
  font-size: 12.5px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s;
  font-family: var(--font-family);
}

.status-btn:hover:not(.status-btn--active) {
  border-color: #1a6fb5;
  color: #1a6fb5;
}

.status-btn--active {
  font-weight: 700;
  border-width: 2px;
}

/* Client & Route */
.detail-client {
  font-size: 14px;
  font-weight: 500;
  color: var(--text-primary);
}

.detail-route {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: var(--text-secondary);
}

.detail-route-flag {
  font-size: 16px;
}

.detail-route-arrow {
  font-size: 18px;
  color: var(--text-muted);
}

/* Progress */
.detail-progress {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.detail-progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.detail-progress-title {
  font-size: 12.5px;
  color: var(--text-secondary);
  font-weight: 500;
}

.detail-progress-pct {
  font-size: 13px;
  font-weight: 700;
  color: var(--accent-green);
}

.detail-progress-bar {
  height: 8px;
  background: #e5e7eb;
  border-radius: 999px;
  overflow: hidden;
}

.detail-progress-fill {
  height: 100%;
  border-radius: 999px;
}

/* Body: 2 columns */
.detail-body {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 28px;
  margin-top: 8px;
}

/* Section title */
.detail-section-title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 14px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 14px;
}

/* ═══ TIMELINE ═══ */
.timeline {
  display: flex;
  flex-direction: column;
}

.timeline-step {
  display: flex;
  gap: 12px;
}

.timeline-dot-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 24px;
  flex-shrink: 0;
}

.timeline-dot {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.timeline-dot--completed {
  background: #6b8e23;
}

.timeline-dot--active {
  background: #1a6fb5;
}

.timeline-dot-inner {
  width: 8px;
  height: 8px;
  background: #ffffff;
  border-radius: 50%;
}

.timeline-dot--pending {
  background: #d1d5db;
}

.timeline-line {
  width: 2px;
  flex: 1;
  min-height: 20px;
  background: #d1d5db;
}

.timeline-step--completed .timeline-line {
  background: #6b8e23;
}

.timeline-content {
  padding-bottom: 20px;
  flex: 1;
  min-width: 0;
}

.timeline-content-header {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.timeline-step-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-primary);
}

.timeline-step-date {
  font-size: 12px;
  color: var(--accent-green);
  font-weight: 500;
  margin-left: auto;
}

.timeline-step-badge {
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 10.5px;
  font-weight: 600;
}

.timeline-step-location {
  font-size: 12px;
  color: var(--text-secondary);
  margin-top: 2px;
}

.timeline-step-detail {
  font-size: 11.5px;
  color: var(--text-muted);
  margin-top: 4px;
  padding: 6px 10px;
  background: var(--page-bg);
  border-radius: 6px;
}

/* ═══ DATA GRID ═══ */
.data-block {
  margin-bottom: 20px;
}

.data-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.data-item {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.data-item-label {
  font-size: 11.5px;
  color: var(--text-muted);
  font-weight: 500;
}

.data-item-value {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-primary);
}

/* ═══ DOCS LIST ═══ */
.docs-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.doc-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background: var(--page-bg);
  border-radius: 8px;
}

.doc-item-name {
  font-size: 13px;
  color: var(--text-primary);
  font-weight: 500;
}

.doc-item-status {
  display: flex;
  align-items: center;
}

.doc-item-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.doc-upload-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  border-radius: 6px;
  background: var(--page-bg);
  border: 1px solid var(--border-color);
  color: var(--text-secondary);
  cursor: pointer;
  transition: all 0.15s;
}

.doc-upload-btn:hover {
  border-color: #1a6fb5;
  color: #1a6fb5;
  background: #eff6ff;
}
</style>
