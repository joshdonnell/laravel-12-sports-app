<script setup lang="ts">
import { nextTick } from 'vue'

const PIN_LENGTH = 6

type PinCode = Record<number, string>

const model = defineModel<string>({ required: true })

const code = reactive<PinCode>(Object.fromEntries(Array.from({ length: PIN_LENGTH }, (_, i) => [i + 1, ''])))

const inputRefs = ref<Record<number, HTMLInputElement>>({})

const focusInput = (index: number) => {
  const input = inputRefs.value[index]
  if (input && typeof input.focus === 'function') {
    input.focus()
    nextTick(() => {
      if (typeof input.setSelectionRange === 'function') {
        input.setSelectionRange(1, 1)
      }
    })
  }
}

const updateModel = () => {
  const values = Object.values(code).filter((v) => v !== '')
  model.value = values.join('')
}

const handleInput = (index: number, event: Event) => {
  const inputEvent = event as InputEvent
  const target = event.target as HTMLInputElement

  if ((inputEvent.data?.length ?? 0) > 1) {
    handleMultipleCharacter(target.value, index)
    return
  }

  const value = inputEvent.data ?? ''
  if (!/^\d*$/.test(value)) {
    target.value = ''
    code[index] = ''
    return
  }

  target.value = value
  code[index] = value

  if (value && index < PIN_LENGTH) {
    focusInput(index + 1)
  }

  updateModel()
}

const handleKeydown = (index: number, event: KeyboardEvent) => {
  if (event.key === 'Backspace') {
    event.preventDefault()

    if (code[index]) {
      code[index] = ''
    } else if (index > 1) {
      focusInput(index - 1)
      code[index - 1] = ''
    }

    updateModel()
    return
  }

  if (event.key === 'Delete') {
    event.preventDefault()
    code[index] = ''
    updateModel()
    return
  }

  if (event.key === 'ArrowLeft' && index > 1) {
    event.preventDefault()
    focusInput(index - 1)
  }

  if (event.key === 'ArrowRight' && index < PIN_LENGTH) {
    event.preventDefault()
    focusInput(index + 1)
  }

  if (event.key === 'Home') {
    event.preventDefault()
    focusInput(1)
  }

  if (event.key === 'End') {
    event.preventDefault()
    focusInput(PIN_LENGTH)
  }
}

const handleFocus = (event: FocusEvent) => {
  const target = event.target as HTMLInputElement
  nextTick(() => {
    target.setSelectionRange(1, 1)
  })

  if (!target.value) {
    target.placeholder = ''
  }
}

const handleBlur = (event: FocusEvent) => {
  const target = event.target as HTMLInputElement
  nextTick(() => {
    if (!target.value) {
      target.placeholder = '○'
    }
  })
}

const handlePaste = (event: ClipboardEvent) => {
  event.preventDefault()
  const pastedData = event.clipboardData?.getData('text') || ''

  if (!pastedData) return

  const target = event.target as HTMLInputElement
  const currentIndex = Number(Object.keys(code).find((key) => inputRefs.value[Number(key)] === target)) || 1

  handleMultipleCharacter(pastedData, currentIndex)
}

const handleMultipleCharacter = (values: string, startIndex: number) => {
  const digits = values.replace(/\D/g, '')

  if (!digits) return

  const initialIndex = digits.length >= PIN_LENGTH ? 1 : startIndex
  const lastIndex = Math.min(initialIndex + digits.length, PIN_LENGTH + 1)

  for (let i = initialIndex; i < lastIndex; i++) {
    const digit = digits[i - initialIndex]
    if (digit) {
      code[i] = digit
    }
  }

  const focusIndex = Math.min(lastIndex, PIN_LENGTH)
  focusInput(focusIndex)

  updateModel()
}

const setInputRef = (el: any, index: number) => {
  if (!el) return

  let inputElement: HTMLInputElement | null = null

  if (el.$el) {
    inputElement = el.$el.querySelector('input') || (el.$el.tagName === 'INPUT' ? el.$el : null)
  } else if (el.tagName === 'INPUT') {
    inputElement = el
  } else if (el.querySelector) {
    inputElement = el.querySelector('input')
  }

  if (inputElement) {
    inputRefs.value[index] = inputElement
  }
}
</script>

<template>
  <div class="form-pin flex justify-between overflow-hidden rounded-[6px] border border-grey-300">
    <div
      v-for="i in PIN_LENGTH"
      :key="i"
      class="flex flex-1 justify-center"
      :class="{
        'border-r border-grey-300': i !== PIN_LENGTH,
      }"
    >
      <FormInput
        :ref="(el: any) => setInputRef(el, i)"
        v-model="code[i]"
        type="text"
        placeholder="○"
        autocapitalize="off"
        autocomplete="one-time-code"
        :aria-label="`PIN digit ${i} of ${PIN_LENGTH}`"
        pattern="[0-9]*"
        inputmode="numeric"
        maxlength="1"
        :autofocus="i === 1"
        class="p-x-5 w-[41px] rounded-none border-0 border-l text-center first:border-l-0"
        @input="handleInput(i, $event)"
        @keydown="handleKeydown(i, $event)"
        @focus="handleFocus"
        @blur="handleBlur"
        @paste="handlePaste"
      />
    </div>
  </div>
</template>
