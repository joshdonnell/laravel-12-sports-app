<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/positions'
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
  <Head title="Create Position" />

  <section class="position-create">
    <SharedHero
      title="Create Position"
      description="Create a new Position, once created we can assign a position to a player via the player edit page."
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
          placeholder="Name eg: (Goal Defence)"
        />
      </FormGroup>

      <FormGroup
        :error="errors.known_as"
        label="Known As"
        input-id="known_as"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="known_as"
          type="text"
          name="known_as"
          placeholder="Known as eg: (GK or GD)"
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
          {{ processing ? 'Saving...' : 'Create Position' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
