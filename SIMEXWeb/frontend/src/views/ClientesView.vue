<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import ClientesStats from '@/components/clientes/ClientesStats.vue'
import ClientesFilters from '@/components/clientes/ClientesFilters.vue'
import ClientesList from '@/components/clientes/ClientesList.vue'
import NuevaEmpresaModal from '@/components/clientes/NuevaEmpresaModal.vue'
import NuevoContactoModal from '@/components/clientes/NuevoContactoModal.vue'

const clientes = [
    { id: 1, company_name: 'Importaciones García S.L.', initial: 'I', vat_number: 'B12345678', address: 'Calle Mayor 15, 3º', country: 'España', postal_code: '28001', contact_name: 'Juan García López', email: 'jgarcia@importaciones.com', phone: '+34 912 345 678', active: true, operations: 5, lastActivity: 'Hace 2 horas' },
    { id: 2, company_name: 'Textiles Mediterráneo S.A.', initial: 'T', vat_number: 'A87654321', address: 'Av. del Puerto 42', country: 'España', postal_code: '46021', contact_name: 'Elena Martí Soler', email: 'emarti@textiles.com', phone: '+34 963 456 789', active: true, operations: 12, lastActivity: 'Hace 1 día' },
    { id: 3, company_name: 'Alimentación Ibérica S.L.', initial: 'A', vat_number: 'B11223344', address: 'Carrer de la Marina 200', country: 'España', postal_code: '08013', contact_name: 'Pere Casals Vidal', email: 'pcasals@alimentacion.com', phone: '+34 934 567 890', active: true, operations: 3, lastActivity: 'Hace 3 días' },
    { id: 4, company_name: 'Electrónica Levante S.A.', initial: 'E', vat_number: 'A55667788', address: 'Pol. Ind. Las Atalayas, Nave 12', country: 'España', postal_code: '03114', contact_name: 'Rosa Díaz Fernández', email: 'rdiaz@electronica.com', phone: '+34 965 678 901', active: true, operations: 8, lastActivity: 'Hace 5 horas' },
    { id: 5, company_name: 'Maquinaria Industrial Norte', initial: 'M', vat_number: 'B99887766', address: 'Gran Vía 28', country: 'España', postal_code: '48001', contact_name: 'Iñaki Etxeberria', email: 'ietxe@maquinaria.com', phone: '+34 944 789 012', active: false, operations: 1, lastActivity: 'Hace 1 semana' },
    { id: 6, company_name: 'Farmacéutica del Sur S.A.', initial: 'F', vat_number: 'A11122233', address: 'Av. de la Constitución 5', country: 'España', postal_code: '41001', contact_name: 'Carmen Ruiz Ortega', email: 'cruiz@farmasur.com', phone: '+34 954 890 123', active: true, operations: 4, lastActivity: 'Hace 2 días' },
    { id: 7, company_name: 'Vinos y Licores Rioja S.L.', initial: 'V', vat_number: 'B44455566', address: 'Calle Portales 8', country: 'España', postal_code: '26001', contact_name: 'Miguel Ángel López', email: 'malopez@vinosrioja.com', phone: '+34 941 234 567', active: false, operations: 0, lastActivity: 'Hace 1 mes' },
    { id: 8, company_name: 'Autopartes Castilla S.A.', initial: 'A', vat_number: 'A77788899', address: 'Pol. Ind. San Cristóbal, Nave 5', country: 'España', postal_code: '47012', contact_name: 'Luis Hernández', email: 'lhernandez@autopartes.com', phone: '+34 983 345 678', active: true, operations: 2, lastActivity: 'Hace 4 días' },
]

const empresaNames = clientes.map((c) => c.company_name)

const activeTab = ref('empresas')
const searchQuery = ref('')

const filteredClientes = computed(() => {
    if (!searchQuery.value.trim()) return clientes
    const q = searchQuery.value.toLowerCase()
    return clientes.filter(
        (c) => c.company_name.toLowerCase().includes(q) || c.vat_number.toLowerCase().includes(q) || c.country.toLowerCase().includes(q),
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
