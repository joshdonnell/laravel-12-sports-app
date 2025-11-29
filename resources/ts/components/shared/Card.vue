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
  <div class="shared-card mx-auto max-w-[500px] rounded-[20px] bg-white p-20 lg:p-40 xl:px-60">
    <component
      :is="titleTag"
      v-if="title"
      class="heading-xl font-semibold text-black"
      :class="{
        'mb-15': description,
        'mb-25': !description,
      }"
    >
      {{ title }}
    </component>

    <p
      v-if="description"
      class="copy-sm mb-25 text-grey-300"
    >
      {{ description }}
    </p>

    <slot />
  </div>
</template>
