<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/venues'
import { SharedData } from '@/types'

interface Props {
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })
const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head title="Create Venue" />

  <section class="venue-create">
    <SharedHero
      title="Create Venue"
      description="Create a new Sports Venue."
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="store.form()"
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
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Create Venue' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
