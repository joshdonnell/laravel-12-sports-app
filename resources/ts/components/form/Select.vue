<script setup lang="ts">
interface Props {
  options: Array<App.Data.Shared.SelectData>
  value?: string | null
  placeholder?: string | undefined
}

const props = withDefaults(defineProps<Props>(), {
  value: undefined,
  placeholder: undefined,
})

const model = defineModel<string | null>({ required: false })

onMounted(() => {
  if (props.value) {
    model.value = props.value
  }
})
</script>

<template>
  <select
    v-model="model"
    class="form-select"
    :class="{ 'text-grey-300': !model }"
  >
    <option
      v-if="placeholder"
      value=""
    >
      {{ placeholder }}
    </option>

    <option
      v-for="{ value: optionValue, label } in options"
      :key="optionValue"
      :value="optionValue"
    >
      {{ label }}
    </option>
  </select>
</template>
