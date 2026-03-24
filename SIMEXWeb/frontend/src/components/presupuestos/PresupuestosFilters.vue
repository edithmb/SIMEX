<script setup>
const props = defineProps({
    activeFilter: { type: String, default: 'Todos' },
    searchQuery: { type: String, default: '' },
})

const emit = defineEmits(['update:activeFilter', 'update:searchQuery'])

const filters = ['Todos', 'Borrador', 'Enviado', 'Aceptado', 'Rechazado']
</script>

<template>
    <div class="presupuestos-filters">
        <div class="presupuestos-filters-search">
            <svg class="presupuestos-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" class="presupuestos-filters-search-input"
                placeholder="Buscar por referencia o cliente..." :value="searchQuery"
                @input="emit('update:searchQuery', $event.target.value)" id="presupuestos-search" />
        </div>

        <div class="presupuestos-filters-buttons">
            <button v-for="f in filters" :key="f"
                :class="['presupuestos-filters-btn', { 'presupuestos-filters-btn--active': activeFilter === f }]"
                @click="emit('update:activeFilter', f)">
                {{ f }}
            </button>
        </div>
    </div>
</template>

<style scoped>
.presupuestos-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.presupuestos-filters-search {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
}

.presupuestos-filters-search-icon {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    pointer-events: none;
}

.presupuestos-filters-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.presupuestos-filters-search-input::placeholder {
    color: var(--text-muted);
}

.presupuestos-filters-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.presupuestos-filters-buttons {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.presupuestos-filters-btn {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    background: var(--page-bg);
    transition: all 0.15s ease;
}

.presupuestos-filters-btn:hover {
    color: var(--text-primary);
    background: #e2e5ea;
}

.presupuestos-filters-btn--active {
    background: var(--sidebar-bg);
    color: #ffffff;
}

.presupuestos-filters-btn--active:hover {
    background: var(--sidebar-bg);
    color: #ffffff;
}

@media (max-width: 900px) {
    .presupuestos-filters {
        flex-direction: column;
        align-items: stretch;
    }

    .presupuestos-filters-buttons {
        flex-wrap: wrap;
    }
}
</style>
