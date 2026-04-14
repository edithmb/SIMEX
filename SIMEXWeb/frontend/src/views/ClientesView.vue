<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import ClientesStats from '@/components/clientes/ClientesStats.vue'
import ClientesFilters from '@/components/clientes/ClientesFilters.vue'
import ClientesList from '@/components/clientes/ClientesList.vue'
import NuevaEmpresaModal from '@/components/clientes/NuevaEmpresaModal.vue'
import NuevoUsuarioModal from '@/components/clientes/NuevoUsuarioModal.vue'
import UsuariosList from '@/components/clientes/UsuariosList.vue'
import Spinner from '@/components/common/Spinner.vue'

const LARAVEL = import.meta.env.VITE_LARAVEL_API
const NET = import.meta.env.VITE_NET_API

const auth = useAuthStore()
const router = useRouter()
const headers = { Authorization: `Bearer ${auth.token}` }

const clientes = ref([])
const roles = ref([])
const loading = ref(false)
const submittingEmpresa = ref(false)
const submittingUsuario = ref(false)

const activeTab = ref('empresas')
const searchQuery = ref('')

const filteredClientes = computed(() => {
    if (!searchQuery.value.trim()) return clientes.value
    const q = searchQuery.value.toLowerCase()
    return clientes.value.filter(
        (c) =>
            c.company_name?.toLowerCase().includes(q) ||
            c.vat_number?.toLowerCase().includes(q) ||
            c.country?.toLowerCase().includes(q),
    )
})

const allUsers = computed(() => {
    const flat = clientes.value.flatMap((c) =>
        (c.users || []).map((u) => ({ ...u, company_name: c.company_name })),
    )
    if (!searchQuery.value.trim()) return flat
    const q = searchQuery.value.toLowerCase()
    return flat.filter(
        (u) =>
            u.name?.toLowerCase().includes(q) ||
            u.email?.toLowerCase().includes(q) ||
            u.company_name?.toLowerCase().includes(q),
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

function mapCliente(c) {
    return {
        ...c,
        initial: (c.company_name || '?')[0].toUpperCase(),
        active: true,
        operations: '—',
        lastActivity: '—',
    }
}

async function fetchClientes() {
    try {
        const res = await axios.get(LARAVEL + '/clients', { headers })
        clientes.value = res.data.map(mapCliente)
    } catch (error) {
        if (error.response?.status === 401) {
            await auth.logout()
            router.push({ name: 'login' })
        }
    }
}

async function fetchRoles() {
    try {
        const res = await axios.get(LARAVEL + '/roles', { headers })
        roles.value = res.data
    } catch {
        // Si falla, seguimos sin roles precargados
    }
}

onMounted(async () => {
    document.addEventListener('click', closeDropdownOutside)
    loading.value = true
    try {
        await Promise.all([fetchClientes(), fetchRoles()])
    } finally {
        loading.value = false
    }
})
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

async function handleEmpresaSubmit(data) {
    if (submittingEmpresa.value) return
    submittingEmpresa.value = true
    try {
        await axios.post(NET + '/Clients', {
            companyName: data.company_name,
            vatNumber: data.vat_number,
            address: data.address,
            country: data.country,
            postalCode: data.postal_code,
            contactName: data.contact_name,
            email: data.email,
            phone: data.phone,
        })
        showEmpresaModal.value = false
        await fetchClientes()
    } catch (error) {
        console.error('Error al crear empresa:', error)
    } finally {
        submittingEmpresa.value = false
    }
}

async function handleContactoSubmit(data) {
    if (submittingUsuario.value) return
    submittingUsuario.value = true
    try {
        await axios.post(NET + '/Users', {
            firstName: data.first_name,
            lastName: data.last_name,
            email: data.email,
            phoneNumber: data.phone,
            passwordHash: data.password,
            roleId: data.role_id || null,
            clientId: data.empresa_id || null,
            isActive: true,
        })
        showContactoModal.value = false
        await fetchClientes()
    } catch (error) {
        console.error('Error al crear usuario:', error)
    } finally {
        submittingUsuario.value = false
    }
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
        <ClientesStats :total-empresas="clientes.length" :total-usuarios="allUsers.length" />
        <ClientesFilters :active-tab="activeTab" :search-query="searchQuery" @update:active-tab="activeTab = $event"
            @update:search-query="searchQuery = $event" />

        <div v-if="loading" class="view-loading">
            <Spinner :size="40" />
        </div>
        <template v-else>
            <ClientesList v-if="activeTab === 'empresas'" :clientes="filteredClientes" />
            <UsuariosList v-else :usuarios="allUsers" />
        </template>

        <!-- Modals -->
        <NuevaEmpresaModal :visible="showEmpresaModal" :submitting="submittingEmpresa"
            @close="showEmpresaModal = false" @submit="handleEmpresaSubmit" />
        <NuevoUsuarioModal :visible="showContactoModal" :empresas="clientes" :roles="roles"
            :submitting="submittingUsuario"
            @close="showContactoModal = false" @submit="handleContactoSubmit" />
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

.view-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 0;
    color: var(--accent-blue);
}
</style>
