<script setup lang="ts">
import searchIcon from '@/../svg/search.svg'
import { SharedData } from '@/types'

interface Props {
  sports?: App.Data.Shared.SelectData[] | null
}

interface Model {
  search: string
  sport: string
}

defineProps<Props>()
const model = defineModel<Model>({ required: true })
const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <div class="rounds-filters row mb-20 flex flex-wrap justify-end gap-y-10">
    <FormGroup
      v-if="auth.can['create-sport']"
      label="Filter Rounds by Sports"
      input-id="sport"
      class="column w-full md:w-1/2 xl:w-1/3"
    >
      <FormSelect
        id="sport"
        v-model="model.sport"
        name="search"
        placeholder="Select a sport"
        :options="sports || []"
      />
    </FormGroup>

    <FormGroup
      label="Search Rounds"
      input-id="search"
      class="column w-full md:w-1/2 xl:w-1/3"
    >
      <FormInput
        id="search"
        v-model="model.search"
        type="text"
        name="search"
        placeholder="Search Rounds by name..."
        :icon="searchIcon"
      />
    </FormGroup>
  </div>
</template>
