<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/SessionController'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { request as forgotPassword } from '@/routes/password/index'
</script>

<template>
  <AuthLayout title="Login">
    <div class="row flex justify-center">
      <div class="column w-full xl:w-4/12">
        <SharedCard
          title="Sign in"
          title-tag="h1"
        >
          <Form
            v-slot="{ errors, processing }"
            v-bind="store.form()"
            :reset-on-success="['password']"
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
                placeholder="example@email.com"
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

            <div class="mt-5 text-right">
              <Link
                :href="forgotPassword()"
                class="copy-sm default-transition font-medium text-black hover:text-blue-300"
              >
                Forgot your password?
              </Link>
            </div>

            <BtnPrimary
              tag="button"
              type="submit"
              :disabled="processing"
              class="mt-20"
            >
              {{ processing ? 'Logging in...' : 'Login' }}
            </BtnPrimary>
          </Form>
        </SharedCard>
      </div>
    </div>
  </AuthLayout>
</template>
