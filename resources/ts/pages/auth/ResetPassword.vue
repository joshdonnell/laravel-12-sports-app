<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue'
import { update } from '@/routes/password'

interface Props {
  email: string
  token: string
}

defineProps<Props>()
</script>

<template>
  <AuthLayout title="Reset Password">
    <div class="row flex justify-center">
      <div class="column w-full xl:w-4/12">
        <SharedCard
          title="Reset Password"
          title-tag="h1"
        >
          <Form
            v-slot="{ errors, processing }"
            v-bind="update.form()"
            :reset-on-success="['password', 'password_confirmation']"
          >
            <FormGroup
              :error="errors.email"
              label="Email"
            >
              <FormText
                id="email"
                required
                type="email"
                name="email"
                placeholder="Email"
                autocomplete="off"
                :value="email"
              />
            </FormGroup>

            <FormGroup
              :error="errors.password"
              label="Password"
              class="mt-15"
            >
              <FormText
                id="password"
                required
                type="password"
                name="password"
                placeholder="Password"
              />
            </FormGroup>

            <FormGroup
              :error="errors.password_confirmation"
              label="Confirm Password"
              class="mt-15"
            >
              <FormText
                id="password_confirmation"
                required
                type="password"
                name="password_confirmation"
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
  </AuthLayout>
</template>
