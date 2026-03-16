<script setup>
const props = defineProps({
    activeTab: { type: String, default: 'empresas' },
    searchQuery: { type: String, default: '' },
})

const emit = defineEmits(['update:activeTab', 'update:searchQuery'])
</script>

<template>
    <div class="clientes-filters">
        <div class="clientes-filters-search">
            <svg class="clientes-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" class="clientes-filters-search-input"
                placeholder="Buscar empresa por nombre, CIF o ciudad..." :value="searchQuery"
                @input="emit('update:searchQuery', $event.target.value)" id="clientes-search" />
        </div>

        <div class="clientes-filters-tabs">
            <button :class="['clientes-filters-tab', { 'clientes-filters-tab--active': activeTab === 'empresas' }]"
                @click="emit('update:activeTab', 'empresas')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Empresas
            </button>
            <button :class="['clientes-filters-tab', { 'clientes-filters-tab--active': activeTab === 'contactos' }]"
                @click="emit('update:activeTab', 'contactos')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Todos los Usuarios
            </button>
        </div>
    </div>
</template>

<style scoped>
.clientes-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.clientes-filters-search {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
}

.clientes-filters-search-icon {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    pointer-events: none;
}

.clientes-filters-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.clientes-filters-search-input::placeholder {
    color: var(--text-muted);
}

.clientes-filters-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.clientes-filters-tabs {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.clientes-filters-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
    background: #ffffff;
    transition: all 0.15s ease;
}

.clientes-filters-tab:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.clientes-filters-tab--active {
    background: var(--page-bg);
    color: var(--text-primary);
    font-weight: 600;
    border-color: var(--text-secondary);
}

@media (max-width: 768px) {
    .clientes-filters {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
