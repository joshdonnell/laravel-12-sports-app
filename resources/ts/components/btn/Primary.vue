<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Props {
  tag?: string | typeof Link
}

withDefaults(defineProps<Props>(), {
  tag: Link,
})
</script>

<template>
  <component
    :is="tag"
    class="btn-primary"
  >
    <slot />
  </component>
</template>
