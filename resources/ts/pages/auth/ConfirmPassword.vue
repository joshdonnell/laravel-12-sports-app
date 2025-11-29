<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue'
import { store } from '@/routes/password/confirm'

defineOptions({ layout: AuthLayout })
</script>

<template>
  <Head title="Confirm Password" />

  <div class="row flex justify-center">
    <div class="column w-full xl:w-4/12">
      <SharedCard
        title="Confirm Password"
        title-tag="h1"
      >
        <Form
          v-slot="{ errors, processing }"
          v-bind="store.form()"
          reset-on-success
        >
          <FormGroup
            :error="errors.password"
            label="Password"
          >
            <FormInput
              id="password"
              required
              type="password"
              name="password"
              placeholder="Password"
            />
          </FormGroup>

          <BtnPrimary
            tag="button"
            type="submit"
            class="mt-20"
            :disabled="processing"
          >
            {{ processing ? 'Confirming...' : 'Confirm' }}
          </BtnPrimary>
        </Form>
      </SharedCard>
    </div>
  </div>
</template>
