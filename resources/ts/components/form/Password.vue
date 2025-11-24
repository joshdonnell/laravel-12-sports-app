<script setup lang="ts">
interface Props {
  id: string
  name: string
  placeholder?: string
}

withDefaults(defineProps<Props>(), {
  placeholder: '',
})

const model = defineModel<string | null>({ required: true })
</script>

<template>
  <input
    :id="id"
    v-model="model"
    :name="name"
    type="password"
    class="form-input form-password"
    :placeholder="placeholder"
  />
</template>
