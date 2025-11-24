<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/EmailResetNotificationController'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { login } from '@/routes'
</script>

<template>
  <AuthLayout title="Forgot Password">
    <div class="row flex justify-center">
      <div class="column w-full xl:w-4/12">
        <SharedCard
          title="Forgot your password"
          title-tag="h1"
        >
          <Form
            v-slot="{ errors, processing, recentlySuccessful }"
            v-bind="store.form()"
            :reset-on-success="['email']"
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
              />
            </FormGroup>

            <div class="mt-5 text-right">
              <Link
                :href="login.url()"
                class="copy-sm default-transition font-medium text-black hover:text-blue-300"
              >
                Back to login
              </Link>
            </div>

            <BtnPrimary
              tag="button"
              type="submit"
              :disabled="processing"
              class="mt-20"
            >
              {{ processing ? 'Sending...' : 'Reset password' }}
            </BtnPrimary>

            <Transition
              enter-active-class="transition ease-in-out"
              enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out"
              leave-to-class="opacity-0"
            >
              <p
                v-show="recentlySuccessful"
                class="copy-md mt-20 text-blue-300"
              >
                If an account with that email exists, we have emailed your password reset link!
              </p>
            </Transition>
          </Form>
        </SharedCard>
      </div>
    </div>
  </AuthLayout>
</template>
