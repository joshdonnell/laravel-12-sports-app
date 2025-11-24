import vue from '@vitejs/plugin-vue'
import path from 'path'
import VueDevTools from 'vite-plugin-vue-devtools'
import { defineConfig } from 'vitest/config'

export default defineConfig({
  plugins: [vue(), VueDevTools()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/ts')
    }
  },
  test: {
    clearMocks: true,
    globals: true,
    environment: 'happy-dom'
  }
})
