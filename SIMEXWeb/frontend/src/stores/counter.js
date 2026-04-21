import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

/**
 * Store de ejemplo generado por el scaffolding de Pinia.
 *
 * Mantiene un contador entero y un `doubleCount` computado. Se conserva
 * como referencia del patrón "setup store" de Pinia.
 *
 * @returns Store expuesto: `{ count, doubleCount, increment }`.
 */
export const useCounterStore = defineStore('counter', () => {
  const count = ref(0)
  const doubleCount = computed(() => count.value * 2)

  /** Incrementa el contador en 1. */
  function increment() {
    count.value++
  }

  return { count, doubleCount, increment }
})
