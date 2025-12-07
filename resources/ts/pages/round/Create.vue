<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/rounds'
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
  <Head title="Create Round" />

  <section class="round-create">
    <SharedHero
      title="Create Round"
      description="Create a new round."
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
          placeholder="Round name eg: (Round 1)"
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
          {{ processing ? 'Saving...' : 'Create Round' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
