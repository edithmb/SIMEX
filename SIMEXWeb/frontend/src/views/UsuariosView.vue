<script setup>
import { ref } from 'vue'
import UsuariosStats from '@/components/usuarios/UsuariosStats.vue'
import UsuariosTable from '@/components/usuarios/UsuariosTable.vue'
import NuevoUsuarioModal from '@/components/usuarios/NuevoUsuarioModal.vue'

const usuarios = [
    { name: 'María García', initial: 'M', email: 'mgarcia@logitrac.com', rol: 'Administrador', status: 'Activo', lastAccess: 'Hace 5 min' },
    { name: 'Carlos López', initial: 'C', email: 'clopez@logitrac.com', rol: 'Operador', status: 'Activo', lastAccess: 'Hace 2 horas' },
    { name: 'Ana Martínez', initial: 'A', email: 'amartinez@logitrac.com', rol: 'Operador', status: 'Inactivo', lastAccess: 'Hace 5 días' },
    { name: 'Javier Ruiz', initial: 'J', email: 'jruiz@cliente.com', rol: 'Cliente', status: 'Activo', lastAccess: 'Ayer' },
    { name: 'Laura Sánchez', initial: 'L', email: 'lsanchez@cliente.com', rol: 'Cliente', status: 'Activo', lastAccess: 'Hace 3 horas' },
]

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
        <UsuariosTable :usuarios="usuarios" />

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
</style>
