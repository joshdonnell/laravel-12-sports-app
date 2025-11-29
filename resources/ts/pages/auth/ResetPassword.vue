<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue'
import { store } from '@/routes/password'

interface Props {
  email: string
  token: string
}

defineProps<Props>()
defineOptions({ layout: AuthLayout })
</script>

<template>
  <Head title="Reset Password" />

  <div class="row flex justify-center">
    <div class="column w-full xl:w-4/12">
      <SharedCard
        title="Reset Password"
        title-tag="h1"
        description="Please enter your new password below."
      >
        <Form
          v-slot="{ errors, processing }"
          :transform="(data) => ({ ...data, token, email })"
          v-bind="store.form()"
          :reset-on-success="['password', 'password_confirmation']"
          :disable-while-processing="true"
        >
          <FormGroup
            :error="errors.email"
            label="Email"
            :input-id="email"
          >
            <FormInput
              id="email"
              type="email"
              name="email"
              required
              autocomplete="off"
              placeholder="example@email.com"
              :value="email"
            />
          </FormGroup>

          <FormGroup
            :error="errors.password"
            label="Password"
            input-id="password"
            class="mt-15"
          >
            <FormInput
              id="password"
              type="password"
              name="password"
              required
              autofocus
              autocomplete="new-password"
              placeholder="Password"
            />
          </FormGroup>

          <FormGroup
            :error="errors.password_confirmation"
            label="Confirm Password"
            class="mt-15"
          >
            <FormInput
              id="password_confirmation"
              type="password"
              name="password_confirmation"
              required
              autocomplete="new-password"
              placeholder="Confirm Password"
            />
          </FormGroup>

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
            class="mt-20"
          >
            {{ processing ? 'Resetting Password...' : 'Reset Password' }}
          </BtnPrimary>
        </Form>
      </SharedCard>
    </div>
  </div>
</template>
