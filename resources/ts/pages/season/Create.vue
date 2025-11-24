<script setup lang="ts">
import { store } from '@/routes/seasons'
</script>

<template>
  <section class="py-100">
    <div class="container space-y-40">
      <h1 class="font-bold">Create Season</h1>

      <hr />

      <Form
        v-slot="{ processing, errors }"
        v-bind="store.form()"
        class="space-y-10"
      >
        <FormGroup
          input-id="name"
          label="Name"
          :error="errors.name"
        >
          <FormText
            id="name"
            name="name"
          />
        </FormGroup>

        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
          >Create</BtnPrimary
        >
      </Form>
    </div>
  </section>
</template>
