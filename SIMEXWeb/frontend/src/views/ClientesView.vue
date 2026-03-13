<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import ClientesStats from '@/components/clientes/ClientesStats.vue'
import ClientesFilters from '@/components/clientes/ClientesFilters.vue'
import ClientesList from '@/components/clientes/ClientesList.vue'
import NuevaEmpresaModal from '@/components/clientes/NuevaEmpresaModal.vue'
import NuevoUsuarioModal from '@/components/clientes/NuevoUsuarioModal.vue'
import UsuariosList from '@/components/clientes/UsuariosList.vue'

const clientes = [
    {
        id: 1, company_name: 'Importaciones García S.L.', initial: 'I', vat_number: 'B12345678', address: 'Calle Mayor 15, 3º', country: 'España', postal_code: '28001', contact_name: 'Juan García López', email: 'jgarcia@importaciones.com', phone: '+34 912 345 678', active: true, operations: 5, lastActivity: 'Hace 2 horas',
        users: [
            { name: 'Juan García López', email: 'jgarcia@importaciones.com', position: 'Director Comercial', rol: 'cliente' },
            { name: 'María Pérez Ruiz', email: 'mperez@importaciones.com', position: 'Responsable Logística', rol: 'operador_logistico' },
        ],
    },
    {
        id: 2, company_name: 'Textiles Mediterráneo S.A.', initial: 'T', vat_number: 'A87654321', address: 'Av. del Puerto 42', country: 'España', postal_code: '46021', contact_name: 'Elena Martí Soler', email: 'emarti@textiles.com', phone: '+34 963 456 789', active: true, operations: 12, lastActivity: 'Hace 1 día',
        users: [
            { name: 'Elena Martí Soler', email: 'emarti@textiles.com', position: 'Gerente', rol: 'cliente' },
            { name: 'Carlos Vidal Pons', email: 'cvidal@textiles.com', position: 'Operador Comercial', rol: 'operador_comercial' },
            { name: 'Laura Gómez Mas', email: 'lgomez@textiles.com', position: 'Logística', rol: 'operador_logistico' },
        ],
    },
    {
        id: 3, company_name: 'Alimentación Ibérica S.L.', initial: 'A', vat_number: 'B11223344', address: 'Carrer de la Marina 200', country: 'España', postal_code: '08013', contact_name: 'Pere Casals Vidal', email: 'pcasals@alimentacion.com', phone: '+34 934 567 890', active: true, operations: 3, lastActivity: 'Hace 3 días',
        users: [
            { name: 'Pere Casals Vidal', email: 'pcasals@alimentacion.com', position: 'Director General', rol: 'administrador' },
            { name: 'Nuria Font Rius', email: 'nfont@alimentacion.com', position: 'Responsable Compras', rol: 'cliente' },
        ],
    },
    {
        id: 4, company_name: 'Electrónica Levante S.A.', initial: 'E', vat_number: 'A55667788', address: 'Pol. Ind. Las Atalayas, Nave 12', country: 'España', postal_code: '03114', contact_name: 'Rosa Díaz Fernández', email: 'rdiaz@electronica.com', phone: '+34 965 678 901', active: true, operations: 8, lastActivity: 'Hace 5 horas',
        users: [
            { name: 'Rosa Díaz Fernández', email: 'rdiaz@electronica.com', position: 'Directora de Operaciones', rol: 'cliente' },
            { name: 'Sergio Navarro Gil', email: 'snavarro@electronica.com', position: 'Técnico Logístico', rol: 'operador_logistico' },
        ],
    },
    {
        id: 5, company_name: 'Maquinaria Industrial Norte', initial: 'M', vat_number: 'B99887766', address: 'Gran Vía 28', country: 'España', postal_code: '48001', contact_name: 'Iñaki Etxeberria', email: 'ietxe@maquinaria.com', phone: '+34 944 789 012', active: false, operations: 1, lastActivity: 'Hace 1 semana',
        users: [
            { name: 'Iñaki Etxeberria', email: 'ietxe@maquinaria.com', position: 'Gerente', rol: 'cliente' },
        ],
    },
    {
        id: 6, company_name: 'Farmacéutica del Sur S.A.', initial: 'F', vat_number: 'A11122233', address: 'Av. de la Constitución 5', country: 'España', postal_code: '41001', contact_name: 'Carmen Ruiz Ortega', email: 'cruiz@farmasur.com', phone: '+34 954 890 123', active: true, operations: 4, lastActivity: 'Hace 2 días',
        users: [
            { name: 'Carmen Ruiz Ortega', email: 'cruiz@farmasur.com', position: 'Directora Comercial', rol: 'cliente' },
            { name: 'Antonio Morales Vega', email: 'amorales@farmasur.com', position: 'Operador Logístico', rol: 'operador_logistico' },
            { name: 'Pilar Santos Leal', email: 'psantos@farmasur.com', position: 'Administración', rol: 'administrador' },
        ],
    },
    {
        id: 7, company_name: 'Vinos y Licores Rioja S.L.', initial: 'V', vat_number: 'B44455566', address: 'Calle Portales 8', country: 'España', postal_code: '26001', contact_name: 'Miguel Ángel López', email: 'malopez@vinosrioja.com', phone: '+34 941 234 567', active: false, operations: 0, lastActivity: 'Hace 1 mes',
        users: [
            { name: 'Miguel Ángel López', email: 'malopez@vinosrioja.com', position: 'Propietario', rol: 'cliente' },
        ],
    },
    {
        id: 8, company_name: 'Autopartes Castilla S.A.', initial: 'A', vat_number: 'A77788899', address: 'Pol. Ind. San Cristóbal, Nave 5', country: 'España', postal_code: '47012', contact_name: 'Luis Hernández', email: 'lhernandez@autopartes.com', phone: '+34 983 345 678', active: true, operations: 2, lastActivity: 'Hace 4 días',
        users: [
            { name: 'Luis Hernández', email: 'lhernandez@autopartes.com', position: 'Director de Compras', rol: 'cliente' },
            { name: 'Paula Iglesias Ramos', email: 'piglesias@autopartes.com', position: 'Operadora Comercial', rol: 'operador_comercial' },
        ],
    },
    {
        id: 9, company_name: 'Logistics Partners S.L.', initial: 'L', vat_number: 'B33344455', address: 'Av. Diagonal 211', country: 'España', postal_code: '08018', contact_name: 'Sandra Torres', email: 'storres@logpartners.com', phone: '+34 932 111 222', active: true, operations: 7, lastActivity: 'Hace 1 día',
        users: [
            { name: 'Sandra Torres', email: 'storres@logpartners.com', position: 'CEO', rol: 'administrador' },
            { name: 'Marcos Blanco Fuertes', email: 'mblanco@logpartners.com', position: 'Operador Logístico', rol: 'operador_logistico' },
            { name: 'Raquel Ibáñez Costa', email: 'ribanez@logpartners.com', position: 'Comercial', rol: 'operador_comercial' },
        ],
    },
    {
        id: 10, company_name: 'Comercial Atlántico S.A.', initial: 'C', vat_number: 'A22233344', address: 'Calle Betanzos 60', country: 'España', postal_code: '15001', contact_name: 'Alberto Vázquez', email: 'avazquez@atlantico.com', phone: '+34 981 222 333', active: true, operations: 9, lastActivity: 'Hace 6 horas',
        users: [
            { name: 'Alberto Vázquez', email: 'avazquez@atlantico.com', position: 'Director General', rol: 'cliente' },
            { name: 'Cristina Loureiro Paz', email: 'cloureiro@atlantico.com', position: 'Responsable Operaciones', rol: 'operador_logistico' },
        ],
    },
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

const allUsers = computed(() => {
    const flat = clientes.flatMap((c) =>
        (c.users || []).map((u) => ({ ...u, company_name: c.company_name })),
    )
    if (!searchQuery.value.trim()) return flat
    const q = searchQuery.value.toLowerCase()
    return flat.filter(
        (u) =>
            u.name.toLowerCase().includes(q) ||
            u.email.toLowerCase().includes(q) ||
            u.company_name.toLowerCase().includes(q),
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
                            Nuevo Usuario
                        </button>
                    </div>
                </Transition>
            </div>
        </div>
        <ClientesStats />
        <ClientesFilters :active-tab="activeTab" :search-query="searchQuery" @update:active-tab="activeTab = $event"
            @update:search-query="searchQuery = $event" />
        <ClientesList v-if="activeTab === 'empresas'" :clientes="filteredClientes" />
        <UsuariosList v-else :usuarios="allUsers" />

        <!-- Modals -->
        <NuevaEmpresaModal :visible="showEmpresaModal" @close="showEmpresaModal = false"
            @submit="handleEmpresaSubmit" />
        <NuevoUsuarioModal :visible="showContactoModal" :empresas="empresaNames" @close="showContactoModal = false"
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
