<script setup>
defineProps({
    productos: { type: Array, required: true },
})

const categoryColors = {
    Electrónica: { bg: '#dbeafe', color: '#1a6fb5' },
    Textil: { bg: '#d1fae5', color: '#047857' },
    Maquinaria: { bg: '#e5e7eb', color: '#4b5563' },
    Alimentación: { bg: '#fef3c7', color: '#b45309' },
    Química: { bg: '#ede9fe', color: '#6d28d9' },
}

function getCategoryStyle(category) {
    return categoryColors[category] || { bg: '#e5e7eb', color: '#6b7280' }
}
</script>

<template>
    <div class="productos-grid">
        <div v-for="p in productos" :key="p.sku" class="productos-card">
            <div class="productos-card-top">
                <div class="productos-card-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <button class="productos-card-menu" title="Opciones">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </button>
            </div>

            <span class="productos-card-sku">{{ p.sku }}</span>
            <span class="productos-card-name">{{ p.name }}</span>

            <div class="productos-card-details">
                <span class="productos-card-category"
                    :style="{ background: getCategoryStyle(p.category).bg, color: getCategoryStyle(p.category).color }">
                    {{ p.category }}
                </span>
                <span class="productos-card-origin">
                    <span class="productos-card-flag">{{ p.flag }}</span>
                    {{ p.origin }}
                </span>
            </div>

            <div class="productos-card-stock">
                <span class="productos-card-stock-label">Stock Actual</span>
                <span :class="['productos-card-stock-value', { 'productos-card-stock-value--low': p.lowStock }]">
                    {{ p.stock }}
                    <svg v-if="p.lowStock" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </span>
            </div>
        </div>
        <div v-if="productos.length === 0" class="productos-empty">
            No se encontraron productos con los filtros aplicados.
        </div>
    </div>
</template>

<style scoped>
.productos-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.productos-card {
    background: var(--card-bg);
    border-radius: var(--card-radius);
    box-shadow: var(--card-shadow);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: box-shadow 0.15s ease;
}

.productos-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.productos-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 4px;
}

.productos-card-icon {
    width: 48px;
    height: 48px;
    background: var(--page-bg);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.productos-card-menu {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    transition: all 0.12s ease;
}

.productos-card-menu:hover {
    background: var(--page-bg);
    color: var(--text-primary);
}

.productos-card-sku {
    font-size: 12px;
    font-weight: 600;
    color: var(--accent-blue);
}

.productos-card-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
}

.productos-card-details {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 4px;
}

.productos-card-category {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.productos-card-origin {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--text-secondary);
}

.productos-card-flag {
    font-size: 14px;
}

.productos-card-stock {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 8px;
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
}

.productos-card-stock-label {
    font-size: 12px;
    color: var(--text-muted);
}

.productos-card-stock-value {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}

.productos-card-stock-value--low {
    color: var(--accent-red);
}

.productos-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
    font-size: 14px;
}

@media (max-width: 1200px) {
    .productos-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .productos-grid {
        grid-template-columns: 1fr;
    }
}
</style>
