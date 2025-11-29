<script setup lang="ts">
import type { SharedData } from '@/types'

const page = usePage<SharedData>()
const user = page.props.auth.user
</script>

<template>
  <span class="shared-avatar flex h-30 w-30 items-center justify-center rounded-[5px] bg-white capitalize">{{ user.name.charAt(0) }}</span>
</template>
