<script setup>
import { ref } from 'vue'

defineProps({
    clientes: { type: Array, required: true },
})

const avatarColors = ['#1a6fb5', '#047857', '#b45309', '#6d28d9', '#be185d', '#0f766e', '#4338ca', '#dc2626']

function getAvatarColor(index) {
    return avatarColors[index % avatarColors.length]
}

const expandedId = ref(null)

function toggleExpand(id) {
    expandedId.value = expandedId.value === id ? null : id
}

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
</script>

<template>
    <div class="clientes-list">
        <div v-for="(c, i) in clientes" :key="c.company_name" class="clientes-card">
            <div class="clientes-card-main">
                <div class="clientes-card-left">
                    <div class="clientes-card-avatar" :style="{ background: getAvatarColor(i) }">
                        {{ c.initial }}
                    </div>
                    <div class="clientes-card-info">
                        <div class="clientes-card-top">
                            <span class="clientes-card-name">{{ c.company_name }}</span>
                            <span class="clientes-card-status"
                                :style="{ background: c.active ? '#d1fae5' : '#e5e7eb', color: c.active ? '#047857' : '#6b7280' }">
                                {{ c.active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                        <div class="clientes-card-meta">
                            <span>NIF: {{ c.vat_number }}</span>
                            <span class="clientes-card-dot">●</span>
                            <span>{{ c.country }}</span>
                            <span class="clientes-card-dot">●</span>
                            <span>{{ c.postal_code }}</span>
                            <span class="clientes-card-dot">●</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <span>{{ c.contact_name }}</span>
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
                    <button
                        class="clientes-card-expand"
                        :class="{ 'clientes-card-expand--open': expandedId === c.id }"
                        :title="expandedId === c.id ? 'Contraer' : 'Expandir'"
                        @click="toggleExpand(c.id)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Usuarios expandidos -->
            <Transition name="expand">
                <div v-if="expandedId === c.id" class="clientes-card-users">
                    <div class="clientes-card-users-header">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        Usuarios ({{ c.users?.length ?? 0 }})
                    </div>
                    <div v-if="c.users && c.users.length > 0" class="clientes-card-users-list">
                        <div v-for="u in c.users" :key="u.email" class="clientes-user-row">
                            <div class="clientes-user-info">
                                <span class="clientes-user-name">{{ u.name }}</span>
                                <span class="clientes-user-email">{{ u.email }}</span>
                            </div>
                            <span class="clientes-user-position">{{ u.position }}</span>
                            <span class="clientes-user-rol"
                                :style="{ background: rolColors[u.rol]?.bg, color: rolColors[u.rol]?.color }">
                                {{ rolLabels[u.rol] ?? u.rol }}
                            </span>
                        </div>
                    </div>
                    <p v-else class="clientes-users-empty">Sin usuarios registrados.</p>
                </div>
            </Transition>
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
    overflow: hidden;
    transition: box-shadow 0.15s ease;
}

.clientes-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.clientes-card-main {
    padding: 18px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
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
    transition: all 0.2s ease;
}

.clientes-card-expand--open {
    transform: rotate(180deg);
    color: var(--text-primary);
}

.clientes-card-expand:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

/* Usuarios expandidos */
.clientes-card-users {
    border-top: 1px solid var(--border-color);
    padding: 16px 22px 18px;
    background: var(--page-bg);
}

.clientes-card-users-header {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.clientes-card-users-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.clientes-user-row {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #ffffff;
    border-radius: 8px;
    padding: 10px 14px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}

.clientes-user-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.clientes-user-name {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-primary);
}

.clientes-user-email {
    font-size: 12px;
    color: var(--text-muted);
}

.clientes-user-position {
    font-size: 12.5px;
    color: var(--text-secondary);
    flex-shrink: 0;
}

.clientes-user-rol {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

.clientes-users-empty {
    font-size: 13px;
    color: var(--text-muted);
    text-align: center;
    padding: 12px 0;
}

/* Transición expand */
.expand-enter-active,
.expand-leave-active {
    transition: opacity 0.18s ease, transform 0.18s ease;
    transform-origin: top;
}

.expand-enter-from,
.expand-leave-to {
    opacity: 0;
    transform: scaleY(0.95);
}

.clientes-empty {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
    font-size: 14px;
}
</style>
