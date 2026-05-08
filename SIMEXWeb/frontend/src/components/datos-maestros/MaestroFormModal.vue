<script setup>
/**
 * @component MaestroFormModal
 * @description Modal genérico de alta/edición de un maestro. Construye
 * el formulario dinámicamente a partir de las `columns` pasadas por el
 * padre y precarga los valores iniciales desde `row` (si existe).
 *
 * @prop {boolean} visible
 * @prop {string}  maestroLabel  Etiqueta humana del maestro activo.
 * @prop {object[]} columns       Columnas con `{ key, label, type?, relatedKey?, displayField? }`.
 * @prop {object|null} [row=null]  Fila en edición (null = alta).
 * @prop {object} [relatedData={}] Catálogos para columnas `select`.
 *
 * @emits close
 * @emits save Con payload plano `{ [col.key]: value, … }`.
 */
import { ref, watch } from 'vue'

const props = defineProps({
  visible: { type: Boolean, required: true },
  maestroLabel: { type: String, required: true },
  columns: { type: Array, required: true },
  row: { type: Object, default: null },
  relatedData: { type: Object, default: () => ({}) },
  error: { type: String, default: '' },
})
const emit = defineEmits(['close', 'save'])

const form = ref({})

/**
 * Deriva la clave de relación a partir del nombre de columna
 * (`country_id` → `country`). Devuelve `null` si la columna no tiene clave.
 *
 * @param {{key:string}} col
 * @returns {string|null}
 */
function getRelationKey(col) {
  if (!col?.key) return null
  if (col.key.endsWith('_id')) return col.key.slice(0, -3)
  return col.key
}

/**
 * Calcula el valor inicial de un campo al abrir el modal en modo
 * edición. Prioriza el valor directo (`row[col.key]`); si el campo es
 * un select y sólo tenemos el objeto de relación eager-loaded, usa su
 * `id`. Devuelve `''` para filas nuevas o valores ausentes.
 *
 * @param {object} col
 * @returns {any}
 */
function getInitialValue(col) {
  if (!props.row) return ''

  const directValue = props.row[col.key]
  if (directValue != null) return directValue

  if (col.type !== 'select') return ''

  const relationKey = getRelationKey(col)
  const relationObj = relationKey ? props.row[relationKey] : null
  return relationObj?.id ?? ''
}

// Cada vez que el modal se abre recalculamos el form para reflejar el
// `row` actual (que puede venir distinto entre aperturas consecutivas)
watch(() => props.visible, (val) => {
  if (!val) return
  const initial = {}
  props.columns.forEach(col => {
    initial[col.key] = getInitialValue(col)
  })
  form.value = initial
})

/** Emite `save` con una copia plana del formulario. */
function handleSave() {
  emit('save', { ...form.value })
}
</script>

<template>
  <Teleport to="body">
    <div v-if="visible" class="modal-backdrop" @click.self="$emit('close')">
      <div class="modal">
        <div class="modal-header">
          <h3 class="modal-title">{{ row ? 'Editar' : 'Añadir' }} {{ maestroLabel }}</h3>
          <button class="modal-close" @click="$emit('close')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="error" class="error-message">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ error }}
          </div>
          <div v-for="col in columns" :key="col.key" class="field">
            <label class="field-label">{{ col.label }}</label>
            <select
              v-if="col.type === 'select'"
              v-model="form[col.key]"
              class="field-input"
            >
              <option value="">— Seleccionar —</option>
              <option
                v-for="opt in (relatedData[col.relatedKey] || [])"
                :key="opt.id"
                :value="opt.id"
              >
                {{ opt[col.displayField || 'name'] }}
              </option>
            </select>
            <input
              v-else
              v-model="form[col.key]"
              type="text"
              class="field-input"
              :placeholder="col.label"
            />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" @click="$emit('close')">Cancelar</button>
          <button class="btn-save" @click="handleSave">Guardar</button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: var(--card-bg);
  border-radius: 12px;
  width: 440px;
  max-width: 95vw;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px 0;
}

.modal-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-primary);
}

.modal-close {
  color: var(--text-muted);
  cursor: pointer;
  padding: 4px;
  background: none;
  border: none;
  display: flex;
  align-items: center;
}

.modal-close:hover {
  color: var(--text-primary);
}

.modal-body {
  padding: 20px 24px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.field-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-secondary);
}

.field-input {
  height: 38px;
  padding: 0 12px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  font-size: 13px;
  font-family: var(--font-family);
  color: var(--text-primary);
  background: var(--page-bg);
  outline: none;
}

.field-input:focus {
  border-color: #1a6fb5;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 0 24px 20px;
}

.btn-cancel {
  padding: 8px 18px;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  background: var(--card-bg);
  font-size: 13px;
  font-weight: 500;
  color: var(--text-secondary);
  cursor: pointer;
  font-family: var(--font-family);
}

.btn-save {
  padding: 8px 20px;
  border-radius: 8px;
  background: #1a6fb5;
  color: #fff;
  border: none;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  font-family: var(--font-family);
  transition: background 0.15s;
}

.btn-save:hover {
  background: #1558a0;
}

.error-message {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px;
  background: #fee2e2;
  border: 1px solid #fecaca;
  border-radius: 8px;
  font-size: 13px;
  color: #991b1b;
  margin-bottom: 8px;
}

.error-message svg {
  flex-shrink: 0;
  margin-top: 2px;
}
</style>
