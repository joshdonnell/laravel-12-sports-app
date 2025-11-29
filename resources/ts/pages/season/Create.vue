<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/seasons'

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head title="Create Season" />

  <section class="season-create">
    <SharedHero
      title="Create Season"
      description="Create a new season."
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
          placeholder="Season name eg: (2025/2026)"
        />
      </FormGroup>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Create' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
