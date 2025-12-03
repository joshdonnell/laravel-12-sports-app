<script setup lang="ts" generic="T">
import deleteIcon from '@/../svg/delete.svg'
import type { Pagination, SharedData } from '@/types'

type Props<T> = {
  data: Pagination<T>
  columns: {
    label: string
    field: keyof T
  }[]
  createText?: string
  createPermission?: string
  createEndpoint?: string
  editPermission?: string
  editEndpoint?: (id: string) => string
  editField?: keyof T
  noResults: string
  deletePermission?: string
  deleteEndpoint?: (id: string) => string
  deleteField?: keyof T
  deleteModalTitle?: string
}

const props = defineProps<Props<T>>()

const page = usePage<SharedData>()
const auth = page.props.auth

const useDelete = () => {
  const showDeleteModal = ref(false)
  const deleteConfirmation = ref(false)
  const deleteModalId = ref('')
  const isDeleting = ref(false)
  const error = ref('')

  const handleShowDeleteModal = (id: string) => {
    deleteModalId.value = id
    showDeleteModal.value = true
  }
  const handleDelete = async () => {
    if (!deleteModalId.value || !props.deleteEndpoint) return

    isDeleting.value = true

    router.delete(props.deleteEndpoint(deleteModalId.value), {
      onSuccess: () => handleCloseDeleteModal(),
      onError: () => {
        isDeleting.value = false
        error.value = 'Something went wrong. Please try again.'
      },
    })
  }

  const handleCloseDeleteModal = () => {
    deleteModalId.value = ''
    showDeleteModal.value = false
    isDeleting.value = false
    error.value = ''
  }

  watch(deleteConfirmation, () => {
    handleDelete()
  })

  return {
    showDeleteModal,
    handleShowDeleteModal,
    deleteConfirmation,
    handleCloseDeleteModal,
    isDeleting,
    error,
  }
}

const { showDeleteModal, handleShowDeleteModal, deleteConfirmation, handleCloseDeleteModal, isDeleting, error } = useDelete()
</script>

<template>
  <section class="shared-resourceTable mt-20 md:mt-30 xl:mt-40">
    <template v-if="data.data && data.data.length">
      <div class="w-[calc(100%+20px)] overflow-auto pb-10 pr-20 lg:w-full lg:overflow-hidden lg:pb-0 lg:pr-0">
        <div class="relative w-full min-w-[600px] max-w-full">
          <div class="row relative mb-10 flex px-15">
            <div
              v-for="column in columns"
              :key="column.field"
              class="column w-3/12"
            >
              <p class="copy font-semibold text-black">{{ column.label }}</p>
            </div>
          </div>

          <div class="flex flex-col gap-y-5">
            <div
              v-for="(item, key) in data.data"
              :key="key"
              class="rounded-[10px] bg-white-100 px-15 py-[12px]"
            >
              <div class="row flex items-center">
                <div
                  v-for="column in columns"
                  :key="column.field"
                  class="column w-3/12"
                >
                  <p class="heading-lg line-clamp-1 text-black">{{ item[column.field] }}</p>
                </div>

                <div
                  v-if="editPermission && editEndpoint && editField && auth.can[editPermission]"
                  class="column ml-auto flex w-3/12 items-center justify-end gap-x-20"
                >
                  <Link
                    :href="editEndpoint(item[editField] as string)"
                    class="default-transition text-blue-200 underline hover:text-blue-300"
                  >
                    Edit
                  </Link>

                  <button
                    v-if="deletePermission && auth.can[deletePermission] && deleteField"
                    @click="handleShowDeleteModal(item[deleteField] as string)"
                  >
                    <InlineSvg
                      :src="deleteIcon"
                      class="w-15 text-red-error"
                    />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <SharedPagination :pagination="data" />
    </template>

    <template v-else>
      <p class="heading-lg font-semibold text-black">{{ noResults }}</p>
    </template>

    <div
      v-if="createPermission && auth.can[createPermission]"
      class="mt-40 flex justify-end"
    >
      <BtnPrimary :href="createEndpoint">{{ createText }}</BtnPrimary>
    </div>
  </section>

  <template v-if="deletePermission && auth.can[deletePermission] && showDeleteModal">
    <SharedModal
      :title="deleteModalTitle || ''"
      @close="handleCloseDeleteModal()"
    >
      <div class="mt-40 flex flex-col items-center gap-y-20">
        <p
          v-if="error"
          class="block text-[12px] font-medium leading-[16px] text-red-error"
        >
          {{ error }}
        </p>

        <BtnPrimary
          tag="button"
          @click="handleCloseDeleteModal()"
        >
          Cancel
        </BtnPrimary>

        <button
          class="copy default-transition cursor-pointer text-red-error underline hover:opacity-80 disabled:cursor-not-allowed disabled:opacity-50"
          :disabled="isDeleting"
          @click="deleteConfirmation = true"
        >
          {{ isDeleting ? 'Deleting...' : 'Delete' }}
        </button>
      </div>
    </SharedModal>
  </template>
</template>
