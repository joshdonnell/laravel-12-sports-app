<script setup lang="ts" generic="T">
import deleteIcon from '@/../svg/delete.svg'
import type { Pagination, SharedData } from '@/types'

type Props<T> = {
  data: Pagination<T>
  columns: {
    label: string
    field: keyof T
    permission?: string
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
  const deleteModalId = ref('')
  const isDeleting = ref(false)
  const error = ref('')

  const handleShowDeleteModal = (id: string) => {
    deleteModalId.value = id
    showDeleteModal.value = true
  }
  const handleDelete = () => {
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

  return {
    showDeleteModal,
    handleShowDeleteModal,
    handleDelete,
    handleCloseDeleteModal,
    isDeleting,
    error,
  }
}

const { showDeleteModal, handleShowDeleteModal, handleDelete, handleCloseDeleteModal, isDeleting, error } = useDelete()
</script>

<template>
  <section class="shared-resourceTable mt-20 md:mt-30 xl:mt-40">
    <template v-if="data.data && data.data.length">
      <SharedTable>
        <div class="row relative mb-10 flex px-15">
          <template
            v-for="column in columns"
            :key="column.field"
          >
            <SharedTableColumn v-if="!column.permission || auth.can[column.permission]">
              <SharedTableKey>{{ column.label }}</SharedTableKey>
            </SharedTableColumn>
          </template>
        </div>

        <div class="flex flex-col gap-y-5">
          <SharedTableRow
            v-for="(item, key) in data.data"
            :key="key"
          >
            <template
              v-for="column in columns"
              :key="column.field"
            >
              <SharedTableColumn
                v-if="!column.permission || auth.can[column.permission]"
                :value="item[column.field] as string"
              />
            </template>

            <SharedTableColumn
              v-if="editPermission && editEndpoint && editField && auth.can[editPermission]"
              class="ml-auto flex items-center justify-end gap-x-20"
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
                  class="default-transition w-15 text-red-error hover:opacity-80"
                />
              </button>
            </SharedTableColumn>
          </SharedTableRow>
        </div>
      </SharedTable>

      <SharedPagination :pagination="data" />
    </template>

    <template v-else>
      <SharedNoResults>{{ noResults }}</SharedNoResults>
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
          @click="handleDelete()"
        >
          {{ isDeleting ? 'Deleting...' : 'Delete' }}
        </button>
      </div>
    </SharedModal>
  </template>
</template>
