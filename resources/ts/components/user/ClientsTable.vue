<script setup lang="ts">
import { attachClient, detachClient } from '@/routes/users'
import { Pagination, SharedData } from '@/types'
import deleteIcon from '../../../svg/delete.svg'

interface Props {
  userId: string
  clients: Pagination<App.Data.Client.ClientData>
  availableClients?: App.Data.Shared.SelectData[] | null
}

const props = defineProps<Props>()

const page = usePage<SharedData>()
const auth = page.props.auth

const useDelete = () => {
  const showDeleteModal = ref(false)
  const deleteClientId = ref('')
  const isDeleting = ref(false)

  const handleShowDeleteModal = (clientId: string) => {
    deleteClientId.value = clientId
    showDeleteModal.value = true
  }
  const handleDelete = () => {
    if (!deleteClientId.value) return

    isDeleting.value = true

    router.delete(detachClient({ user: props.userId, client: deleteClientId.value }), {
      onSuccess: () => handleCloseDeleteModal(),
    })
  }

  const handleCloseDeleteModal = () => {
    deleteClientId.value = ''
    showDeleteModal.value = false
    isDeleting.value = false
  }

  return {
    showDeleteModal,
    handleDelete,
    handleShowDeleteModal,
    handleCloseDeleteModal,
    isDeleting,
  }
}
const { showDeleteModal, handleDelete, handleShowDeleteModal, handleCloseDeleteModal, isDeleting } = useDelete()

const useCreate = () => {
  const showCreateModal = ref(false)
  const createClientId = ref('')

  const handleShowCreateModal = () => {
    showCreateModal.value = true

    router.reload({
      only: ['availableClients'],
    })
  }
  const handleCloseCreateModal = () => {
    showCreateModal.value = false
    isCreating.value = false
    createClientId.value = ''
  }

  return {
    showCreateModal,
    createClientId,
    handleShowCreateModal,
    handleCloseCreateModal,
  }
}

const { showCreateModal, createClientId, handleShowCreateModal, handleCloseCreateModal } = useCreate()
</script>

<template>
  <section class="user-clientsTable mt-20 md:mt-30 xl:mt-40">
    <template v-if="clients.data && clients.data.length">
      <SharedTable>
        <div class="row relative mb-10 flex px-15">
          <SharedTableColumn>
            <SharedTableKey>Name</SharedTableKey>
          </SharedTableColumn>

          <SharedTableColumn v-if="auth.can['create-sport']">
            <SharedTableKey>Sport</SharedTableKey>
          </SharedTableColumn>
        </div>

        <div class="flex flex-col gap-y-5">
          <SharedTableRow
            v-for="(client, key) in clients.data"
            :key="key"
          >
            <SharedTableColumn :value="client.name" />
            <SharedTableColumn
              v-if="auth.can['create-sport']"
              :value="client.sport_name"
            />

            <SharedTableColumn class="ml-auto flex items-center justify-end gap-x-20">
              <button @click="handleShowDeleteModal(client.uuid)">
                <InlineSvg
                  :src="deleteIcon"
                  class="default-transition w-15 text-red-error hover:opacity-80"
                />
              </button>
            </SharedTableColumn>
          </SharedTableRow>
        </div>
      </SharedTable>

      <SharedPagination :pagination="clients" />
    </template>

    <template v-else>
      <SharedNoResults>No API Clients added yet — add your first client to continue</SharedNoResults>
    </template>

    <div class="mt-20">
      <BtnPrimary
        tag="button"
        @click="handleShowCreateModal"
      >
        Add API Client
      </BtnPrimary>
    </div>

    <template v-if="showDeleteModal">
      <SharedModal
        title="Are your sure you want to delete this API Client from the user?"
        @close="handleCloseDeleteModal()"
      >
        <div class="mt-40 flex flex-col items-center gap-y-20">
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

    <template v-if="showCreateModal">
      <SharedModal
        title="Add an API Client to the user"
        @close="handleCloseCreateModal()"
      >
        <Form
          v-slot="{ processing, errors }"
          v-bind="attachClient.form({ user: userId, client: createClientId })"
          :on-success="handleCloseCreateModal"
          class="mt-40 flex flex-col gap-y-20"
        >
          <FormGroup
            input-id="client"
            label="Select API Client"
            :error="errors.client"
          >
            <FormSearchableSelect
              id="client"
              v-model="createClientId"
              autofocus
              :options="availableClients || []"
              :disabled="!availableClients"
            />
          </FormGroup>

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing || !createClientId"
          >
            {{ isDeleting ? 'Adding...' : 'Add API Client' }}
          </BtnPrimary>
        </Form>
      </SharedModal>
    </template>
  </section>
</template>
