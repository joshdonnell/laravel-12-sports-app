<script setup lang="ts">
interface Props {
  id: string
  name: string
  placeholder?: string
  value?: string
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: '',
  value: undefined,
})

const model = defineModel<string | null>({ required: false })

onMounted(() => {
  if (props.value) {
    model.value = props.value
  }
})
</script>

<template>
  <input
    :id="id"
    v-model="model"
    :name="name"
    type="text"
    class="form-input form-text"
    :placeholder="placeholder"
  />
</template>
