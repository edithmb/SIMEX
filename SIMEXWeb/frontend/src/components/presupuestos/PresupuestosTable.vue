<script setup>
const props = defineProps({
    presupuestos: { type: Array, required: true },
    role: { type: String, default: 'admin' },
})

const emit = defineEmits(['aprobar', 'rechazar'])

const incotermColors = {
    CIF: { bg: '#dbeafe', color: '#1a6fb5' },
    EXW: { bg: '#fef3c7', color: '#b45309' },
    DDP: { bg: '#d1fae5', color: '#047857' },
    FOB: { bg: '#ede9fe', color: '#6d28d9' },
    DAP: { bg: '#fce7f3', color: '#be185d' },
    CIP: { bg: '#e0e7ff', color: '#4338ca' },
    CFR: { bg: '#f0fdf4', color: '#15803d' },
    CPT: { bg: '#fdf4ff', color: '#a21caf' },
    DPU: { bg: '#fff7ed', color: '#c2410c' },
}

const statusColors = {
    Borrador: { bg: '#e5e7eb', color: '#6b7280' },
    Enviado: { bg: '#dbeafe', color: '#1a6fb5' },
    Aceptado: { bg: '#d1fae5', color: '#047857' },
    Rechazado: { bg: '#fee2e2', color: '#dc2626' },
}

function getIncotermStyle(incoterm) {
    return incotermColors[incoterm] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getStatusStyle(status) {
    return statusColors[status] || { bg: '#e5e7eb', color: '#6b7280' }
}

function formatPrice(price) {
    return '€' + price.toLocaleString('es-ES')
}
</script>

<template>
    <div class="presupuestos-table-wrapper">
        <table class="presupuestos-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Solicitud</th>
                    <th>Cliente</th>
                    <th>Incoterm</th>
                    <th>Puerto Origen</th>
                    <th>Puerto Destino</th>
                    <th>Contenedor</th>
                    <th>Precio</th>
                    <th>Validez</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in presupuestos" :key="p.id">
                    <td><span class="pres-ref">{{ p.reference }}</span></td>
                    <td><span class="pres-sol">SOL-2024-{{ String(p.client_request_id).padStart(3, '0') }}</span></td>
                    <td><span class="pres-client">{{ p.clientName }}</span></td>
                    <td>
                        <span class="pres-incoterm"
                            :style="{ background: getIncotermStyle(p.incoterm).bg, color: getIncotermStyle(p.incoterm).color }">
                            {{ p.incoterm }}
                        </span>
                    </td>
                    <td><span class="pres-port">{{ p.origin_port }}</span></td>
                    <td><span class="pres-port">{{ p.destination_port }}</span></td>
                    <td><span class="pres-container">{{ p.container_type }}</span></td>
                    <td><span class="pres-value">{{ formatPrice(p.price) }}</span></td>
                    <td><span class="pres-validity">{{ p.valid_until }}</span></td>
                    <td>
                        <span class="pres-status"
                            :style="{ background: getStatusStyle(p.status).bg, color: getStatusStyle(p.status).color }">
                            {{ p.status }}
                        </span>
                    </td>
                    <td>
                        <div v-if="role === 'cliente' && p.status === 'Enviado'" class="pres-actions">
                            <button class="pres-action-btn pres-action-btn--approve" @click="emit('aprobar', p)">
                                Aprobar
                            </button>
                            <button class="pres-action-btn pres-action-btn--reject" @click="emit('rechazar', p)">
                                Rechazar
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="presupuestos.length === 0">
                    <td colspan="11" class="pres-empty">No se encontraron presupuestos con los filtros aplicados.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.presupuestos-table-wrapper {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.presupuestos-table {
    width: 100%;
    border-collapse: collapse;
}

.presupuestos-table thead th {
    text-align: left;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 14px 14px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.presupuestos-table tbody tr {
    transition: background 0.12s ease;
}

.presupuestos-table tbody tr:hover {
    background: #f8f9fb;
}

.presupuestos-table tbody td {
    padding: 14px 14px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13.5px;
    vertical-align: middle;
}

.presupuestos-table tbody tr:last-child td {
    border-bottom: none;
}

.pres-ref {
    font-size: 12px;
    font-weight: 600;
    color: var(--accent-blue);
    line-height: 1.5;
    word-break: break-all;
}

.pres-sol {
    font-size: 12.5px;
    color: var(--text-secondary);
}

.pres-client {
    font-weight: 600;
    color: var(--text-primary);
}

.pres-incoterm {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}

.pres-port {
    color: var(--text-secondary);
    font-size: 13px;
    white-space: nowrap;
}

.pres-container {
    color: var(--text-secondary);
    font-size: 13px;
    white-space: nowrap;
}

.pres-value {
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
}

.pres-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.pres-validity {
    color: var(--text-primary);
    font-size: 13px;
    white-space: nowrap;
}

.pres-empty {
    text-align: center;
    padding: 40px 14px !important;
    color: var(--text-muted);
    font-size: 14px;
}

.pres-actions {
    display: flex;
    gap: 8px;
}

.pres-action-btn {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.pres-action-btn--approve {
    background: #d1fae5;
    color: #047857;
}

.pres-action-btn--approve:hover {
    background: #047857;
    color: #ffffff;
}

.pres-action-btn--reject {
    background: #fee2e2;
    color: #dc2626;
}

.pres-action-btn--reject:hover {
    background: #dc2626;
    color: #ffffff;
}
</style>
