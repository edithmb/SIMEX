<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
    solicitud: { type: Object, default: null },
})

const emit = defineEmits(['close', 'submit'])

const form = reactive({
    reference: '',
    incoterm: 'FOB',
    origin_port: '',
    destination_port: '',
    container_type: '',
    price: '',
    valid_until: '',
    comments: '',
})

// Reset form when solicitud changes
watch(
    () => props.solicitud,
    (sol) => {
        if (sol) {
            form.reference = ''
            form.incoterm = 'FOB'
            form.origin_port = ''
            form.destination_port = ''
            form.container_type = ''
            form.price = ''
            form.valid_until = ''
            form.comments = ''
        }
    },
    { immediate: true },
)

const incotermOptions = [
    { value: 'FOB', label: 'FOB (Free On Board)' },
    { value: 'CIF', label: 'CIF (Cost, Insurance & Freight)' },
    { value: 'EXW', label: 'EXW (Ex Works)' },
    { value: 'DDP', label: 'DDP (Delivered Duty Paid)' },
    { value: 'FCA', label: 'FCA (Free Carrier)' },
    { value: 'CFR', label: 'CFR (Cost & Freight)' },
    { value: 'CPT', label: 'CPT (Carriage Paid To)' },
    { value: 'CIP', label: 'CIP (Carriage & Insurance Paid To)' },
    { value: 'DAP', label: 'DAP (Delivered At Place)' },
    { value: 'DPU', label: 'DPU (Delivered at Place Unloaded)' },
]

const portOptions = [
    { value: '', label: 'Seleccionar...' },
    { value: 'Barcelona', label: 'Barcelona' },
    { value: 'Valencia', label: 'Valencia' },
    { value: 'Bilbao', label: 'Bilbao' },
    { value: 'Algeciras', label: 'Algeciras' },
    { value: 'Shanghai', label: 'Shanghai' },
    { value: 'Shenzhen', label: 'Shenzhen' },
    { value: 'Ningbo', label: 'Ningbo' },
    { value: 'Rotterdam', label: 'Rotterdam' },
    { value: 'Hamburgo', label: 'Hamburgo' },
    { value: 'Amberes', label: 'Amberes' },
    { value: 'Miami', label: 'Miami' },
    { value: 'Nueva York', label: 'Nueva York' },
    { value: 'Los Ángeles', label: 'Los Ángeles' },
    { value: 'Singapur', label: 'Singapur' },
    { value: 'Busan', label: 'Busan' },
    { value: 'Santos', label: 'Santos' },
    { value: 'El Pireo', label: 'El Pireo' },
    { value: 'Génova', label: 'Génova' },
    { value: 'Jebel Ali', label: 'Jebel Ali' },
    { value: 'Le Havre', label: 'Le Havre' },
]

const containerOptions = [
    { value: '', label: 'Seleccionar...' },
    { value: '20st', label: "20' Standard" },
    { value: '40st', label: "40' Standard" },
    { value: '40hc', label: "40' High Cube" },
    { value: '20rf', label: "20' Reefer" },
    { value: '40rf', label: "40' Reefer" },
]

function handleClose() {
    emit('close')
}

function handleSubmit() {
    emit('submit', { ...form })
}

function handleOverlayClick(e) {
    if (e.target === e.currentTarget) {
        handleClose()
    }
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="visible && solicitud" class="modal-overlay" @click="handleOverlayClick">
                <div class="modal-box">
                    <!-- Header -->
                    <div class="modal-header">
                        <h3 class="modal-header-title">Crear Presupuesto</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <!-- Solicitud Vinculada -->
                    <div class="modal-linked">
                        <span class="modal-linked-label">Solicitud Vinculada</span>
                        <div class="modal-linked-grid">
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">ID:</span>
                                <span class="modal-linked-ref">{{ solicitud.id }}</span>
                            </div>
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">Cliente:</span>
                                <span class="modal-linked-val">{{ solicitud.clientName }}</span>
                            </div>
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">Volumen (m³):</span>
                                <span class="modal-linked-val">{{ solicitud.volume_m3 }}</span>
                            </div>
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">Peso Bruto (kg):</span>
                                <span class="modal-linked-val">{{ solicitud.gross_weight_kg?.toLocaleString() }}</span>
                            </div>
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">Origen:</span>
                                <span class="modal-linked-val">{{ solicitud.originName }}</span>
                            </div>
                            <div class="modal-linked-item">
                                <span class="modal-linked-key">Destino:</span>
                                <span class="modal-linked-val">{{ solicitud.destinationName }}</span>
                            </div>
                            <div class="modal-linked-item modal-linked-item--full">
                                <span class="modal-linked-key">Comentarios:</span>
                                <span class="modal-linked-val">{{ solicitud.comments }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="modal-body">
                        <!-- Detalles de la Oferta -->
                        <h4 class="modal-section-title">Detalles de la Oferta</h4>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">Referencia</label>
                                <input v-model="form.reference" type="text" class="modal-input"
                                    placeholder="PR-2024-XXX" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Incoterm</label>
                                <select v-model="form.incoterm" class="modal-select">
                                    <option v-for="opt in incotermOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Puerto Origen</label>
                                <select v-model="form.origin_port" class="modal-select">
                                    <option v-for="opt in portOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Puerto Destino</label>
                                <select v-model="form.destination_port" class="modal-select">
                                    <option v-for="opt in portOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Tipo de Contenedor</label>
                                <select v-model="form.container_type" class="modal-select">
                                    <option v-for="opt in containerOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Condiciones Económicas -->
                        <h4 class="modal-section-title">Condiciones Económicas</h4>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">Precio (EUR)</label>
                                <input v-model="form.price" type="text" class="modal-input" placeholder="Ej. 12.450" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Válido Hasta</label>
                                <input v-model="form.valid_until" type="date" class="modal-input" />
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="modal-field modal-field--full">
                            <label class="modal-label">Comentarios</label>
                            <textarea v-model="form.comments" class="modal-textarea"
                                placeholder="Condiciones especiales, notas internas..." rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-submit" @click="handleSubmit">Generar Presupuesto</button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* Overlay */
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

/* Box */
.modal-box {
    background: #ffffff;
    border-radius: 14px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
}

/* Header */
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

/* Solicitud Vinculada */
.modal-linked {
    margin: 20px 28px 0;
    background: #f8f9fb;
    border-radius: 10px;
    padding: 14px 18px;
    border: 1px solid var(--border-color);
}

.modal-linked-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
    margin-bottom: 10px;
}

.modal-linked-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 24px;
}

.modal-linked-item {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.modal-linked-item--full {
    grid-column: 1 / -1;
}

.modal-linked-key {
    font-size: 12.5px;
    color: var(--text-secondary);
    flex-shrink: 0;
}

.modal-linked-ref {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent-blue);
}

.modal-linked-val {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
}

/* Body */
.modal-body {
    padding: 20px 28px 0;
}

.modal-section-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 14px;
    margin-top: 8px;
}

.modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.modal-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.modal-field--full {
    margin-bottom: 8px;
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

.modal-textarea {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 13.5px;
    font-family: var(--font-family);
    color: var(--text-primary);
    background: #ffffff;
    resize: vertical;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.modal-textarea:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(26, 111, 181, 0.12);
    outline: none;
}

.modal-textarea::placeholder {
    color: var(--text-muted);
}

/* Footer */
.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 28px 24px;
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

/* Transition */
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

@media (max-width: 600px) {
    .modal-grid {
        grid-template-columns: 1fr;
    }

    .modal-linked-grid {
        grid-template-columns: 1fr;
    }

    .modal-box {
        max-height: 95vh;
    }
}
</style>
