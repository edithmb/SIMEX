<script setup>
import { reactive } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'submit'])

const form = reactive({
    origin: '',
    destination: '',
    volume_m3: '',
    gross_weight_kg: '',
    comments: '',
})

const locationOptions = [
    { value: '', label: 'Seleccionar...' },
    { value: 'Almacén Principal - Calle Industrial 15, Valencia', label: 'Almacén Principal - Calle Industrial 15, Valencia' },
    { value: 'Nave Logística - Pol. Ind. Norte, Coslada, Madrid', label: 'Nave Logística - Pol. Ind. Norte, Coslada, Madrid' },
    { value: 'Almacén Frigorífico - Zona Franca, Barcelona', label: 'Almacén Frigorífico - Zona Franca, Barcelona' },
    { value: 'Fábrica Shanghai - Pudong District, China', label: 'Fábrica Shanghai - Pudong District, China' },
    { value: 'Almacén Rotterdam - Europoort, Países Bajos', label: 'Almacén Rotterdam - Europoort, Países Bajos' },
    { value: 'Centro Distribución Miami - NW 25th St, Florida, EEUU', label: 'Centro Distribución Miami - NW 25th St, Florida, EEUU' },
    { value: 'Fábrica Shenzhen - Guangdong, China', label: 'Fábrica Shenzhen - Guangdong, China' },
    { value: 'Almacén Frankfurt - Hessen, Alemania', label: 'Almacén Frankfurt - Hessen, Alemania' },
]

function resetForm() {
    form.origin = ''
    form.destination = ''
    form.volume_m3 = ''
    form.gross_weight_kg = ''
    form.comments = ''
}

function handleClose() {
    resetForm()
    emit('close')
}

function handleSubmit() {
    emit('submit', { ...form })
    resetForm()
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
            <div v-if="visible" class="modal-overlay" @click="handleOverlayClick">
                <div class="modal-box">
                    <!-- Header -->
                    <div class="modal-header">
                        <h3 class="modal-header-title">Nueva Solicitud de Transporte</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form Body -->
                    <div class="modal-body">
                        <!-- Origen -->
                        <div class="modal-field">
                            <label class="modal-label">Origen</label>
                            <select v-model="form.origin" class="modal-select">
                                <option v-for="opt in locationOptions" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Destino -->
                        <div class="modal-field">
                            <label class="modal-label">Destino</label>
                            <select v-model="form.destination" class="modal-select">
                                <option v-for="opt in locationOptions" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Volumen / Peso (2-column) -->
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">Volumen (m³)</label>
                                <input v-model="form.volume_m3" type="number" class="modal-input"
                                    placeholder="Ej. 45" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Peso Bruto (kg)</label>
                                <input v-model="form.gross_weight_kg" type="number" class="modal-input"
                                    placeholder="Ej. 12450" />
                            </div>
                        </div>

                        <!-- Comentarios -->
                        <div class="modal-field modal-field--full">
                            <label class="modal-label">Comentarios</label>
                            <textarea v-model="form.comments" class="modal-textarea"
                                placeholder="Descripción de la mercancía, condiciones especiales..." rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-submit" @click="handleSubmit">Enviar Solicitud</button>
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
    max-width: 520px;
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

/* Body */
.modal-body {
    padding: 20px 28px 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
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

    .modal-box {
        max-height: 95vh;
    }
}
</style>
