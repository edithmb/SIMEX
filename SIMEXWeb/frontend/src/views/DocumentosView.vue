<script setup>
import { ref, reactive, computed } from 'vue'
import { useRoleStore } from '@/stores/role'
import DocumentosStats from '@/components/documentos/DocumentosStats.vue'
import DocumentosTable from '@/components/documentos/DocumentosTable.vue'
import SubirDocumentoModal from '@/components/documentos/SubirDocumentoModal.vue'

const roleStore = useRoleStore()

// ── Admin data ──
const documentos = [
    { name: 'Bill of Lading - OP-2024-158.pdf', type: 'PDF', operation: 'OP-2024-158', date: '15/01/2024', size: '2.4 MB', status: 'Aprobado' },
    { name: 'Factura Comercial_Textiles.xlsx', type: 'Excel', operation: 'OP-2024-158', date: '14/01/2024', size: '1.1 MB', status: 'Aprobado' },
    { name: 'Certificado de Origen_Miami.pdf', type: 'PDF', operation: 'OP-2024-159', date: '20/01/2024', size: '850 KB', status: 'Pendiente' },
    { name: 'Packing List_Alimentacion.pdf', type: 'PDF', operation: 'OP-2024-160', date: '10/01/2024', size: '1.5 MB', status: 'Aprobado' },
    { name: 'DUA Importación_Shenzhen.pdf', type: 'PDF', operation: 'OP-2024-161', date: '05/12/2023', size: '3.2 MB', status: 'Aprobado' },
    { name: 'Seguro Transporte_Frankfurt.pdf', type: 'PDF', operation: 'OP-2024-162', date: '18/01/2024', size: '1.8 MB', status: 'Pendiente' },
]

const searchQuery = ref('')

const filteredDocumentos = computed(() => {
    if (!searchQuery.value.trim()) return documentos
    const q = searchQuery.value.toLowerCase()
    return documentos.filter(
        (d) => d.name.toLowerCase().includes(q) || d.operation.toLowerCase().includes(q),
    )
})

const showModal = ref(false)

function handleSubmit(data) {
    console.log('Subir documento:', data)
    showModal.value = false
}

// ── Client data ──
const operationDocuments = reactive([
    {
        operationRef: 'OP-2024-001',
        clientName: 'Importaciones García S.L.',
        route: 'Shanghai → Barcelona',
        documents: [
            { id: 1, type: 'Bill of Lading (BL)', status: 'subido', fileName: 'BL_OP001.pdf' },
            { id: 2, type: 'Factura Comercial', status: 'subido', fileName: 'Factura_OP001.pdf' },
            { id: 3, type: 'Certificado de Origen', status: 'pendiente', fileName: null },
            { id: 4, type: 'DUA Importación', status: 'urgente', fileName: null },
            { id: 5, type: 'Packing List', status: 'subido', fileName: 'PL_OP001.pdf' },
            { id: 6, type: 'Seguro de Transporte', status: 'pendiente', fileName: null },
        ],
    },
    {
        operationRef: 'OP-2024-002',
        clientName: 'Textiles Mediterráneo S.A.',
        route: 'Rotterdam → Valencia',
        documents: [
            { id: 7, type: 'Bill of Lading (BL)', status: 'subido', fileName: 'BL_OP002.pdf' },
            { id: 8, type: 'Factura Comercial', status: 'pendiente', fileName: null },
            { id: 9, type: 'Certificado de Origen', status: 'urgente', fileName: null },
            { id: 10, type: 'DUA Importación', status: 'pendiente', fileName: null },
            { id: 11, type: 'Packing List', status: 'subido', fileName: 'PL_OP002.pdf' },
        ],
    },
    {
        operationRef: 'OP-2024-003',
        clientName: 'Electrónica Levante S.A.',
        route: 'Shenzhen → Bilbao',
        documents: [
            { id: 12, type: 'Air Waybill (AWB)', status: 'subido', fileName: 'AWB_OP003.pdf' },
            { id: 13, type: 'Factura Comercial', status: 'subido', fileName: 'Factura_OP003.pdf' },
            { id: 14, type: 'Packing List', status: 'subido', fileName: 'PL_OP003.pdf' },
            { id: 15, type: 'DUA Importación', status: 'pendiente', fileName: null },
        ],
    },
])

const clientFilters = ['Todos', 'Pendiente', 'Urgente', 'Subido']
const activeDocFilter = ref('Todos')

const filteredOperationDocs = computed(() => {
    return operationDocuments
        .map((op) => {
            const filteredDocs =
                activeDocFilter.value === 'Todos'
                    ? op.documents
                    : op.documents.filter(
                          (d) => d.status === activeDocFilter.value.toLowerCase(),
                      )
            return { ...op, filteredDocs }
        })
        .filter((op) => op.filteredDocs.length > 0)
})

function mockUpload(doc) {
    doc.status = 'subido'
    doc.fileName = doc.type.replace(/\s/g, '_') + '.pdf'
}
</script>

<template>
    <div class="documentos">
        <!-- ── Admin view ── -->
        <template v-if="roleStore.isAdmin">
            <div class="documentos-header">
                <h2 class="documentos-header-title">Gestión Documental</h2>
                <button class="documentos-header-btn" @click="showModal = true">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    Subir Documento
                </button>
            </div>
            <DocumentosStats />

            <!-- Search -->
            <div class="documentos-search-wrapper">
                <svg class="documentos-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="documentos-search-input" placeholder="Buscar por nombre o referencia..."
                    v-model="searchQuery" id="documentos-search" />
            </div>

            <DocumentosTable :documentos="filteredDocumentos" />

            <SubirDocumentoModal :visible="showModal" @close="showModal = false" @submit="handleSubmit" />
        </template>

        <!-- ── Client view ── -->
        <template v-else>
            <!-- Header -->
            <div class="documentos-header">
                <h2 class="documentos-header-title">Mis Documentos</h2>
            </div>

            <!-- Filter buttons -->
            <div class="doc-client-filters">
                <button v-for="f in clientFilters" :key="f"
                    :class="['doc-client-filter-btn', { 'doc-client-filter-btn--active': activeDocFilter === f }]"
                    @click="activeDocFilter = f">
                    {{ f }}
                </button>
            </div>

            <!-- Operation cards -->
            <div v-for="op in filteredOperationDocs" :key="op.operationRef" class="doc-operation-card">
                <div class="doc-operation-header">
                    <span class="doc-operation-ref">{{ op.operationRef }}</span>
                    <span class="doc-operation-route">{{ op.route }}</span>
                </div>
                <div class="doc-operation-list">
                    <div v-for="doc in op.filteredDocs" :key="doc.id" class="doc-operation-item">
                        <div class="doc-operation-item-left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            <span class="doc-operation-item-name">{{ doc.type }}</span>
                        </div>
                        <div class="doc-operation-item-right">
                            <span v-if="doc.status === 'subido'"
                                class="doc-status-badge doc-status-badge--subido">Subido</span>
                            <span v-else-if="doc.status === 'urgente'"
                                class="doc-status-badge doc-status-badge--urgente">Urgente</span>
                            <span v-else
                                class="doc-status-badge doc-status-badge--pendiente">Pendiente</span>
                            <button v-if="doc.status !== 'subido'" class="doc-upload-btn" @click="mockUpload(doc)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                Subir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>
.documentos {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.documentos-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.documentos-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.documentos-header-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: var(--sidebar-bg);
    color: #ffffff;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    transition: background 0.15s ease;
}

.documentos-header-btn:hover {
    background: #0d2440;
}

.documentos-search-wrapper {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    position: relative;
    display: flex;
    align-items: center;
}

.documentos-search-icon {
    position: absolute;
    left: 36px;
    color: var(--text-muted);
    pointer-events: none;
}

.documentos-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.documentos-search-input::placeholder {
    color: var(--text-muted);
}

.documentos-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

/* ── Client view ── */
.doc-client-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 14px 22px;
    display: flex;
    gap: 8px;
}

.doc-client-filter-btn {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--page-bg);
    transition: all 0.15s ease;
}

.doc-client-filter-btn:hover {
    color: var(--text-primary);
    background: #e2e5ea;
}

.doc-client-filter-btn--active {
    background: var(--sidebar-bg);
    color: #ffffff;
}

.doc-operation-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 20px 22px;
}

.doc-operation-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.doc-operation-ref {
    font-size: 14px;
    font-weight: 700;
    color: var(--accent-blue);
}

.doc-operation-route {
    font-size: 13px;
    color: var(--text-secondary);
}

.doc-operation-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.doc-operation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: var(--page-bg);
    border-radius: 8px;
}

.doc-operation-item-left {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-secondary);
}

.doc-operation-item-name {
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-primary);
}

.doc-operation-item-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.doc-status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.doc-status-badge--subido {
    background: #d1fae5;
    color: #047857;
}

.doc-status-badge--urgente {
    background: #fee2e2;
    color: #dc2626;
}

.doc-status-badge--pendiente {
    background: #fef3c7;
    color: #b45309;
}

.doc-upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    background: var(--sidebar-bg);
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    transition: background 0.15s ease;
}

.doc-upload-btn:hover {
    background: #0d2440;
}
</style>
