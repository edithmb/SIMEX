import { vi, beforeEach } from 'vitest'

vi.mock('axios')

vi.stubEnv('VITE_LARAVEL_API', 'http://localhost:8000/api')

beforeEach(() => {
  localStorage.clear()
  vi.clearAllMocks()
})
