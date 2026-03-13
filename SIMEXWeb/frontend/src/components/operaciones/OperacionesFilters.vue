<script setup>
const props = defineProps({
    activeTransport: { type: String, default: 'Todos' },
    activeStatus: { type: String, default: 'Todos los Estados' },
    searchQuery: { type: String, default: '' },
})

const emit = defineEmits(['update:activeTransport', 'update:activeStatus', 'update:searchQuery'])

const transportFilters = [
    { key: 'Todos', label: '✈ Todos' },
    { key: 'ship', label: '🚢 Marítimo' },
    { key: 'plane', label: '✈️ Aéreo' },
    { key: 'truck', label: '🚚 Terrestre' },
]

const statusOptions = [
    'Todos los Estados',
    'Preparación',
    'En Tránsito',
    'En Aduana',
    'Entregado',
    'Incidencia',
]
</script>

<template>
    <div class="operaciones-filters">
        <div class="operaciones-filters-search">
            <svg class="operaciones-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" class="operaciones-filters-search-input" placeholder="Buscar por referencia o cliente..."
                :value="searchQuery" @input="emit('update:searchQuery', $event.target.value)" id="operaciones-search" />
        </div>

        <div class="operaciones-filters-buttons">
            <button v-for="t in transportFilters" :key="t.key"
                :class="['operaciones-filters-btn', { 'operaciones-filters-btn--active': activeTransport === t.key }]"
                @click="emit('update:activeTransport', t.key)">
                {{ t.label }}
            </button>
        </div>

        <select class="operaciones-filters-select" :value="activeStatus"
            @change="emit('update:activeStatus', $event.target.value)">
            <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
        </select>
    </div>
</template>

<style scoped>
.operaciones-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.operaciones-filters-search {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
}

.operaciones-filters-search-icon {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    pointer-events: none;
}

.operaciones-filters-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.operaciones-filters-search-input::placeholder {
    color: var(--text-muted);
}

.operaciones-filters-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.operaciones-filters-buttons {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.operaciones-filters-btn {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--page-bg);
    transition: all 0.15s ease;
}

.operaciones-filters-btn:hover {
    color: var(--text-primary);
    background: #e2e5ea;
}

.operaciones-filters-btn--active {
    background: var(--sidebar-bg);
    color: #ffffff;
}

.operaciones-filters-btn--active:hover {
    background: var(--sidebar-bg);
    color: #ffffff;
}

.operaciones-filters-select {
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 13px;
    font-family: var(--font-family);
    color: var(--text-primary);
    background: #ffffff;
    cursor: pointer;
    flex-shrink: 0;
}

@media (max-width: 900px) {
    .operaciones-filters {
        flex-direction: column;
        align-items: stretch;
    }

    .operaciones-filters-buttons {
        flex-wrap: wrap;
    }
}
</style>
