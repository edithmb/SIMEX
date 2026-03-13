<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  visible: { type: Boolean, required: true },
  maestroLabel: { type: String, required: true },
  columns: { type: Array, required: true },
  row: { type: Object, default: null },
  relatedData: { type: Object, default: () => ({}) },
})
const emit = defineEmits(['close', 'save'])

const form = ref({})

watch(() => props.visible, (val) => {
  if (!val) return
  const initial = {}
  props.columns.forEach(col => {
    initial[col.key] = props.row ? props.row[col.key] : ''
  })
  form.value = initial
})

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
                :value="opt[col.displayField || 'name']"
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
</style>
