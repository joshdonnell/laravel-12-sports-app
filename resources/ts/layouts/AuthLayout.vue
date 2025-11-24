<script setup lang="ts">
import AuthBg from '@/../images/auth-bg.jpg'
import logo from '@/../svg/logo.svg'
import { dashboard } from '@/routes'
import { SharedData } from '@/types'
import InlineSvg from 'vue-inline-svg'

interface Props {
  title?: string
  backgroundImage?: string
}

defineProps<Props>()
const page = usePage<SharedData>()
</script>

<template>
  <Head
    v-if="title"
    :title="title"
  />

  <section class="auth-layout relative h-dvh py-20">
    <img
      :src="AuthBg"
      class="pointer-events-none absolute inset-0 h-full overflow-hidden object-cover"
      :alt="`${page.props.name} Auth Background`"
    />

    <div class="container relative z-10 flex h-full flex-col justify-between gap-y-20">
      <div class="flex flex-1 items-start justify-center">
        <Link :href="dashboard.url()">
          <InlineSvg
            class="w-130 md:w-150"
            :src="logo"
          />
        </Link>
      </div>

      <slot />

      <span class="flex-1"></span>
    </div>
  </section>
</template>
