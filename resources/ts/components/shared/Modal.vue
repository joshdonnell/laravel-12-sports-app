<script setup lang="ts">
import closeIcon from '@/../svg/close.svg'
import { onClickOutside, useScrollLock } from '@vueuse/core'
import gsap from 'gsap'

interface Props {
  title?: string
}

defineProps<Props>()

const emits = defineEmits<{ (e: 'close'): void }>()
const modal = ref<HTMLElement>()
const modalContent = ref<HTMLElement>()
const scrollLocked = useScrollLock(window)

onClickOutside(modalContent, () => handleClose())

const handleClose = () => {
  gsap
    .to(modal.value!, {
      duration: 0.2,
      opacity: 0,
    })
    .then(() => emits('close'))
}

onMounted(() => {
  gsap.to(modal.value!, {
    duration: 0.2,
    opacity: 1,
  })
  scrollLocked.value = true
})

onUnmounted(() => {
  scrollLocked.value = false
})
</script>

<template>
  <teleport to="body">
    <div
      ref="modal"
      class="shared-modal fixed inset-0 z-[100] flex items-center justify-center bg-purple/80 px-20 py-20 opacity-0"
    >
      <div
        ref="modalContent"
        class="relative max-h-full w-full max-w-[600px] rounded-[20px] bg-white p-20 md:p-40 xl:p-60"
      >
        <button
          class="default-transition absolute right-15 top-15 flex h-30 w-30 cursor-pointer items-center justify-center rounded-[5px] bg-white-100 text-black hover:bg-blue-200 hover:text-white"
          @click="handleClose()"
        >
          <inlineSvg
            :src="closeIcon"
            class="w-15"
          />
        </button>
        <h2
          v-if="title"
          class="heading-xl mb-20 font-semibold text-black xl:mb-25"
        >
          {{ title }}
        </h2>

        <slot />
      </div>
    </div>
  </teleport>
</template>
