<script setup>
defineProps({
    clientes: { type: Array, required: true },
})

const avatarColors = ['#1a6fb5', '#047857', '#b45309', '#6d28d9', '#be185d', '#0f766e', '#4338ca', '#dc2626']

function getAvatarColor(index) {
    return avatarColors[index % avatarColors.length]
}
</script>

<template>
    <div class="clientes-list">
        <div v-for="(c, i) in clientes" :key="c.name" class="clientes-card">
            <div class="clientes-card-left">
                <div class="clientes-card-avatar" :style="{ background: getAvatarColor(i) }">
                    {{ c.initial }}
                </div>
                <div class="clientes-card-info">
                    <div class="clientes-card-top">
                        <span class="clientes-card-name">{{ c.name }}</span>
                        <span class="clientes-card-status"
                            :style="{ background: c.active ? '#d1fae5' : '#e5e7eb', color: c.active ? '#047857' : '#6b7280' }">
                            {{ c.active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div class="clientes-card-meta">
                        <span>CIF: {{ c.cif }}</span>
                        <span class="clientes-card-dot">●</span>
                        <span>{{ c.city }}</span>
                        <span class="clientes-card-dot">●</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <span>{{ c.contacts }} contacto{{ c.contacts !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
            <div class="clientes-card-right">
                <div class="clientes-card-metric">
                    <span class="clientes-card-metric-label">Operaciones</span>
                    <span class="clientes-card-metric-value">{{ c.operations }}</span>
                </div>
                <div class="clientes-card-metric">
                    <span class="clientes-card-metric-label">Última Actividad</span>
                    <span class="clientes-card-metric-value">{{ c.lastActivity }}</span>
                </div>
                <button class="clientes-card-expand" title="Expandir">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
            </div>
        </div>
        <div v-if="clientes.length === 0" class="clientes-empty">
            No se encontraron clientes con los filtros aplicados.
        </div>
    </div>
</template>

<style scoped>
.clientes-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.clientes-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 18px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    transition: box-shadow 0.15s ease;
}

.clientes-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.clientes-card-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
    flex: 1;
}

.clientes-card-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.clientes-card-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.clientes-card-top {
    display: flex;
    align-items: center;
    gap: 10px;
}

.clientes-card-name {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--text-primary);
}

.clientes-card-status {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.clientes-card-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--text-secondary);
    flex-wrap: wrap;
}

.clientes-card-dot {
    font-size: 6px;
    color: var(--text-muted);
}

.clientes-card-right {
    display: flex;
    align-items: center;
    gap: 28px;
    flex-shrink: 0;
}

.clientes-card-metric {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

.clientes-card-metric-label {
    font-size: 11px;
    color: var(--text-muted);
}

.clientes-card-metric-value {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
}

.clientes-card-expand {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    transition: all 0.12s ease;
}

.clientes-card-expand:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.clientes-empty {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
