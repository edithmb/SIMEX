<script setup>
import { ref, computed } from 'vue'
import DocumentosStats from '@/components/documentos/DocumentosStats.vue'
import DocumentosTable from '@/components/documentos/DocumentosTable.vue'
import SubirDocumentoModal from '@/components/documentos/SubirDocumentoModal.vue'

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
</script>

<template>
    <div class="documentos">
        <div class="documentos-header">
            <h2 class="documentos-header-title">Gestión Documental</h2>
            <button class="documentos-header-btn" @click="showModal = true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
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
</style>
