<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/positions'
import { SharedData } from '@/types'

interface Props {
  position: App.Data.Position.PositionData
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head :title="`Edit ${position.name} Position`" />

  <section class="position-edit">
    <SharedHero
      :title="`Edit ${position.name} Position`"
      :description="`Edit and update the ${position.name} Position.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ position: position.uuid })"
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
          :value="position.name"
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
          :value="position.known_as ?? ''"
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
            :value="position.sport_id"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update Position' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
