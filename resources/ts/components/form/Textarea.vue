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

const model = defineModel<string | null>({ required: true })

onMounted(() => {
  if (props.value) {
    model.value = props.value
  }
})
</script>

<template>
  <textarea
    :id="id"
    v-model="model"
    :name="name"
    class="form-input form-textarea"
    :placeholder="placeholder"
  />
</template>
