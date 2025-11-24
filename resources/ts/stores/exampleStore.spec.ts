import { createPinia, setActivePinia } from 'pinia'
import { beforeEach, expect, it } from 'vitest'
import { useExampleStore } from './exampleStore'

beforeEach(() => {
  setActivePinia(createPinia())
})

it('should initialize with exampleCount of 0', () => {
  const store = useExampleStore()

  expect(store.exampleCount).toBe(0)
})

it('should double the exampleCount when dobuleExampleCount is called', () => {
  const store = useExampleStore()

  store.doubleExampleCount()

  expect(store.exampleCount).toBe(0)
})

it('should double the exampleCount correctly from non-zero value', () => {
  const store = useExampleStore()

  store.exampleCount = 5
  store.doubleExampleCount()

  expect(store.exampleCount).toBe(10)
})

it('should double the exampleCount multiple times correctly', () => {
  const store = useExampleStore()

  store.exampleCount = 2
  store.doubleExampleCount()

  expect(store.exampleCount).toBe(4)

  store.doubleExampleCount()
  expect(store.exampleCount).toBe(8)

  store.doubleExampleCount()
  expect(store.exampleCount).toBe(16)
})
