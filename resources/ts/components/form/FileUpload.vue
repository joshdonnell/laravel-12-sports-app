<script setup lang="ts">
import deleteIcon from '@/../svg/delete.svg'
import uploadIcon from '@/../svg/upload.svg'
import { bytesToMegaBytes } from '@/utils/fileUtils'

interface Props {
  existingUrl?: string | null
}

const props = defineProps<Props>()
const model = defineModel<File>()
const emits = defineEmits<{ (e: 'fileRemoved'): void }>()

defineOptions({
  inheritAttrs: false,
})

const useFileUpload = () => {
  const isDragging = ref(false)

  const getImagePreview = (file: File) => {
    return URL.createObjectURL(file)
  }

  const handleFileBeingAdded = (event: Event): void => {
    if (event.target instanceof HTMLInputElement && event.target.files) {
      model.value = event.target.files?.[0]
    }
  }

  const handleFileRemove = () => {
    model.value = undefined
    emits('fileRemoved')
  }

  const handleDragOver = (event: DragEvent) => {
    event.preventDefault()
    if (!model.value && !props.existingUrl) {
      isDragging.value = true
    }
  }

  const handleDragLeave = (event: DragEvent) => {
    event.preventDefault()
    isDragging.value = false
  }

  const handleDrop = (event: DragEvent) => {
    event.preventDefault()
    isDragging.value = false

    if (model.value || props.existingUrl) return

    const files = event.dataTransfer?.files

    if (files && files.length > 0) {
      const file = files[0]
      if (file.type.startsWith('image/')) {
        model.value = file
      }
    }
  }

  const fileUploadContent = computed(() => {
    if (model.value || props.existingUrl) {
      return {
        title: 'An existing file already exists or is selected',
        description: 'Remove this below to upload a new file.',
      }
    } else if (isDragging.value) {
      return {
        title: 'Drop your file here',
        description: 'Release to upload your image',
      }
    } else {
      return {
        title: 'Click here to upload your file or drag and drop',
        description: 'Supported Format: SVG, JPG, PNG (3mb max)',
      }
    }
  })

  return {
    isDragging,
    getImagePreview,
    handleFileBeingAdded,
    handleFileRemove,
    handleDragOver,
    handleDragLeave,
    handleDrop,
    fileUploadContent,
  }
}
const {
  isDragging,
  getImagePreview,
  handleFileBeingAdded,
  handleFileRemove,
  handleDragOver,
  handleDragLeave,
  handleDrop,
  fileUploadContent: content,
} = useFileUpload()
</script>

<template>
  <div class="form-fileUpload flex flex-col gap-y-15">
    <div
      class="flex flex-col items-center rounded-[10px] border border-grey-300 p-20 text-center transition-colors"
      :class="{ 'border-blue-300 bg-purple': isDragging }"
      @dragover="handleDragOver"
      @dragleave="handleDragLeave"
      @drop="handleDrop"
    >
      <span class="mb-10 flex h-30 w-30 items-center justify-center rounded-[5px] bg-white-100">
        <InlineSvg
          :src="uploadIcon"
          class="w-[14px]"
        />
      </span>

      <p class="copy-md mb-10 text-balance text-black">{{ content.title }}</p>
      <p class="copy-sm mb-25 font-medium text-grey-300">{{ content.description }}</p>

      <BtnPrimary
        tag="label"
        :for="$attrs['id']"
        :class="{ 'is-disabled': model || existingUrl }"
        @click="(model || existingUrl) && $event.preventDefault()"
      >
        Select File
      </BtnPrimary>

      <input
        type="file"
        v-bind="$attrs"
        class="hidden"
        accept="image/png, image/jpeg, image/svg+xml"
        @change="handleFileBeingAdded($event)"
      />
    </div>

    <div
      v-if="model || existingUrl"
      class="flex items-center justify-between overflow-hidden rounded-[10px] border border-grey-300 p-15"
    >
      <div class="flex items-center gap-x-15">
        <img
          :src="model ? getImagePreview(model) : existingUrl!"
          class="h-30 w-30 rounded-[5px] object-cover"
          alt=""
        />

        <div class="flex flex-col gap-y-5">
          <p class="copy-sm font-medium text-black">{{ model ? model.name : 'Existing File' }}</p>
          <p
            v-if="model && model.size"
            class="copy-sm font-medium text-grey-300"
          >
            {{ bytesToMegaBytes(model.size) }}mb
          </p>
        </div>
      </div>

      <button
        class="default-transition cursor-pointer text-red-error hover:opacity-80"
        @click.prevent="handleFileRemove()"
      >
        <InlineSvg
          :src="deleteIcon"
          class="w-10"
        />
      </button>
    </div>
  </div>
</template>
