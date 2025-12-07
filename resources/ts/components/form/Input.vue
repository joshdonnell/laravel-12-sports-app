<script setup lang="ts">
interface Props {
  value?: string | number
  icon?: string
}

const props = withDefaults(defineProps<Props>(), {
  value: undefined,
  icon: '',
})

const model = defineModel<string | number | null>({ required: false })

defineOptions({
  inheritAttrs: false,
})

onMounted(() => {
  if (props.value) {
    model.value = props.value
  }
})
</script>

<template>
  <div class="form-text relative">
    <input
      v-model="model"
      v-bind="$attrs"
      class="form-input form-text"
      :class="{ 'pr-40': props.icon }"
    />

    <template v-if="icon">
      <span class="absolute right-[7px] top-1/2 flex h-30 w-30 -translate-y-1/2 items-center justify-center rounded-[5px] bg-white-100">
        <InlineSvg
          :src="icon"
          class="w-[14px]"
        />
      </span>
    </template>
  </div>
</template>
