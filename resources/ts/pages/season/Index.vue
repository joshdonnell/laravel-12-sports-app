<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, edit, index } from '@/routes/seasons'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  seasons: Pagination<App.Data.Season.SeasonData>
  search?: string
}

const props = withDefaults(defineProps<Props>(), {
  search: '',
})

defineOptions({ layout: DashboardLayout })

const search = ref<string>(props.search)

watchDebounced(
  search,
  () => {
    router.visit(index(), {
      only: ['seasons'],
      data: {
        search: search.value,
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
  <Head title="Seasons" />

  <section class="season-index">
    <SeasonFilters v-model="search" />

    <SharedHero
      title="Seasons"
      description="A list of all seasons."
    />

    <SharedResourceTable
      :data="seasons"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Season ID',
          field: 'uuid',
        },
      ]"
      create-text="Add Season"
      create-permission="create-season"
      :create-endpoint="create.url()"
      edit-permission="update-season"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Seasons found."
    />
  </section>
</template>
