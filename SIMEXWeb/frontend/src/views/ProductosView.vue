<script setup>
import { ref, computed } from 'vue'
import ProductosStats from '@/components/productos/ProductosStats.vue'
import ProductosFilters from '@/components/productos/ProductosFilters.vue'
import ProductosGrid from '@/components/productos/ProductosGrid.vue'
import NuevoProductoModal from '@/components/productos/NuevoProductoModal.vue'

const productos = [
    { sku: 'PRD-EL-001', name: 'Placa Base Industrial X99', category: 'Electrónica', origin: 'China', flag: '🇨🇳', stock: 450, lowStock: false },
    { sku: 'PRD-TX-045', name: 'Bobina Algodón Orgánico 500m', category: 'Textil', origin: 'India', flag: '🇮🇳', stock: 85, lowStock: true },
    { sku: 'PRD-MQ-112', name: 'Motor Eléctrico Trifásico 5HP', category: 'Maquinaria', origin: 'Alemania', flag: '🇩🇪', stock: 24, lowStock: false },
    { sku: 'PRD-AL-089', name: 'Aceite de Oliva Virgen Extra 5L', category: 'Alimentación', origin: 'España', flag: '🇪🇸', stock: 1200, lowStock: false },
    { sku: 'PRD-CH-033', name: 'Reactivo Químico R-404A', category: 'Química', origin: 'USA', flag: '🇺🇸', stock: 15, lowStock: true },
    { sku: 'PRD-EL-002', name: 'Microcontrolador ARM Cortex-M4', category: 'Electrónica', origin: 'Taiwán', flag: '🇹🇼', stock: 5000, lowStock: false },
    { sku: 'PRD-TX-046', name: 'Tela Poliéster Impermeable 50m', category: 'Textil', origin: 'Vietnam', flag: '🇻🇳', stock: 40, lowStock: true },
    { sku: 'PRD-MQ-113', name: 'Bomba Hidráulica Alta Presión', category: 'Maquinaria', origin: 'Italia', flag: '🇮🇹', stock: 8, lowStock: false },
]

const searchQuery = ref('')
const activeCategory = ref('Todas')
const activeOrigin = ref('Cualquier Origen')

const filteredProductos = computed(() => {
    let result = productos
    if (activeCategory.value !== 'Todas') {
        result = result.filter((p) => p.category === activeCategory.value)
    }
    if (activeOrigin.value !== 'Cualquier Origen') {
        result = result.filter((p) => p.origin === activeOrigin.value)
    }
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(
            (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q),
        )
    }
    return result
})

const showModal = ref(false)

function handleSubmit(data) {
    console.log('Nuevo producto:', data)
    showModal.value = false
}
</script>

<template>
    <div class="productos">
        <div class="productos-header">
            <h2 class="productos-header-title">Productos Importados</h2>
            <button class="productos-header-btn" @click="showModal = true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Añadir Producto
            </button>
        </div>
        <ProductosStats />
        <ProductosFilters :search-query="searchQuery" :active-category="activeCategory" :active-origin="activeOrigin"
            @update:search-query="searchQuery = $event" @update:active-category="activeCategory = $event"
            @update:active-origin="activeOrigin = $event" />
        <ProductosGrid :productos="filteredProductos" />

        <NuevoProductoModal :visible="showModal" @close="showModal = false" @submit="handleSubmit" />
    </div>
</template>

<style scoped>
.productos {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.productos-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.productos-header-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
}

.productos-header-btn {
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

.productos-header-btn:hover {
    background: #0d2440;
}
</style>
