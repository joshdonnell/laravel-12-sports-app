<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/SessionController'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { request as forgotPassword } from '@/routes/password/index'

defineOptions({ layout: AuthLayout })
</script>

<template>
  <Head title="Login" />

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
              autocomplete="email"
              placeholder="example@email.com"
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
              autocomplete="password"
              placeholder="Password"
            />
          </FormGroup>

          <div class="mt-5 text-right">
            <SharedTextLink :href="forgotPassword()">Forgot your password?</SharedTextLink>
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
</template>
