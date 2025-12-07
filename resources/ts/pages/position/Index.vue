<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, destroy, edit, index } from '@/routes/positions'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  positions: Pagination<App.Data.Position.PositionData>
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
      only: ['positions'],
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
  <Head title="Positions" />

  <section class="position-index">
    <PositionFilters
      v-model="filters"
      :sports="sports"
    />

    <SharedHero
      title="Positions"
      description="A list of Positions which players can be assigned to."
    />

    <SharedResourceTable
      :data="positions"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Known As',
          field: 'known_as',
        },
        {
          label: 'Sport',
          field: 'sport_name',
          permission: 'create-sport',
        },
      ]"
      create-text="Add Position"
      create-permission="create-position"
      :create-endpoint="create.url()"
      edit-permission="update-position"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Positions found."
      delete-permission="delete-position"
      :delete-endpoint="destroy.url"
      delete-field="uuid"
      delete-modal-title="Are your sure you want to delete this Position?"
    />
  </section>
</template>
