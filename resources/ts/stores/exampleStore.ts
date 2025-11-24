import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useExampleStore = defineStore('example', () => {
  const exampleCount = ref(0)

  function doubleExampleCount() {
    exampleCount.value *= 2
  }

  return { exampleCount, doubleExampleCount }
})
