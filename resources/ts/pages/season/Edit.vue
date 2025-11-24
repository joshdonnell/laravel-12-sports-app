<script setup lang="ts">
import { update } from '@/routes/seasons'

interface Props {
  season: App.Data.Season.SeasonData
}

defineProps<Props>()
</script>

<template>
  <section class="py-100">
    <div class="container space-y-40">
      <h1 class="font-bold">Update - {{ season.name }}</h1>

      <hr />

      <Form
        v-slot="{ processing, errors }"
        v-bind="update.form({ season: season.uuid })"
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
            :value="season.name"
          />
        </FormGroup>

        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
          >Update</BtnPrimary
        >
      </Form>
    </div>
  </section>
</template>
