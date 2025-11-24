import { wayfinder } from '@laravel/vite-plugin-wayfinder'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import path from 'path'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'
import { watch } from 'vite-plugin-watch'

const inertiaComponents = ['Head', 'Link', 'Form']

export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/ts'),
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/ts/app.ts'],
      ssr: 'resources/ts/ssr.ts',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    wayfinder({
      formVariants: true,
      path: 'resources/ts',
    }),
    AutoImport({
      imports: ['vue', { '@inertiajs/vue3': ['useForm', 'usePage', 'useRemember', 'usePrefetch', 'router'] }],
      dirs: [path.resolve(__dirname, 'resources/ts/composables'), path.resolve(__dirname, 'resources/ts/stores')],
    }),
    Components({
      dts: true,
      dirs: [path.resolve(__dirname, 'resources/ts/components')],
      directoryAsNamespace: true,
      types: [
        {
          from: '@inertiajs/vue3',
          names: inertiaComponents,
        },
      ],
      resolvers: [
        (name: string) => {
          if (inertiaComponents.includes(name)) {
            return {
              name,
              from: '@inertiajs/vue3',
            }
          }
          return undefined
        },
      ],
    }),
    watch({
      pattern: 'app/{Data,Enums}/**/*.php',
      command: 'composer run transform-types',
    }),
  ],
})
