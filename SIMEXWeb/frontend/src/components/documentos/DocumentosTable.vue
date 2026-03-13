<script setup>
defineProps({
    documentos: { type: Array, required: true },
})

const statusColors = {
    Aprobado: { bg: '#d1fae5', color: '#047857' },
    Pendiente: { bg: '#fef3c7', color: '#b45309' },
}

function getStatusStyle(status) {
    return statusColors[status] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getFileIcon(type) {
    return type === 'PDF' ? '#ef4444' : '#10b981'
}
</script>

<template>
    <div class="documentos-table-wrapper">
        <table class="documentos-table">
            <thead>
                <tr>
                    <th>Nombre del Documento</th>
                    <th>Operación</th>
                    <th>Fecha</th>
                    <th>Tamaño</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="doc in documentos" :key="doc.name">
                    <td>
                        <div class="doc-name-cell">
                            <div class="doc-icon" :style="{ color: getFileIcon(doc.type) }">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                </svg>
                            </div>
                            <div class="doc-name-info">
                                <span class="doc-name">{{ doc.name }}</span>
                                <span class="doc-type">{{ doc.type }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="doc-operation">{{ doc.operation }}</span></td>
                    <td><span class="doc-date">{{ doc.date }}</span></td>
                    <td><span class="doc-size">{{ doc.size }}</span></td>
                    <td>
                        <span class="doc-status"
                            :style="{ background: getStatusStyle(doc.status).bg, color: getStatusStyle(doc.status).color }">
                            {{ doc.status }}
                        </span>
                    </td>
                    <td>
                        <div class="doc-actions">
                            <button class="doc-action-btn" title="Descargar">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                            </button>
                            <button class="doc-action-btn" title="Más opciones">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="documentos.length === 0">
                    <td colspan="6" class="doc-empty">No se encontraron documentos.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.documentos-table-wrapper {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.documentos-table {
    width: 100%;
    border-collapse: collapse;
}

.documentos-table thead th {
    text-align: left;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.documentos-table tbody tr {
    transition: background 0.12s ease;
}

.documentos-table tbody tr:hover {
    background: #f8f9fb;
}

.documentos-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13.5px;
    vertical-align: middle;
}

.documentos-table tbody tr:last-child td {
    border-bottom: none;
}

.doc-name-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.doc-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.doc-name-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.doc-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 13.5px;
}

.doc-type {
    font-size: 11.5px;
    color: var(--text-muted);
}

.doc-operation {
    font-weight: 600;
    color: var(--accent-blue);
    font-size: 13px;
}

.doc-date {
    color: var(--text-primary);
    font-size: 13px;
    white-space: nowrap;
}

.doc-size {
    color: var(--text-secondary);
    font-size: 13px;
    white-space: nowrap;
}

.doc-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.doc-actions {
    display: flex;
    align-items: center;
    gap: 4px;
}

.doc-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    transition: all 0.12s ease;
}

.doc-action-btn:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.doc-empty {
    text-align: center;
    padding: 40px 16px !important;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
