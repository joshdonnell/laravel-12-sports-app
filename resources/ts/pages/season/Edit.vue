<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/seasons'

interface Props {
  season: App.Data.Season.SeasonData
}

defineProps<Props>()

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head :title="`Edit ${season.name} Season`" />

  <section class="season-edit">
    <SharedHero
      :title="`Edit ${season.name} Season`"
      :description="`Edit and update the ${season.name} season.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ season: season.uuid })"
      class="form form--crud"
    >
      <FormGroup
        :error="errors.name"
        label="Name"
        input-id="name"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="name"
          type="text"
          name="name"
          required
          autofocus
          placeholder="Season name eg: (2025/2026)"
          :value="season.name"
        />
      </FormGroup>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
