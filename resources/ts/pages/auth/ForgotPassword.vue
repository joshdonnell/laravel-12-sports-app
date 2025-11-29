<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/EmailResetNotificationController'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { login } from '@/routes'

interface Props {
  status?: string
}

defineProps<Props>()
defineOptions({ layout: AuthLayout })
</script>

<template>
  <Head title="Forgot Password" />

  <div class="row flex justify-center">
    <div class="column w-full xl:w-4/12">
      <SharedCard
        title="Forgot your password"
        title-tag="h1"
        description="Enter your email to receive a password reset link."
      >
        <SharedSuccessMessage
          v-if="status"
          class="mb-20"
        >
          {{ status }}
        </SharedSuccessMessage>

        <Form
          v-slot="{ errors, processing }"
          v-bind="store.form()"
          :disable-while-processing="true"
        >
          <FormGroup
            :error="errors.email"
            label="Email"
            input-id="email"
          >
            <FormInput
              id="email"
              type="email"
              name="email"
              required
              autofocus
              autocomplete="off"
              placeholder="example@email.com"
            />
          </FormGroup>

          <div class="mt-5 text-right">
            <SharedTextLink :href="login.url()">Return to login</SharedTextLink>
          </div>

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
            class="mt-20"
          >
            {{ processing ? 'Sending...' : 'Reset password' }}
          </BtnPrimary>
        </Form>
      </SharedCard>
    </div>
  </div>
</template>
