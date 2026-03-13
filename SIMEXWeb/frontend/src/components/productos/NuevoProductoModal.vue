<script setup>
import { reactive } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
})

const emit = defineEmits(['close', 'submit'])

const categories = ['Electrónica', 'Textil', 'Maquinaria', 'Alimentación', 'Química']
const origins = ['China', 'India', 'Alemania', 'España', 'USA', 'Taiwán', 'Vietnam', 'Italia']

const form = reactive({
    name: '',
    sku: '',
    category: 'Electrónica',
    stock: 0,
    origin: 'China',
})

function handleClose() {
    form.name = ''
    form.sku = ''
    form.category = 'Electrónica'
    form.stock = 0
    form.origin = 'China'
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
                        <h3 class="modal-header-title">Añadir Nuevo Producto</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="modal-field">
                            <label class="modal-label">Nombre del Producto</label>
                            <input v-model="form.name" type="text" class="modal-input"
                                placeholder="Ej. Motor Eléctrico" />
                        </div>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">SKU / Referencia</label>
                                <input v-model="form.sku" type="text" class="modal-input" placeholder="PRD-XX-001" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">Categoría</label>
                                <select v-model="form.category" class="modal-select">
                                    <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-grid">
                            <div class="modal-field">
                                <label class="modal-label">Stock Inicial</label>
                                <input v-model.number="form.stock" type="number" class="modal-input" min="0" />
                            </div>
                            <div class="modal-field">
                                <label class="modal-label">País de Origen</label>
                                <select v-model="form.origin" class="modal-select">
                                    <option v-for="o in origins" :key="o" :value="o">{{ o }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-submit" @click="handleSubmit">Añadir Producto</button>
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
