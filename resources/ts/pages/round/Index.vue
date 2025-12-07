<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, edit, index } from '@/routes/rounds'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  rounds: Pagination<App.Data.Round.RoundData>
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
      only: ['rounds'],
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
  <Head title="Rounds" />

  <section class="round-index">
    <RoundFilters
      v-model="filters"
      :sports="sports"
    />

    <SharedHero
      title="Rounds"
      description="A list of all rounds."
    />

    <SharedResourceTable
      :data="rounds"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Round Number',
          field: 'round_number',
        },
        {
          label: 'Sport',
          field: 'sport_name',
          permission: 'create-sport',
        },
      ]"
      create-text="Add Round"
      create-permission="create-round"
      :create-endpoint="create.url()"
      edit-permission="update-round"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Rounds found."
    />
  </section>
</template>
