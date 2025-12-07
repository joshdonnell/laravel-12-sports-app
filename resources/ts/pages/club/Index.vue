<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, destroy, edit, index } from '@/routes/clubs'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  clubs: Pagination<App.Data.Club.ClubCardData>
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
      only: ['clubs'],
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
  <Head title="Clubs" />

  <section class="club-index">
    <ClubFilters
      v-model="filters"
      :sports="sports"
    />

    <SharedHero
      title="Clubs"
      description="A list of Clubs that can contain many teams."
    />

    <SharedResourceTable
      :data="clubs"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Known as',
          field: 'known_as',
        },
        {
          label: 'Sport',
          field: 'sport_name',
          permission: 'create-sport',
        },
      ]"
      create-text="Add Club"
      create-permission="create-club"
      :create-endpoint="create.url()"
      edit-permission="update-club"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Clubs found."
      delete-permission="delete-club"
      :delete-endpoint="destroy.url"
      delete-field="uuid"
      delete-modal-title="Are your sure you want to delete this Club?"
    />
  </section>
</template>
