<script setup>
import { reactive, watch } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'submit'])

const roleOptions = [
    { id: 1, name: 'Administrador' },
    { id: 2, name: 'Operador' },
    { id: 3, name: 'Cliente' },
]

const clientOptions = [
    { id: null, name: '-- Sin cliente asociado --' },
    { id: 1, name: 'Importaciones García S.L.' },
    { id: 2, name: 'Textiles Mediterráneo S.A.' },
    { id: 3, name: 'Alimentación Ibérica S.L.' },
    { id: 4, name: 'Electrónica Levante S.A.' },
    { id: 5, name: 'Maquinaria Industrial Norte' },
]

const form = reactive({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    role_id: 2,
    client_id: null,
    is_active: true,
})

// Reset client_id when role is not Cliente
watch(() => form.role_id, (val) => {
    if (val !== 3) form.client_id = null
})

function resetForm() {
    form.first_name = ''
    form.last_name = ''
    form.email = ''
    form.phone_number = ''
    form.role_id = 2
    form.client_id = null
    form.is_active = true
}

function handleClose() {
    resetForm()
    emit('close')
}

function handleSubmit() {
    emit('submit', { ...form })
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
                        <h3 class="modal-header-title">Añadir Nuevo Usuario</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">Nombre</label>
                                <input v-model="form.first_name" type="text" class="modal-input"
                                    placeholder="Ej. Juan" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Apellidos</label>
                                <input v-model="form.last_name" type="text" class="modal-input"
                                    placeholder="Ej. Pérez García" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Email</label>
                                <input v-model="form.email" type="email" class="modal-input"
                                    placeholder="juan@ejemplo.com" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Teléfono</label>
                                <input v-model="form.phone_number" type="text" class="modal-input"
                                    placeholder="+34 600 000 000" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Rol</label>
                                <select v-model="form.role_id" class="modal-select">
                                    <option v-for="r in roleOptions" :key="r.id" :value="r.id">{{ r.name }}</option>
                                </select>
                            </div>
                            <div v-if="form.role_id === 3" class="modal-field">
                                <label class="modal-label">Cliente Asociado</label>
                                <select v-model="form.client_id" class="modal-select">
                                    <option v-for="c in clientOptions" :key="c.id" :value="c.id">{{ c.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-field modal-field--toggle">
                            <label class="modal-label">Estado</label>
                            <label class="modal-toggle">
                                <input type="checkbox" v-model="form.is_active" class="modal-toggle-input" />
                                <span class="modal-toggle-slider"></span>
                                <span class="modal-toggle-label">{{ form.is_active ? 'Activo' : 'Inactivo' }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-submit" @click="handleSubmit">Crear Usuario</button>
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
    max-width: 580px;
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

.modal-field--toggle {
    gap: 8px;
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
    background: var(--page-bg);
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

/* Toggle */
.modal-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.modal-toggle-input {
    display: none;
}

.modal-toggle-slider {
    position: relative;
    width: 40px;
    height: 22px;
    background: #d1d5db;
    border-radius: 11px;
    transition: background 0.2s ease;
    flex-shrink: 0;
}

.modal-toggle-slider::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 16px;
    height: 16px;
    background: #ffffff;
    border-radius: 50%;
    transition: transform 0.2s ease;
}

.modal-toggle-input:checked + .modal-toggle-slider {
    background: #047857;
}

.modal-toggle-input:checked + .modal-toggle-slider::after {
    transform: translateX(18px);
}

.modal-toggle-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
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
