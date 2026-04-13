import { setActivePinia, createPinia } from 'pinia'
import { useCounterStore } from '@/stores/counter'

describe('counter store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('starts at count 0 with doubleCount 0', () => {
    const store = useCounterStore()
    expect(store.count).toBe(0)
    expect(store.doubleCount).toBe(0)
  })

  it('increments count by 1', () => {
    const store = useCounterStore()
    store.increment()
    expect(store.count).toBe(1)
  })

  it('doubleCount reflects 2x count', () => {
    const store = useCounterStore()
    store.increment()
    store.increment()
    expect(store.doubleCount).toBe(4)
  })
})
