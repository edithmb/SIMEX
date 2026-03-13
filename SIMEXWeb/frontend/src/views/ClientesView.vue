<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import ClientesStats from '@/components/clientes/ClientesStats.vue'
import ClientesFilters from '@/components/clientes/ClientesFilters.vue'
import ClientesList from '@/components/clientes/ClientesList.vue'
import NuevaEmpresaModal from '@/components/clientes/NuevaEmpresaModal.vue'
import NuevoContactoModal from '@/components/clientes/NuevoContactoModal.vue'

const clientes = [
    { name: 'Importaciones García S.L.', initial: 'I', cif: 'B12345678', city: 'Madrid', contacts: 2, active: true, operations: 5, lastActivity: 'Hace 2 horas' },
    { name: 'Textiles Mediterráneo S.A.', initial: 'T', cif: 'A87654321', city: 'Valencia', contacts: 3, active: true, operations: 12, lastActivity: 'Hace 1 día' },
    { name: 'Alimentación Ibérica S.L.', initial: 'A', cif: 'B11223344', city: 'Barcelona', contacts: 1, active: true, operations: 3, lastActivity: 'Hace 3 días' },
    { name: 'Electrónica Levante S.A.', initial: 'E', cif: 'A55667788', city: 'Alicante', contacts: 2, active: true, operations: 8, lastActivity: 'Hace 5 horas' },
    { name: 'Maquinaria Industrial Norte', initial: 'M', cif: 'B99887766', city: 'Bilbao', contacts: 1, active: false, operations: 1, lastActivity: 'Hace 1 semana' },
    { name: 'Farmacéutica del Sur S.A.', initial: 'F', cif: 'A11122233', city: 'Sevilla', contacts: 1, active: true, operations: 4, lastActivity: 'Hace 2 días' },
    { name: 'Vinos y Licores Rioja S.L.', initial: 'V', cif: 'B44455566', city: 'Logroño', contacts: 1, active: false, operations: 0, lastActivity: 'Hace 1 mes' },
    { name: 'Autopartes Castilla S.A.', initial: 'A', cif: 'A77788899', city: 'Valladolid', contacts: 1, active: true, operations: 2, lastActivity: 'Hace 4 días' },
]

const empresaNames = clientes.map((c) => c.name)

const activeTab = ref('empresas')
const searchQuery = ref('')

const filteredClientes = computed(() => {
    if (!searchQuery.value.trim()) return clientes
    const q = searchQuery.value.toLowerCase()
    return clientes.filter(
        (c) => c.name.toLowerCase().includes(q) || c.cif.toLowerCase().includes(q) || c.city.toLowerCase().includes(q),
    )
})

// Dropdown
const showDropdown = ref(false)

function toggleDropdown() {
    showDropdown.value = !showDropdown.value
}

function closeDropdownOutside(e) {
    if (showDropdown.value && !e.target.closest('.clientes-header-wrapper')) {
        showDropdown.value = false
    }
}

onMounted(() => document.addEventListener('click', closeDropdownOutside))
onUnmounted(() => document.removeEventListener('click', closeDropdownOutside))

// Modals
const showEmpresaModal = ref(false)
const showContactoModal = ref(false)

function openEmpresaModal() {
    showDropdown.value = false
    showEmpresaModal.value = true
}

function openContactoModal() {
    showDropdown.value = false
    showContactoModal.value = true
}

function handleEmpresaSubmit(data) {
    console.log('Nueva empresa:', data)
    showEmpresaModal.value = false
}

function handleContactoSubmit(data) {
    console.log('Nuevo contacto:', data)
    showContactoModal.value = false
}
</script>

<template>
    <div class="clientes">
        <div class="clientes-header">
            <h2 class="clientes-header-title">Gestión de Clientes</h2>
            <div class="clientes-header-wrapper">
                <button class="clientes-header-btn" @click="toggleDropdown">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Añadir Nuevo
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <Transition name="dropdown">
                    <div v-if="showDropdown" class="clientes-dropdown">
                        <button class="clientes-dropdown-item" @click="openEmpresaModal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            Nueva Empresa
                        </button>
                        <button class="clientes-dropdown-item" @click="openContactoModal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Nuevo Contacto
                        </button>
                    </div>
                </Transition>
            </div>
        </div>
        <ClientesStats />
        <ClientesFilters :active-tab="activeTab" :search-query="searchQuery" @update:active-tab="activeTab = $event"
            @update:search-query="searchQuery = $event" />
        <ClientesList :clientes="filteredClientes" />

        <!-- Modals -->
        <NuevaEmpresaModal :visible="showEmpresaModal" @close="showEmpresaModal = false"
            @submit="handleEmpresaSubmit" />
        <NuevoContactoModal :visible="showContactoModal" :empresas="empresaNames" @close="showContactoModal = false"
            @submit="handleContactoSubmit" />
    </div>
</template>

<style scoped>
.clientes {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.clientes-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.clientes-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.clientes-header-wrapper {
    position: relative;
}

.clientes-header-btn {
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

.clientes-header-btn:hover {
    background: #0d2440;
}

/* Dropdown */
.clientes-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14);
    min-width: 200px;
    padding: 6px;
    z-index: 100;
}

.clientes-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-primary);
    transition: background 0.12s ease;
}

.clientes-dropdown-item:hover {
    background: var(--page-bg);
}

/* Dropdown transition */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
