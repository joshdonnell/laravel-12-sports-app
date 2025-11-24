<script setup lang="ts">
interface Props {
  title?: string
}

defineProps<Props>()
</script>

<template>
  <Head
    v-if="title"
    :title="title"
  />
  <SiteHeader />
  <slot />
  <SiteFooter />
</template>
