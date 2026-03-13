<script setup>
defineProps({
    operaciones: { type: Array, required: true },
})

const incotermColors = {
    CIF: { bg: '#dbeafe', color: '#1a6fb5' },
    EXW: { bg: '#fef3c7', color: '#b45309' },
    DDP: { bg: '#d1fae5', color: '#047857' },
    FOB: { bg: '#ede9fe', color: '#6d28d9' },
    DAP: { bg: '#fce7f3', color: '#be185d' },
    CIP: { bg: '#e0e7ff', color: '#4338ca' },
    FCA: { bg: '#ccfbf1', color: '#0f766e' },
    CPT: { bg: '#fef9c3', color: '#a16207' },
}

const statusColors = {
    'En Tránsito': { bg: '#dbeafe', color: '#1a6fb5' },
    'Preparación': { bg: '#e5e7eb', color: '#6b7280' },
    'En Aduana': { bg: '#fef3c7', color: '#b45309' },
    'Entregado': { bg: '#d1fae5', color: '#047857' },
    'Incidencia': { bg: '#fee2e2', color: '#dc2626' },
}

function getIncotermStyle(incoterm) {
    return incotermColors[incoterm] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getStatusStyle(status) {
    return statusColors[status] || { bg: '#e5e7eb', color: '#6b7280' }
}
</script>

<template>
    <div class="operaciones-table-wrapper">
        <table class="operaciones-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Oferta</th>
                    <th>Cliente</th>
                    <th>Ruta</th>
                    <th>Modo</th>
                    <th>Incoterm</th>
                    <th>Estado</th>
                    <th>Inicio</th>
                    <th>Est. Entrega</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="op in operaciones" :key="op.ref">
                    <td><span class="op-ref">{{ op.ref }}</span></td>
                    <td><span class="op-oferta">{{ op.oferta }}</span></td>
                    <td><span class="op-client">{{ op.client }}</span></td>
                    <td><span class="op-route">{{ op.routeFrom }} → {{ op.routeTo }}</span></td>
                    <td>
                        <span class="op-transport">
                            <svg v-if="op.transport === 'ship'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
                                <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
                                <path d="M12 10V2" />
                            </svg>
                            <svg v-else-if="op.transport === 'plane'" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
                            </svg>
                            <svg v-else-if="op.transport === 'truck'" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" rx="1" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </span>
                    </td>
                    <td>
                        <span class="op-incoterm"
                            :style="{ background: getIncotermStyle(op.incoterm).bg, color: getIncotermStyle(op.incoterm).color }">
                            {{ op.incoterm }}
                        </span>
                    </td>
                    <td>
                        <span class="op-status"
                            :style="{ background: getStatusStyle(op.status).bg, color: getStatusStyle(op.status).color }">
                            {{ op.status }}
                        </span>
                    </td>
                    <td><span class="op-date">{{ op.inicio }}</span></td>
                    <td><span class="op-date">{{ op.entrega }}</span></td>
                    <td>
                        <button class="op-action-btn" title="Ver detalle">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr v-if="operaciones.length === 0">
                    <td colspan="10" class="op-empty">No se encontraron operaciones con los filtros aplicados.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.operaciones-table-wrapper {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.operaciones-table {
    width: 100%;
    border-collapse: collapse;
}

.operaciones-table thead th {
    text-align: left;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 14px 12px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.operaciones-table tbody tr {
    transition: background 0.12s ease;
}

.operaciones-table tbody tr:hover {
    background: #f8f9fb;
}

.operaciones-table tbody td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13.5px;
    vertical-align: middle;
}

.operaciones-table tbody tr:last-child td {
    border-bottom: none;
}

.op-ref {
    font-size: 12px;
    font-weight: 600;
    color: var(--accent-blue);
    line-height: 1.5;
    word-break: break-all;
}

.op-oferta {
    font-size: 12.5px;
    color: var(--text-secondary);
}

.op-client {
    font-weight: 600;
    color: var(--text-primary);
}

.op-route {
    color: var(--text-secondary);
    font-size: 13px;
    white-space: nowrap;
}

.op-transport {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
}

.op-incoterm {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}

.op-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.op-date {
    color: var(--text-primary);
    font-size: 13px;
    white-space: nowrap;
}

.op-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    transition: all 0.12s ease;
}

.op-action-btn:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.op-empty {
    text-align: center;
    padding: 40px 12px !important;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
