<script setup>
import { ref, reactive, computed } from 'vue'
import { useRoleStore } from '@/stores/role'
import UsuariosStats from '@/components/usuarios/UsuariosStats.vue'
import UsuariosTable from '@/components/usuarios/UsuariosTable.vue'
import NuevoUsuarioModal from '@/components/usuarios/NuevoUsuarioModal.vue'

const roleStore = useRoleStore()

const usuarios = reactive([
    { id: 1, first_name: 'María', last_name: 'García', email: 'mgarcia@simex.com', phone_number: '+34 612 345 678', role_id: 1, roleName: 'Administrador', client_id: null, clientName: null, is_active: true, lastAccess: 'Hace 5 min' },
    { id: 2, first_name: 'Carlos', last_name: 'López', email: 'clopez@simex.com', phone_number: '+34 623 456 789', role_id: 2, roleName: 'Operador', client_id: null, clientName: null, is_active: true, lastAccess: 'Hace 2 horas' },
    { id: 3, first_name: 'Ana', last_name: 'Martínez', email: 'amartinez@simex.com', phone_number: '+34 634 567 890', role_id: 2, roleName: 'Operador', client_id: null, clientName: null, is_active: false, lastAccess: 'Hace 5 días' },
    { id: 4, first_name: 'Javier', last_name: 'Ruiz', email: 'jruiz@importaciones.com', phone_number: '+34 645 678 901', role_id: 3, roleName: 'Cliente', client_id: 1, clientName: 'Importaciones García S.L.', is_active: true, lastAccess: 'Ayer' },
    { id: 5, first_name: 'Laura', last_name: 'Sánchez', email: 'lsanchez@textiles.com', phone_number: '+34 656 789 012', role_id: 3, roleName: 'Cliente', client_id: 2, clientName: 'Textiles Mediterráneo S.A.', is_active: true, lastAccess: 'Hace 3 horas' },
    { id: 6, first_name: 'Pedro', last_name: 'Fernández', email: 'pfernandez@simex.com', phone_number: '+34 667 890 123', role_id: 2, roleName: 'Operador', client_id: null, clientName: null, is_active: true, lastAccess: 'Hace 1 hora' },
    { id: 7, first_name: 'Isabel', last_name: 'Torres', email: 'itorres@alimentacion.com', phone_number: '+34 678 901 234', role_id: 3, roleName: 'Cliente', client_id: 3, clientName: 'Alimentación Ibérica S.L.', is_active: true, lastAccess: 'Hace 2 días' },
])

const searchQuery = ref('')
const filterRole = ref('Todos')
const filterClient = ref('Todos')
const filterStatus = ref('Todos')

const roleOptions = ['Todos', 'Administrador', 'Operador', 'Cliente']

const clientOptions = computed(() => {
    const clients = usuarios
        .filter((u) => u.clientName)
        .map((u) => u.clientName)
    return ['Todos', ...new Set(clients)]
})

const statusOptions = ['Todos', 'Activo', 'Inactivo']

const filteredUsuarios = computed(() => {
    return usuarios.filter((u) => {
        // Search
        if (searchQuery.value.trim()) {
            const q = searchQuery.value.toLowerCase()
            const fullName = (u.first_name + ' ' + u.last_name).toLowerCase()
            if (!fullName.includes(q) && !u.email.toLowerCase().includes(q)) return false
        }
        // Role
        if (filterRole.value !== 'Todos' && u.roleName !== filterRole.value) return false
        // Client
        if (filterClient.value !== 'Todos') {
            if (!u.clientName || u.clientName !== filterClient.value) return false
        }
        // Status
        if (filterStatus.value !== 'Todos') {
            const isActive = filterStatus.value === 'Activo'
            if (u.is_active !== isActive) return false
        }
        return true
    })
})

const showModal = ref(false)

function handleSubmit(data) {
    console.log('Nuevo usuario:', data)
    showModal.value = false
}
</script>

<template>
    <div class="usuarios">
        <div class="usuarios-header">
            <h2 class="usuarios-header-title">Gestión de Usuarios</h2>
            <button class="usuarios-header-btn" @click="showModal = true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Nuevo Usuario
            </button>
        </div>
        <UsuariosStats />

        <!-- Filters -->
        <div class="usuarios-filters">
            <div class="usuarios-filters-search">
                <svg class="usuarios-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" class="usuarios-filters-search-input"
                    placeholder="Buscar por nombre o email..." v-model="searchQuery" />
            </div>
            <select v-model="filterRole" class="usuarios-filters-select">
                <option v-for="r in roleOptions" :key="r" :value="r">{{ r === 'Todos' ? 'Todos los roles' : r }}</option>
            </select>
            <select v-model="filterClient" class="usuarios-filters-select">
                <option v-for="c in clientOptions" :key="c" :value="c">{{ c === 'Todos' ? 'Todos los clientes' : c }}</option>
            </select>
            <select v-model="filterStatus" class="usuarios-filters-select">
                <option v-for="s in statusOptions" :key="s" :value="s">{{ s === 'Todos' ? 'Todos los estados' : s }}</option>
            </select>
        </div>

        <UsuariosTable :usuarios="filteredUsuarios" />

        <NuevoUsuarioModal :visible="showModal" @close="showModal = false" @submit="handleSubmit" />
    </div>
</template>

<style scoped>
.usuarios {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.usuarios-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.usuarios-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.usuarios-header-btn {
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

.usuarios-header-btn:hover {
    background: #0d2440;
}

/* Filters */
.usuarios-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.usuarios-filters-search {
    position: relative;
    flex: 1;
    display: flex;
    align-items: center;
}

.usuarios-filters-search-icon {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    pointer-events: none;
}

.usuarios-filters-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.usuarios-filters-search-input::placeholder {
    color: var(--text-muted);
}

.usuarios-filters-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.usuarios-filters-select {
    height: 40px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0 12px;
    font-size: 13px;
    background: var(--card-bg);
    color: var(--text-primary);
    appearance: auto;
}

.usuarios-filters-select:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(26, 111, 181, 0.12);
    outline: none;
}
</style>
