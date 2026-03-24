<script setup>
import { ref, reactive, computed } from 'vue'
import { useRoleStore } from '@/stores/role'

const roleStore = useRoleStore()
const canEditEmpresa = computed(() => roleStore.isAdmin)

const activeTab = ref('empresa')

const empresa = reactive({
    name: 'LogiTrack Pro S.L.',
    cif: 'B12345678',
    address: 'Av. del Puerto 123, 46024 Valencia, España',
    incoterm: 'CIF',
    currency: 'EUR',
})

const security = reactive({
    email: 'usuario@logitracks.com',
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
    twoFactor: true,
    sessionTimeout: '30',
})

const editingEmail = ref(false)
const emailDraft = ref('')

function startEditEmail() {
    emailDraft.value = security.email
    editingEmail.value = true
}

function saveEmail() {
    security.email = emailDraft.value
    editingEmail.value = false
}

function cancelEditEmail() {
    editingEmail.value = false
}

function handleSave() {
    console.log('Guardar cambios:', activeTab.value === 'empresa' ? empresa : security)
}

function handleUpdatePassword() {
    console.log('Actualizar contraseña')
    security.currentPassword = ''
    security.newPassword = ''
    security.confirmPassword = ''
}
</script>

<template>
    <div class="config">
        <div class="config-header">
            <h2 class="config-header-title">Configuración del Sistema</h2>
            <button v-if="canEditEmpresa || activeTab === 'seguridad'" class="config-header-btn" @click="handleSave">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                Guardar Cambios
            </button>
        </div>

        <div class="config-layout">
            <!-- Sidebar Tabs -->
            <div class="config-sidebar">
                <button :class="['config-tab', { 'config-tab--active': activeTab === 'empresa' }]"
                    @click="activeTab = 'empresa'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Datos de la Empresa
                </button>
                <button :class="['config-tab', { 'config-tab--active': activeTab === 'seguridad' }]"
                    @click="activeTab = 'seguridad'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Seguridad y Accesos
                </button>
            </div>

            <!-- Content Panel -->
            <div class="config-content">
                <!-- Tab Empresa -->
                <template v-if="activeTab === 'empresa'">
                    <div v-if="!canEditEmpresa" class="config-readonly-notice">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Solo los administradores pueden modificar los datos de la empresa.
                    </div>

                    <div class="config-card">
                        <h3 class="config-card-title">Información General</h3>
                        <div class="config-grid">
                            <div class="config-field">
                                <label class="config-label">Nombre de la Empresa</label>
                                <input v-model="empresa.name" type="text"
                                    :class="['config-input', { 'config-input--readonly': !canEditEmpresa }]"
                                    :readonly="!canEditEmpresa" />
                            </div>
                            <div class="config-field">
                                <label class="config-label">CIF / NIF</label>
                                <input v-model="empresa.cif" type="text"
                                    :class="['config-input', { 'config-input--readonly': !canEditEmpresa }]"
                                    :readonly="!canEditEmpresa" />
                            </div>
                        </div>
                        <div class="config-field">
                            <label class="config-label">Dirección Principal</label>
                            <input v-model="empresa.address" type="text"
                                :class="['config-input', { 'config-input--readonly': !canEditEmpresa }]"
                                :readonly="!canEditEmpresa" />
                        </div>
                    </div>

                    <div class="config-card">
                        <h3 class="config-card-title">Preferencias Operativas</h3>
                        <div class="config-grid">
                            <div class="config-field">
                                <label class="config-label">Incoterm por Defecto</label>
                                <select v-model="empresa.incoterm"
                                    :class="['config-select', { 'config-input--readonly': !canEditEmpresa }]"
                                    :disabled="!canEditEmpresa">
                                    <option value="CIF">CIF (Cost, Insurance and Freight)</option>
                                    <option value="FOB">FOB (Free on Board)</option>
                                    <option value="EXW">EXW (Ex Works)</option>
                                    <option value="DDP">DDP (Delivered Duty Paid)</option>
                                    <option value="DAP">DAP (Delivered at Place)</option>
                                    <option value="FCA">FCA (Free Carrier)</option>
                                </select>
                            </div>
                            <div class="config-field">
                                <label class="config-label">Moneda Principal</label>
                                <select v-model="empresa.currency"
                                    :class="['config-select', { 'config-input--readonly': !canEditEmpresa }]"
                                    :disabled="!canEditEmpresa">
                                    <option value="EUR">EUR (€)</option>
                                    <option value="USD">USD ($)</option>
                                    <option value="GBP">GBP (£)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Tab Seguridad -->
                <template v-if="activeTab === 'seguridad'">
                    <div class="config-card">
                        <h3 class="config-card-title">Correo Electrónico</h3>
                        <div class="config-field">
                            <label class="config-label">Correo actual</label>
                            <div v-if="!editingEmail" class="config-email-row">
                                <span class="config-email-value">{{ security.email }}</span>
                                <button class="config-edit-email-btn" @click="startEditEmail">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Editar
                                </button>
                            </div>
                            <div v-else class="config-email-edit-row">
                                <input v-model="emailDraft" type="email" class="config-input" />
                                <button class="config-update-btn" @click="saveEmail">Guardar</button>
                                <button class="config-cancel-btn" @click="cancelEditEmail">Cancelar</button>
                            </div>
                        </div>
                    </div>

                    <div class="config-card">
                        <h3 class="config-card-title">Cambiar Contraseña</h3>
                        <div class="config-field">
                            <label class="config-label">Contraseña Actual</label>
                            <input v-model="security.currentPassword" type="password" class="config-input" />
                        </div>
                        <div class="config-field">
                            <label class="config-label">Nueva Contraseña</label>
                            <input v-model="security.newPassword" type="password" class="config-input" />
                        </div>
                        <div class="config-field">
                            <label class="config-label">Confirmar Nueva Contraseña</label>
                            <input v-model="security.confirmPassword" type="password" class="config-input" />
                        </div>
                        <button class="config-update-btn" @click="handleUpdatePassword">Actualizar Contraseña</button>
                    </div>

                    <div class="config-card">
                        <h3 class="config-card-title">Políticas de Acceso</h3>

                        <div class="config-policy">
                            <div class="config-policy-info">
                                <span class="config-policy-title">Autenticación de Dos Factores (2FA)</span>
                                <span class="config-policy-desc">Requiere 2FA para todos los administradores.</span>
                            </div>
                            <label class="config-toggle">
                                <input type="checkbox" v-model="security.twoFactor" />
                                <span class="config-toggle-track"></span>
                            </label>
                        </div>

                        <div class="config-policy">
                            <div class="config-policy-info">
                                <span class="config-policy-title">Cierre de Sesión Automático</span>
                                <span class="config-policy-desc">Tiempo de inactividad antes de cerrar sesión.</span>
                            </div>
                            <select v-model="security.sessionTimeout" class="config-select config-select--small">
                                <option value="15">15 minutos</option>
                                <option value="30">30 minutos</option>
                                <option value="60">60 minutos</option>
                            </select>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
.config {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.config-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.config-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.config-header-btn {
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

.config-header-btn:hover {
    background: #0d2440;
}

/* Layout */
.config-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
    align-items: start;
}

/* Sidebar */
.config-sidebar {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.config-tab {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-secondary);
    text-align: left;
    transition: all 0.12s ease;
}

.config-tab:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.config-tab--active {
    background: #dbeafe;
    color: var(--accent-blue);
    font-weight: 600;
}

.config-tab--active:hover {
    background: #dbeafe;
    color: var(--accent-blue);
}

/* Content */
.config-content {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.config-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.config-card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.config-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.config-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.config-label {
    font-size: 12.5px;
    font-weight: 500;
    color: var(--text-secondary);
}

.config-input,
.config-select {
    height: 42px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0 14px;
    font-size: 14px;
    font-family: var(--font-family);
    color: var(--text-primary);
    background: var(--page-bg);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    appearance: auto;
}

.config-input:focus,
.config-select:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(26, 111, 181, 0.12);
    outline: none;
}

.config-select--small {
    width: auto;
    min-width: 140px;
}

.config-input--readonly {
    background: var(--page-bg);
    color: var(--text-secondary);
    cursor: default;
    pointer-events: none;
    border-color: transparent;
}

.config-readonly-notice {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #fef9c3;
    color: #92400e;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
}

/* Email field */
.config-email-row {
    display: flex;
    align-items: center;
    gap: 12px;
    height: 42px;
}

.config-email-value {
    font-size: 14px;
    color: var(--text-primary);
    flex: 1;
    padding: 0 14px;
    height: 42px;
    display: flex;
    align-items: center;
    background: var(--page-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
}

.config-edit-email-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 16px;
    background: var(--page-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    transition: all 0.12s ease;
    flex-shrink: 0;
}

.config-edit-email-btn:hover {
    background: #e2e5ea;
}

.config-email-edit-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.config-email-edit-row .config-input {
    flex: 1;
}

.config-update-btn {
    align-self: flex-start;
    padding: 10px 20px;
    background: var(--page-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    transition: all 0.12s ease;
    white-space: nowrap;
}

.config-update-btn:hover {
    background: #e2e5ea;
}

.config-cancel-btn {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    transition: all 0.12s ease;
    white-space: nowrap;
}

.config-cancel-btn:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

/* Policy rows */
.config-policy {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 0;
}

.config-policy+.config-policy {
    border-top: 1px solid var(--border-color);
}

.config-policy-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.config-policy-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
}

.config-policy-desc {
    font-size: 12.5px;
    color: var(--text-muted);
}

/* Toggle */
.config-toggle {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.config-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.config-toggle-track {
    position: absolute;
    inset: 0;
    background: #d1d5db;
    border-radius: 12px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.config-toggle-track::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: #ffffff;
    border-radius: 50%;
    transition: transform 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.config-toggle input:checked+.config-toggle-track {
    background: var(--sidebar-bg);
}

.config-toggle input:checked+.config-toggle-track::after {
    transform: translateX(20px);
}

@media (max-width: 900px) {
    .config-layout {
        grid-template-columns: 1fr;
    }

    .config-grid {
        grid-template-columns: 1fr;
    }
}
</style>
