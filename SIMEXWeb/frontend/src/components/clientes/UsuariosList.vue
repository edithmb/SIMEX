<script setup>
defineProps({
    usuarios: { type: Array, required: true },
})

const rolLabels = {
    operador_logistico: 'Operador Logístico',
    operador_comercial: 'Operador Comercial',
    cliente: 'Cliente',
    administrador: 'Administrador',
}

const rolColors = {
    operador_logistico: { bg: '#dbeafe', color: '#1d4ed8' },
    operador_comercial: { bg: '#fef9c3', color: '#92400e' },
    cliente: { bg: '#d1fae5', color: '#047857' },
    administrador: { bg: '#ede9fe', color: '#5b21b6' },
}

function getInitial(name) {
    return name?.charAt(0).toUpperCase() ?? '?'
}
</script>

<template>
    <div class="usuarios-list">
        <div v-for="u in usuarios" :key="u.email" class="usuario-card">
            <div class="usuario-avatar">{{ getInitial(u.name) }}</div>
            <div class="usuario-info">
                <span class="usuario-name">{{ u.name }}</span>
                <span class="usuario-email">{{ u.email }}</span>
            </div>
            <span class="usuario-company">{{ u.company_name }}</span>
            <span class="usuario-position">{{ u.position }}</span>
            <span class="usuario-rol"
                :style="{ background: rolColors[u.rol]?.bg, color: rolColors[u.rol]?.color }">
                {{ rolLabels[u.rol] ?? u.rol }}
            </span>
        </div>
        <div v-if="usuarios.length === 0" class="usuarios-empty">
            No se encontraron usuarios con los filtros aplicados.
        </div>
    </div>
</template>

<style scoped>
.usuarios-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.usuario-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 14px 22px;
    display: grid;
    grid-template-columns: 38px 1fr 200px 180px 160px;
    align-items: center;
    gap: 16px;
    transition: box-shadow 0.15s ease;
}

.usuario-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.usuario-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--sidebar-bg);
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.usuario-info {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.usuario-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-email {
    font-size: 12px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-company {
    font-size: 13px;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-position {
    font-size: 13px;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.usuario-rol {
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
    text-align: center;
}

.usuarios-empty {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
