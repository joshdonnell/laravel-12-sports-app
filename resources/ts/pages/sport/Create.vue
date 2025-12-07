<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/sports'

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head title="Create Sport" />

  <section class="sport-create">
    <SharedHero
      title="Create Sport"
      description="Create a new sport."
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
          placeholder="Sport name eg: (Netball)"
        />
      </FormGroup>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Create Sport' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
