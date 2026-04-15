<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Spinner from '@/components/common/Spinner.vue'

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function handleLogin() {
  error.value = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/')
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-logo">
        <span class="login-brand">SIMEX</span>
      </div>

      <h1 class="login-title">Iniciar sesión</h1>
      <p class="login-subtitle">Accede a tu cuenta para continuar</p>

      <form class="login-form" @submit.prevent="handleLogin">
        <div class="field">
          <label class="field-label">Correo electrónico</label>
          <input
            v-model="email"
            type="email"
            class="field-input"
            placeholder="usuario@empresa.com"
            autocomplete="email"
            required
          />
        </div>

        <div class="field">
          <label class="field-label">Contraseña</label>
          <input
            v-model="password"
            type="password"
            class="field-input"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          />
        </div>

        <p v-if="error" class="login-error">{{ error }}</p>

        <button type="submit" class="login-btn" :disabled="loading">
          <Spinner v-if="loading" :size="16" />
          <span>{{ loading ? 'Entrando…' : 'Entrar' }}</span>
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--page-bg, #f5f7fa);
}

.login-card {
  background: var(--card-bg, #fff);
  border-radius: 16px;
  padding: 40px;
  width: 400px;
  max-width: 95vw;
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
}

.login-logo {
  text-align: center;
  margin-bottom: 28px;
}

.login-brand {
  font-size: 28px;
  font-weight: 800;
  color: #1a6fb5;
  letter-spacing: 2px;
}

.login-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-primary, #111);
  margin: 0 0 6px;
}

.login-subtitle {
  font-size: 13px;
  color: var(--text-secondary, #666);
  margin: 0 0 28px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.field-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-secondary, #666);
}

.field-input {
  height: 40px;
  padding: 0 12px;
  border: 1px solid var(--border-color, #dde1e7);
  border-radius: 8px;
  font-size: 14px;
  color: var(--text-primary, #111);
  background: var(--page-bg, #f5f7fa);
  outline: none;
  font-family: inherit;
}

.field-input:focus {
  border-color: #1a6fb5;
}

.login-error {
  font-size: 13px;
  color: #d9534f;
  margin: 0;
}

.login-btn {
  height: 42px;
  background: #1a6fb5;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  font-family: inherit;
  transition: background 0.15s;
  margin-top: 4px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.login-btn:hover:not(:disabled) {
  background: #1558a0;
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: default;
}
</style>
