import axios from 'axios'

const LARAVEL = import.meta.env.VITE_LARAVEL_API

export async function login(email, password) {
  return await axios.post(LARAVEL + '/login', { email, password })
}

export async function me() {
  const token = localStorage.getItem('jwt_token')
  return await axios.get(LARAVEL + '/me', {
    headers: { Authorization: `Bearer ${token}` },
  })
}
