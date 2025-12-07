<script setup lang="ts">
interface Props {
  value?: string | number | null
}

defineProps<Props>()
</script>

<template>
  <div class="column w-3/12">
    <template v-if="value">
      <p class="heading-lg line-clamp-1 text-black">{{ value }}</p>
    </template>

    <slot />
  </div>
</template>
