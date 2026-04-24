<script setup>
/**
 * @component ChatWidget
 * @description Botón flotante abajo-derecha que abre un panel de chat
 * conectado al workflow de n8n (text-to-SQL sobre comercio_mundial).
 * El historial se guarda en sessionStorage y se pierde al cerrar pestaña.
 */
import { ref, nextTick, onMounted, watch } from 'vue'
import { sendChatMessage } from '@/services/chatService'

const STORAGE_KEY = 'simex-chat-history'
const SESSION_KEY = 'simex-chat-session'

const open = ref(false)
const input = ref('')
const loading = ref(false)
const messages = ref([])
const sessionId = ref('')
const listRef = ref(null)

onMounted(() => {
  const saved = sessionStorage.getItem(STORAGE_KEY)
  if (saved) {
    try { messages.value = JSON.parse(saved) } catch { messages.value = [] }
  }
  let sid = sessionStorage.getItem(SESSION_KEY)
  if (!sid) {
    sid = (crypto.randomUUID && crypto.randomUUID()) || String(Date.now())
    sessionStorage.setItem(SESSION_KEY, sid)
  }
  sessionId.value = sid
})

watch(messages, (val) => {
  sessionStorage.setItem(STORAGE_KEY, JSON.stringify(val))
  nextTick(scrollToEnd)
}, { deep: true })

function scrollToEnd() {
  if (listRef.value) listRef.value.scrollTop = listRef.value.scrollHeight
}

function toggle() {
  open.value = !open.value
  if (open.value) nextTick(scrollToEnd)
}

async function send() {
  const text = input.value.trim()
  if (!text || loading.value) return
  messages.value.push({ role: 'user', text })
  input.value = ''
  loading.value = true
  try {
    const respuesta = await sendChatMessage(text, sessionId.value)
    messages.value.push({ role: 'bot', text: respuesta })
  } catch (err) {
    messages.value.push({ role: 'bot', text: `Error: ${err.message}` })
  } finally {
    loading.value = false
  }
}

function clearHistory() {
  messages.value = []
}
</script>

<template>
  <div class="chat-widget">
    <button
      v-if="!open"
      class="chat-fab"
      aria-label="Abrir chat"
      @click="toggle"
    >
      💬
    </button>

    <div v-if="open" class="chat-panel" role="dialog" aria-label="Chat SIMEX">
      <header class="chat-header">
        <span class="chat-title">Asistente SIMEX</span>
        <div class="chat-header-actions">
          <button class="chat-icon-btn" title="Limpiar" @click="clearHistory">🗑</button>
          <button class="chat-icon-btn" title="Cerrar" @click="toggle">✕</button>
        </div>
      </header>

      <div ref="listRef" class="chat-messages">
        <p v-if="messages.length === 0" class="chat-empty">
          Pregúntame sobre comercio mundial. Ej: <em>“top 3 países exportadores en 2016”</em>.
        </p>
        <div
          v-for="(m, i) in messages"
          :key="i"
          :class="['chat-msg', m.role === 'user' ? 'chat-msg-user' : 'chat-msg-bot']"
        >
          {{ m.text }}
        </div>
        <div v-if="loading" class="chat-msg chat-msg-bot chat-loading">
          Pensando…
        </div>
      </div>

      <form class="chat-input" @submit.prevent="send">
        <input
          v-model="input"
          type="text"
          placeholder="Escribe tu pregunta…"
          :disabled="loading"
        />
        <button type="submit" :disabled="loading || !input.trim()">Enviar</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.chat-widget {
  position: fixed;
  right: 24px;
  bottom: 24px;
  z-index: 9999;
}

.chat-fab {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: var(--accent-blue, #1a6fb5);
  color: #fff;
  font-size: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
  transition: transform 0.15s ease;
}
.chat-fab:hover {
  transform: scale(1.05);
}

.chat-panel {
  width: 360px;
  height: 520px;
  max-height: calc(100vh - 48px);
  background: var(--card-bg, #fff);
  border-radius: var(--card-radius, 12px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border: 1px solid var(--border-color, #e5e7eb);
}

.chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: var(--accent-blue, #1a6fb5);
  color: #fff;
}
.chat-title {
  font-weight: 600;
}
.chat-header-actions {
  display: flex;
  gap: 4px;
}
.chat-icon-btn {
  color: #fff;
  font-size: 14px;
  padding: 4px 8px;
  border-radius: 4px;
}
.chat-icon-btn:hover {
  background: rgba(255, 255, 255, 0.15);
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 12px;
  background: var(--page-bg, #f0f2f5);
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.chat-empty {
  color: var(--text-muted, #9ca3af);
  font-size: 13px;
  text-align: center;
  margin-top: 16px;
}

.chat-msg {
  max-width: 85%;
  padding: 8px 12px;
  border-radius: 10px;
  font-size: 14px;
  line-height: 1.4;
  word-wrap: break-word;
  white-space: pre-wrap;
}
.chat-msg-user {
  align-self: flex-end;
  background: var(--accent-blue, #1a6fb5);
  color: #fff;
  border-bottom-right-radius: 2px;
}
.chat-msg-bot {
  align-self: flex-start;
  background: var(--card-bg, #fff);
  color: var(--text-primary, #1a2332);
  border: 1px solid var(--border-color, #e5e7eb);
  border-bottom-left-radius: 2px;
}
.chat-loading {
  font-style: italic;
  color: var(--text-secondary, #6b7280);
}

.chat-input {
  display: flex;
  gap: 8px;
  padding: 10px;
  border-top: 1px solid var(--border-color, #e5e7eb);
  background: var(--card-bg, #fff);
}
.chat-input input {
  flex: 1;
  padding: 8px 10px;
  border: 1px solid var(--border-color, #e5e7eb);
  border-radius: 6px;
  font-size: 14px;
}
.chat-input input:focus {
  border-color: var(--accent-blue, #1a6fb5);
}
.chat-input button {
  padding: 8px 14px;
  background: var(--accent-blue, #1a6fb5);
  color: #fff;
  border-radius: 6px;
  font-weight: 600;
  font-size: 13px;
}
.chat-input button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
