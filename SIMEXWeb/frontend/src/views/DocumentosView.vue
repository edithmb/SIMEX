<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useRoleStore } from '@/stores/role'
import { useAuthStore } from '@/stores/auth'
import SubirDocumentoModal from '@/components/documentos/SubirDocumentoModal.vue'
import Spinner from '@/components/common/Spinner.vue'

const roleStore = useRoleStore()
const auth = useAuthStore()
const router = useRouter()

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const NET = import.meta.env.VITE_NET_API
const headers = { Authorization: `Bearer ${auth.token}` }

const operaciones = ref([])
const loading = ref(false)
const showModal = ref(false)
const submittingUpload = ref(false)

// Carga todas las operaciones logísticas con sus documentos
async function fetchOperaciones() {
    loading.value = true
    try {
        const res = await axios.get(LARAVEL + '/logistics-operations?per_page=100', { headers })
        const rows = Array.isArray(res.data) ? res.data : (res.data.data || [])
        operaciones.value = rows.map(mapOperacion)
    } catch (error) {
        if (error.response?.status === 401) {
            await auth.logout()
            router.push({ name: 'login' })
        } else {
            console.error('Error al cargar documentos:', error)
        }
    } finally {
        loading.value = false
    }
}

function mapOperacion(op) {
    const offer = op.commercial_offer || op.commercialOffer || {}
    const originPort = offer.origin_port?.name || offer.originPort?.name || '—'
    const destPort = offer.destination_port?.name || offer.destinationPort?.name || '—'
    const route = originPort !== '—' || destPort !== '—'
        ? `${originPort} → ${destPort}`
        : '—'

    const rawDocs = op.logistics_operation_documents || op.logisticsOperationDocuments || []
    const documents = rawDocs.map((d) => ({
        id: d.id,
        type: d.custom_name || d.customName || d.document_type?.name || d.documentType?.name || 'Documento',
        status: mapDocStatus(d.status),
        fileName: d.file_name || d.fileName || null,
        fileUrl: d.file_url || d.fileUrl || null,
    }))

    return {
        operationRef: op.reference || `OP-${op.id}`,
        clientName: op.client?.company_name || op.client?.companyName || '—',
        route,
        documents,
    }
}

function mapDocStatus(status) {
    if (!status) return 'pendiente'
    const s = status.toLowerCase()
    if (s === 'uploaded' || s === 'subido' || s === 'approved') return 'subido'
    if (s === 'urgent' || s === 'urgente') return 'urgente'
    return 'pendiente'
}

onMounted(fetchOperaciones)

const clientFilters = ['Todos', 'Pendiente', 'Urgente', 'Subido']
const activeDocFilter = ref('Todos')

const filteredOperationDocs = computed(() => {
    return operaciones.value
        .map((op) => {
            const filteredDocs =
                activeDocFilter.value === 'Todos'
                    ? op.documents
                    : op.documents.filter((d) => d.status === activeDocFilter.value.toLowerCase())
            return { ...op, filteredDocs }
        })
        .filter((op) => op.filteredDocs.length > 0)
})

// Subir documento a .NET API
async function handleUpload(doc, operationRef) {
    const input = document.createElement('input')
    input.type = 'file'
    input.onchange = async (e) => {
        const file = e.target.files[0]
        if (!file) return
        try {
            const formData = new FormData()
            formData.append('file', file)
            formData.append('documentId', doc.id)
            await axios.post(NET + '/DocumentsTramite/upload', formData, {
                headers: { ...headers, 'Content-Type': 'multipart/form-data' },
            })
            // Refresca la lista para mostrar el nuevo estado
            await fetchOperaciones()
        } catch (err) {
            console.error('Error al subir documento:', err)
        }
    }
    input.click()
}

// Descargar documento desde .NET API
async function handleDownload(doc) {
    if (!doc.id) return
    try {
        const res = await axios.get(`${NET}/DocumentsPerson/download/${doc.id}`, {
            headers,
            responseType: 'blob',
        })
        const url = URL.createObjectURL(res.data)
        const a = document.createElement('a')
        a.href = url
        a.download = doc.fileName || 'documento'
        a.click()
        URL.revokeObjectURL(url)
    } catch (err) {
        console.error('Error al descargar documento:', err)
    }
}

async function handleSubmit(data) {
    if (submittingUpload.value) return
    submittingUpload.value = true
    try {
        console.log('Subir documento:', data)
        showModal.value = false
    } finally {
        submittingUpload.value = false
    }
}
</script>

<template>
    <div class="documentos">
        <!-- Header -->
        <div :class="['documentos-header', { 'documentos-header--with-btn': roleStore.isAdmin }]">
            <div class="documentos-header-text">
                <h2 class="documentos-header-title">
                    {{ roleStore.isAdmin ? 'Gestión Documental' : 'Mis Documentos' }}
                </h2>
            </div>
            <button v-if="roleStore.isAdmin" class="documentos-header-btn" @click="showModal = true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="17 8 12 3 7 8" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                Subir Documento
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="view-loading">
            <Spinner :size="40" />
        </div>

        <!-- Filter buttons -->
        <div v-else class="doc-client-filters">
            <button v-for="f in clientFilters" :key="f" :class="[
                'doc-client-filter-btn',
                { 'doc-client-filter-btn--active': activeDocFilter === f },
            ]" @click="activeDocFilter = f">
                {{ f }}
            </button>
        </div>

        <!-- Sin datos -->
        <p v-if="!loading && filteredOperationDocs.length === 0" class="documentos-empty">
            No hay documentos disponibles.
        </p>

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
                        <span v-else class="doc-status-badge doc-status-badge--pendiente">Pendiente</span>

                        <!-- Descargar si ya está subido -->
                        <button v-if="doc.status === 'subido' && doc.id" class="doc-upload-btn"
                            @click="handleDownload(doc)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            Descargar
                        </button>

                        <!-- Subir si pendiente -->
                        <button v-else-if="doc.status !== 'subido'" class="doc-upload-btn"
                            @click="handleUpload(doc, op.operationRef)">
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

        <SubirDocumentoModal :visible="showModal" :submitting="submittingUpload"
            @close="showModal = false" @submit="handleSubmit" />
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
    flex-direction: column;
    gap: 4px;
}

.documentos-header--with-btn {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.documentos-header-text {
    display: flex;
    flex-direction: column;
    gap: 4px;
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

.documentos-empty {
    font-size: 13.5px;
    color: var(--text-secondary);
    padding: 20px 0;
    text-align: center;
}

.view-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 0;
    color: var(--accent-blue);
}

/* ── Filters & cards ── */
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
