/**
 * @file Wrapper fino sobre los endpoints `/login` y `/me` del backend
 * Laravel. La URL base se toma de `VITE_LARAVEL_API`.
 *
 * Nota: la lógica de sesión (persistencia del token, rol, etc.) vive en
 * `stores/auth.js`. Este módulo se mantiene como alternativa sin Pinia
 * (útil para componentes que prefieran invocar directamente el backend).
 */

import axios from 'axios'

const LARAVEL = import.meta.env.VITE_LARAVEL_API

/**
 * Llama al endpoint de login del backend.
 *
 * @param {string} email
 * @param {string} password
 * @returns {Promise<import('axios').AxiosResponse>} Respuesta con
 *   `{ token, token_type, expires_in, user }`.
 */
export async function login(email, password) {
  return await axios.post(LARAVEL + '/login', { email, password })
}

/**
 * Obtiene el usuario autenticado. Lee el JWT directamente de
 * `localStorage` para no depender de los interceptores globales.
 *
 * @returns {Promise<import('axios').AxiosResponse>}
 */
export async function me() {
  const token = localStorage.getItem('jwt_token')
  return await axios.get(LARAVEL + '/me', {
    headers: { Authorization: `Bearer ${token}` },
  })
}
