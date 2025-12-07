<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, destroy, edit, index } from '@/routes/clients'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  clients: Pagination<App.Data.Client.ClientData>
  sports?: App.Data.Shared.SelectData[] | null
  search?: string
  sport?: string
}

const props = withDefaults(defineProps<Props>(), {
  search: '',
  sport: '',
  sports: null,
})

defineOptions({ layout: DashboardLayout })

const filters = reactive<{
  search: string
  sport: string
}>({
  search: props.search,
  sport: props.sport,
})

watchDebounced(
  filters,
  () => {
    router.visit(index(), {
      only: ['clients'],
      data: {
        search: filters.search,
        sport: filters.sport,
      },
      preserveState: true,
    })
  },
  {
    debounce: 300,
  },
)
</script>

<template>
  <Head title="API Clients" />

  <section class="client-index">
    <ClientFilters
      v-model="filters"
      :sports="sports"
    />

    <SharedHero
      title="API Clients"
      description="A list of API clients that can connect to your app. Use these to give secure access to third-party tools and integrations."
    />

    <SharedResourceTable
      :data="clients"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Sport',
          field: 'sport_name',
          permission: 'create-sport',
        },
        {
          label: 'Client ID',
          field: 'uuid',
        },
      ]"
      create-text="Add API Client"
      create-permission="create-client"
      :create-endpoint="create.url()"
      edit-permission="update-client"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No API Clients found."
      delete-permission="delete-client"
      :delete-endpoint="destroy.url"
      delete-field="uuid"
      delete-modal-title="Are your sure you want to delete this API Client?"
    />
  </section>
</template>
