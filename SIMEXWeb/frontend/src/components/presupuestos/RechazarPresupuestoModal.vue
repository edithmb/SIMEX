<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    visible: { type: Boolean, default: false },
    presupuesto: { type: Object, default: null },
})

const emit = defineEmits(['close', 'confirm'])

const reason = ref('')

watch(
    () => props.visible,
    (val) => {
        if (val) {
            reason.value = ''
        }
    },
)

function handleClose() {
    emit('close')
}

function handleConfirm() {
    if (reason.value.trim()) {
        emit('confirm', reason.value.trim())
    }
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
            <div v-if="visible && presupuesto" class="modal-overlay" @click="handleOverlayClick">
                <div class="modal-box">
                    <!-- Header -->
                    <div class="modal-header">
                        <h3 class="modal-header-title">Rechazar Presupuesto</h3>
                        <button class="modal-header-close" @click="handleClose" title="Cerrar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="modal-detail-item">
                            <span class="modal-detail-key">Referencia:</span>
                            <span class="modal-detail-ref">{{ presupuesto.reference }}</span>
                        </div>

                        <div class="modal-field">
                            <label class="modal-label" for="reject-reason">Motivo del rechazo</label>
                            <textarea v-model="reason" id="reject-reason" class="modal-textarea"
                                placeholder="Indique el motivo por el cual rechaza este presupuesto..." rows="4"
                                required></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="modal-footer-cancel" @click="handleClose">Cancelar</button>
                        <button class="modal-footer-confirm" :disabled="!reason.trim()" @click="handleConfirm">
                            Confirmar Rechazo
                        </button>
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
    max-width: 460px;
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
}

.modal-detail-item {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 20px;
}

.modal-detail-key {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
}

.modal-detail-ref {
    font-size: 14px;
    font-weight: 700;
    color: var(--accent-blue);
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

.modal-footer-confirm {
    padding: 10px 24px;
    font-size: 13.5px;
    font-weight: 600;
    color: #ffffff;
    background: #ef4444;
    border-radius: 8px;
    transition: background 0.15s ease;
}

.modal-footer-confirm:hover {
    background: #dc2626;
}

.modal-footer-confirm:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
</style>
