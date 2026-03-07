<script setup>
import { reactive, ref } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'submit'])

const docTypes = [
    'Bill of Lading (BL)',
    'Factura Comercial',
    'Packing List',
    'Certificado de Origen',
    'DUA Importación',
    'CMR',
    'Seguro de Transporte',
    'Otro',
]

const form = reactive({
    docType: 'Bill of Lading (BL)',
    operation: '',
})

const fileName = ref('')
const isDragging = ref(false)

function handleFile(e) {
    const file = e.target.files?.[0]
    if (file) fileName.value = file.name
}

function handleDrop(e) {
    e.preventDefault()
    isDragging.value = false
    const file = e.dataTransfer?.files?.[0]
    if (file) fileName.value = file.name
}

function handleDragOver(e) {
    e.preventDefault()
    isDragging.value = true
}

function handleDragLeave() {
    isDragging.value = false
}

function handleClose() {
    form.docType = 'Bill of Lading (BL)'
    form.operation = ''
    fileName.value = ''
    emit('close')
}

function handleSubmit() {
    emit('submit', { ...form, fileName: fileName.value })
    handleClose()
}

function handleOverlayClick(e) {
    if (e.target === e.currentTarget) handleClose()
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="visible" class="modal-overlay" @click="handleOverlayClick">
                <div class="modal-box">
                    <div class="modal-header">
                        <h3 class="modal-header-title">Subir Nuevo Documento</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Upload Zone -->
                        <label
                            :class="['upload-zone', { 'upload-zone--dragging': isDragging, 'upload-zone--has-file': fileName }]"
                            @drop="handleDrop" @dragover="handleDragOver" @dragleave="handleDragLeave">
                            <input type="file" class="upload-zone-input" @change="handleFile" />
                            <svg v-if="!fileName" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                            <span v-if="!fileName" class="upload-zone-text">Haz clic para seleccionar o arrastra un
                                archivo</span>
                            <span v-if="!fileName" class="upload-zone-hint">PDF, Excel, Imágenes (máx. 10MB)</span>
                            <span v-if="fileName" class="upload-zone-file">📄 {{ fileName }}</span>
                        </label>

                        <div class="modal-field">
                            <label class="modal-label">Tipo de Documento</label>
                            <select v-model="form.docType" class="modal-select">
                                <option v-for="t in docTypes" :key="t" :value="t">{{ t }}</option>
                            </select>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label">Operación Asociada (Opcional)</label>
                            <input v-model="form.operation" type="text" class="modal-input"
                                placeholder="Ej. OP-2024-158" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-submit" @click="handleSubmit">Subir Archivo</button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.modal-box {
    background: #ffffff;
    border-radius: 14px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 28px 0;
}

.modal-header-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
}

.modal-header-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    transition: all 0.12s ease;
}

.modal-header-close:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.modal-body {
    padding: 20px 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Upload Zone */
.upload-zone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 28px 20px;
    border: 2px dashed var(--border-color);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.15s ease;
    color: var(--text-muted);
}

.upload-zone:hover {
    border-color: var(--accent-blue);
    background: rgba(26, 111, 181, 0.03);
}

.upload-zone--dragging {
    border-color: var(--accent-blue);
    background: rgba(26, 111, 181, 0.06);
}

.upload-zone--has-file {
    border-color: var(--accent-green);
    background: rgba(16, 185, 129, 0.04);
}

.upload-zone-input {
    display: none;
}

.upload-zone-text {
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-secondary);
}

.upload-zone-hint {
    font-size: 11.5px;
    color: var(--text-muted);
}

.upload-zone-file {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--accent-green);
}

.modal-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.modal-label {
    font-size: 12.5px;
    font-weight: 500;
    color: var(--text-secondary);
}

.modal-input,
.modal-select {
    height: 40px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0 12px;
    font-size: 13.5px;
    font-family: var(--font-family);
    color: var(--text-primary);
    background: #ffffff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    appearance: auto;
}

.modal-input:focus,
.modal-select:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(26, 111, 181, 0.12);
    outline: none;
}

.modal-input::placeholder {
    color: var(--text-muted);
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 0 28px 24px;
}

.modal-footer-cancel {
    padding: 10px 22px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-secondary);
    border-radius: 8px;
    transition: all 0.12s ease;
}

.modal-footer-cancel:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.modal-footer-submit {
    padding: 10px 24px;
    font-size: 13.5px;
    font-weight: 600;
    color: #ffffff;
    background: var(--sidebar-bg);
    border-radius: 8px;
    transition: background 0.15s ease;
}

.modal-footer-submit:hover {
    background: #0d2440;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-active .modal-box,
.modal-leave-active .modal-box {
    transition: transform 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-box {
    transform: scale(0.95) translateY(10px);
}

.modal-leave-to .modal-box {
    transform: scale(0.95) translateY(10px);
}
</style>
