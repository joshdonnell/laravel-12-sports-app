<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/venues'
import { SharedData } from '@/types'

interface Props {
  venue: App.Data.Venue.VenueData
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })
const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head :title="`Edit Venue ${venue.name}`" />

  <section class="venue-create">
    <SharedHero
      :title="`Edit Venue ${venue.name}`"
      :description="`Edit and update ${venue.name}.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ venue: venue.uuid })"
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
          placeholder="Venue name eg: (Copper Box Arena London)"
          :value="venue.name"
        />
      </FormGroup>

      <FormGroup
        :error="errors.address"
        label="Address"
        input-id="address"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="address"
          type="text"
          name="address"
          placeholder="Venue address (Optional)"
          :value="venue.address ?? ''"
        />
      </FormGroup>

      <FormGroup
        :error="errors.website"
        label="Website URL"
        input-id="website"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="website"
          type="text"
          name="website"
          placeholder="Venue website (Optional)"
          :value="venue.website ?? ''"
        />
      </FormGroup>

      <template v-if="auth.can['create-sport']">
        <FormGroup
          :error="errors.sport"
          label="Sport"
          input-id="sport"
          class="column w-full lg:w-1/2"
        >
          <FormSelect
            id="sport"
            :options="sports || []"
            name="sport_id"
            required
            autocomplete="none"
            placeholder="Select a sport"
            :disabled="!sports"
            :value="venue.sport_id"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update Venue' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
