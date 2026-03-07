<script setup>
defineProps({
    presupuestos: { type: Array, required: true },
})

const incotermColors = {
    CIF: { bg: '#dbeafe', color: '#1a6fb5' },
    EXW: { bg: '#fef3c7', color: '#b45309' },
    DDP: { bg: '#d1fae5', color: '#047857' },
    FOB: { bg: '#ede9fe', color: '#6d28d9' },
    DAP: { bg: '#fce7f3', color: '#be185d' },
    CIP: { bg: '#e0e7ff', color: '#4338ca' },
}

const statusColors = {
    Caducado: { bg: '#fef3c7', color: '#b45309' },
    Enviado: { bg: '#dbeafe', color: '#1a6fb5' },
    Rechazado: { bg: '#fee2e2', color: '#dc2626' },
    Aceptado: { bg: '#d1fae5', color: '#047857' },
    Borrador: { bg: '#e5e7eb', color: '#6b7280' },
}

function getIncotermStyle(incoterm) {
    return incotermColors[incoterm] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getStatusStyle(status) {
    return statusColors[status] || { bg: '#e5e7eb', color: '#6b7280' }
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
                    <th>Ruta</th>
                    <th>Incoterm</th>
                    <th>Transp.</th>
                    <th>Valor</th>
                    <th>Estado</th>
                    <th>Validez</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in presupuestos" :key="p.ref">
                    <td><span class="pres-ref">{{ p.ref }}</span></td>
                    <td><span class="pres-sol">{{ p.solicitud }}</span></td>
                    <td><span class="pres-client">{{ p.client }}</span></td>
                    <td><span class="pres-route">{{ p.routeFrom }} → {{ p.routeTo }}</span></td>
                    <td>
                        <span class="pres-incoterm"
                            :style="{ background: getIncotermStyle(p.incoterm).bg, color: getIncotermStyle(p.incoterm).color }">
                            {{ p.incoterm }}
                        </span>
                    </td>
                    <td>
                        <span class="pres-transport">
                            <svg v-if="p.transport === 'ship'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76" />
                                <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6" />
                                <path d="M12 10V2" />
                            </svg>
                            <svg v-else-if="p.transport === 'plane'" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z" />
                            </svg>
                            <svg v-else-if="p.transport === 'truck'" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13" rx="1" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </span>
                    </td>
                    <td><span class="pres-value">{{ p.value }}</span></td>
                    <td>
                        <span class="pres-status"
                            :style="{ background: getStatusStyle(p.status).bg, color: getStatusStyle(p.status).color }">
                            {{ p.status }}
                        </span>
                    </td>
                    <td><span class="pres-validity">{{ p.validity }}</span></td>
                    <td><!-- placeholder for future actions --></td>
                </tr>
                <tr v-if="presupuestos.length === 0">
                    <td colspan="10" class="pres-empty">No se encontraron presupuestos con los filtros aplicados.</td>
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

.pres-route {
    color: var(--text-secondary);
    font-size: 13px;
    white-space: nowrap;
}

.pres-incoterm {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}

.pres-transport {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
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
</style>
