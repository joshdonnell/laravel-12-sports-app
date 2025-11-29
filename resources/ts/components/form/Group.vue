<script setup lang="ts">
import infoIcon from '@/../svg/info.svg'

interface Props {
  error?: string | string[] | undefined
  instructions?: string
  label?: string
  inputId?: string
}

withDefaults(defineProps<Props>(), {
  inputId: '',
  instructions: undefined,
  label: undefined,
  error: undefined,
})
</script>

<template>
  <div class="form-group flex flex-col gap-y-5">
    <FormLabel
      v-if="label"
      :input-id="inputId"
      :label="label"
    />

    <slot />

    <p
      v-if="instructions"
      class="form-group__instructions flex items-center gap-x-5"
    >
      <InlineSvg
        class="h-15 w-15 text-grey-300"
        :src="infoIcon"
      />

      <span>
        {{ instructions }}
      </span>
    </p>

    <template v-if="error">
      <template v-if="typeof error === 'object'">
        <p
          v-for="(err, index) in error"
          :key="index"
          class="form-group__error"
        >
          {{ err }}
        </p>
      </template>
      <p
        v-else
        class="form-group__error"
      >
        {{ error }}
      </p>
    </template>
  </div>
</template>
