<script setup>
defineProps({
    usuarios: { type: Array, required: true },
})

const rolColors = {
    Administrador: { bg: '#dbeafe', color: '#1a6fb5' },
    Operador: { bg: '#e5e7eb', color: '#4b5563' },
    Cliente: { bg: '#e5e7eb', color: '#4b5563' },
}

const statusColors = {
    Activo: { bg: '#d1fae5', color: '#047857' },
    Inactivo: { bg: '#e5e7eb', color: '#6b7280' },
}

const avatarColors = ['#1a6fb5', '#047857', '#b45309', '#6d28d9', '#be185d']

function getRolStyle(rol) {
    return rolColors[rol] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getStatusStyle(status) {
    return statusColors[status] || { bg: '#e5e7eb', color: '#6b7280' }
}

function getAvatarColor(i) {
    return avatarColors[i % avatarColors.length]
}
</script>

<template>
    <div class="usuarios-table-wrapper">
        <table class="usuarios-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(u, i) in usuarios" :key="u.email">
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar" :style="{ background: getAvatarColor(i) }">{{ u.initial }}</div>
                            <div class="user-info">
                                <span class="user-name">{{ u.name }}</span>
                                <span class="user-email">{{ u.email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="user-badge"
                            :style="{ background: getRolStyle(u.rol).bg, color: getRolStyle(u.rol).color }">
                            {{ u.rol }}
                        </span>
                    </td>
                    <td>
                        <span class="user-badge"
                            :style="{ background: getStatusStyle(u.status).bg, color: getStatusStyle(u.status).color }">
                            {{ u.status }}
                        </span>
                    </td>
                    <td><span class="user-access">{{ u.lastAccess }}</span></td>
                    <td>
                        <button class="user-menu-btn" title="Opciones">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="5" r="1" />
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="19" r="1" />
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr v-if="usuarios.length === 0">
                    <td colspan="5" class="user-empty">No se encontraron usuarios.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.usuarios-table-wrapper {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
}

.usuarios-table {
    width: 100%;
    border-collapse: collapse;
}

.usuarios-table thead th {
    text-align: left;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.usuarios-table tbody tr {
    transition: background 0.12s ease;
}

.usuarios-table tbody tr:hover {
    background: #f8f9fb;
}

.usuarios-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.usuarios-table tbody tr:last-child td {
    border-bottom: none;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.user-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
}

.user-email {
    font-size: 12px;
    color: var(--text-muted);
}

.user-badge {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.user-access {
    font-size: 13px;
    color: var(--text-secondary);
    white-space: nowrap;
}

.user-menu-btn {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    transition: all 0.12s ease;
}

.user-menu-btn:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.user-empty {
    text-align: center;
    padding: 40px 16px !important;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
