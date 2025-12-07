<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/rounds'
import { SharedData } from '@/types'

interface Props {
  round: App.Data.Round.RoundData
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head :title="`Edit ${round.name} Round`" />

  <section class="round-edit">
    <SharedHero
      :title="`Edit ${round.name} Round`"
      :description="`Edit and update the ${round.name} Round.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ round: round.uuid })"
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
          placeholder="Round name eg: (Round 1)"
          :value="round.name"
        />
      </FormGroup>

      <FormGroup
        :error="errors.name"
        label="Round Number"
        input-id="round_number"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="round_number"
          type="number"
          name="round_number"
          required
          autofocus
          placeholder="Round number eg: (1)"
          :value="round.round_number"
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
            :value="round.sport_id"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update Round' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
