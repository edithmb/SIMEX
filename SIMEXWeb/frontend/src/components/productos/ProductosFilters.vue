<script setup>
const props = defineProps({
    searchQuery: { type: String, default: '' },
    activeCategory: { type: String, default: 'Todas' },
    activeOrigin: { type: String, default: 'Cualquier Origen' },
})

const emit = defineEmits(['update:searchQuery', 'update:activeCategory', 'update:activeOrigin'])

const categories = ['Todas', 'Electrónica', 'Textil', 'Maquinaria', 'Alimentación', 'Química']
const origins = ['Cualquier Origen', 'China', 'India', 'Alemania', 'España', 'USA', 'Taiwán', 'Vietnam', 'Italia']
</script>

<template>
    <div class="productos-filters">
        <div class="productos-filters-search">
            <svg class="productos-filters-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" class="productos-filters-search-input" placeholder="Buscar por nombre o SKU..."
                :value="searchQuery" @input="emit('update:searchQuery', $event.target.value)" id="productos-search" />
        </div>

        <select class="productos-filters-select" :value="activeCategory"
            @change="emit('update:activeCategory', $event.target.value)">
            <option v-for="c in categories" :key="c" :value="c">{{ c === 'Todas' ? 'Todas las Categorías' : c }}
            </option>
        </select>

        <select class="productos-filters-select" :value="activeOrigin"
            @change="emit('update:activeOrigin', $event.target.value)">
            <option v-for="o in origins" :key="o" :value="o">{{ o }}</option>
        </select>
    </div>
</template>

<style scoped>
.productos-filters {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.productos-filters-search {
    position: relative;
    display: flex;
    align-items: center;
    flex: 1;
}

.productos-filters-search-icon {
    position: absolute;
    left: 14px;
    color: var(--text-muted);
    pointer-events: none;
}

.productos-filters-search-input {
    width: 100%;
    height: 40px;
    background: var(--page-bg);
    border-radius: 20px;
    padding: 0 16px 0 40px;
    font-size: 13px;
    color: var(--text-primary);
}

.productos-filters-search-input::placeholder {
    color: var(--text-muted);
}

.productos-filters-search-input:focus {
    box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.2);
}

.productos-filters-select {
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    font-size: 13px;
    font-family: var(--font-family);
    color: var(--text-primary);
    background: #ffffff;
    cursor: pointer;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .productos-filters {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
