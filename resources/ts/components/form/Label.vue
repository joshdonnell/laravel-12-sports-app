<script setup lang="ts">
interface Props {
  inputId: string
  label: string
}

defineProps<Props>()
</script>

<template>
  <label
    :for="inputId"
    class="form-label"
  >
    {{ label }}
  </label>
</template>
