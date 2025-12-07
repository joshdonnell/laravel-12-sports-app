<script setup lang="ts">
interface Props {
  value?: string
}

const props = defineProps<Props>()

const model = defineModel<string | null>({ required: false })

onMounted(() => {
  if (props.value) {
    model.value = props.value
  }
})
</script>

<template>
  <textarea
    v-model="model"
    class="form-input form-textarea"
  />
</template>
