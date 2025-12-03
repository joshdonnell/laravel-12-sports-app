<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, edit, index } from '@/routes/sports'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  sports: Pagination<App.Data.Sport.SportData>
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
      only: ['sports'],
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
  <Head title="Sports" />

  <section class="sport-index">
    <SportsFilters v-model="search" />

    <SharedHero
      title="Sports"
      description="A list of all sports, this is a section for Super Admins only."
    />

    <SharedResourceTable
      :data="sports"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Sport ID',
          field: 'uuid',
        },
      ]"
      create-text="Add Sport"
      create-permission="create-sport"
      :create-endpoint="create.url()"
      edit-permission="update-sport"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Sports found."
    />
  </section>
</template>
