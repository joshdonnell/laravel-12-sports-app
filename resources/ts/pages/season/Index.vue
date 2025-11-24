<script setup lang="ts">
import { create, edit } from '@/routes/seasons'
import { Pagination, SharedData } from '@/types'

interface Props {
  seasons: Pagination<App.Data.Season.SeasonData>
}

defineProps<Props>()
const page = usePage<SharedData>()
</script>

<template>
  <section class="py-100">
    <div class="container flex flex-col gap-y-20">
      <div class="flex items-center justify-between">
        <h1 class="font-bold">All Seasons</h1>

        <template v-if="page.props.auth.can['create-season']">
          <BtnPrimary :href="create.url()">Create Season</BtnPrimary>
        </template>
      </div>

      <hr />

      <template v-if="seasons.data.length > 0">
        <template
          v-for="season in seasons.data"
          :key="season.uuid"
        >
          <Link
            v-if="page.props.auth.can['update-season']"
            :href="edit.url({ season: season.uuid })"
            >Edit - {{ season.name }}</Link
          >
          <p v-else>{{ season.name }} (No edit access)</p>
        </template>
      </template>

      <template v-else>
        <p>No Seasons Found</p>
      </template>
    </div>
  </section>
</template>
