<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/sports'

interface Props {
  sport: App.Data.Sport.SportData
}

defineProps<Props>()

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head :title="`Edit ${sport.name} Sport`" />

  <section class="sport-edit">
    <SharedHero
      :title="`Edit ${sport.name} Sport`"
      :description="`Edit and update the ${sport.name} sport.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ sport: sport.uuid })"
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
          placeholder="Sport name eg: (Netball)"
          :value="sport.name"
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
