<script setup lang="ts">
interface Props {
  title?: string
  titleTag?: string
  description?: string
}

withDefaults(defineProps<Props>(), {
  title: '',
  titleTag: 'h2',
  description: '',
})
</script>

<template>
  <div class="shared-card rounded-[20px] bg-white p-20 lg:p-40 xl:px-60">
    <component
      :is="titleTag"
      v-if="title"
      class="heading-xl mb-25 font-semibold text-black"
    >
      {{ title }}
    </component>

    <p
      v-if="description"
      class="copy-md mb-20 text-black"
    >
      {{ description }}
    </p>

    <slot />
  </div>
</template>
