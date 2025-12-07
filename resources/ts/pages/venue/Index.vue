<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, destroy, edit, index } from '@/routes/venues'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  venues: Pagination<App.Data.Venue.VenueData>
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
      only: ['venues'],
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
  <Head title="Venues" />

  <section class="venue-index">
    <VenueFilters
      v-model="filters"
      :sports="sports"
    />

    <SharedHero
      title="Venues"
      description="A list of Sports Venues that can attached to individual teams."
    />

    <SharedResourceTable
      :data="venues"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Address',
          field: 'address',
        },
        {
          label: 'Sport',
          field: 'sport_name',
          permission: 'create-sport',
        },
      ]"
      create-text="Add Venue"
      create-permission="create-venue"
      :create-endpoint="create.url()"
      edit-permission="update-venue"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Venues found."
      delete-permission="delete-venue"
      :delete-endpoint="destroy.url"
      delete-field="uuid"
      delete-modal-title="Are your sure you want to delete this Venue?"
    />
  </section>
</template>
