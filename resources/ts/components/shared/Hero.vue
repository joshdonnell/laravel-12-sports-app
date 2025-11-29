<script setup lang="ts">
interface Props {
  title: string
  description: string
}

defineProps<Props>()
</script>

<template>
  <section class="shared-hero border-b border-grey-200 pb-20">
    <h1 class="heading-xl mb-[2px] font-semibold text-black">{{ title }}</h1>
    <p class="copy text-grey-300">{{ description }}</p>
  </section>
</template>
