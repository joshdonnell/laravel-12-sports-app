<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/clients'
import { SharedData } from '@/types'

interface Props {
  client: App.Data.Client.ClientData
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head :title="`Edit API Client ${client.name}`" />

  <section class="client-edit">
    <SharedHero
      :title="`Edit API Client ${client.name}`"
      :description="`Edit and update ${client.name}.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ client: client.uuid })"
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
          placeholder="Name eg: (EN Website)"
          :value="client.name"
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
            :value="client.sport_id"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update API Client' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
