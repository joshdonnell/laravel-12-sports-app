<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Auth/EmailVerificationNotificationController'
import AuthLayout from '@/layouts/AuthLayout.vue'
import { logout } from '@/routes'

defineOptions({ layout: AuthLayout })
</script>

<template>
  <Head title="Verify Email" />

  <div class="row flex justify-center">
    <div class="column w-full xl:w-4/12">
      <SharedCard
        title="Verify Email"
        description="Please verify your email address by clicking on the link we just emailed to you."
        title-tag="h1"
      >
        <Form
          v-slot="{ processing }"
          v-bind="store.form()"
          class="flex flex-col items-start gap-y-10"
        >
          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
          >
            {{ processing ? 'Sending verification email...' : 'Resend Verification Email' }}
          </BtnPrimary>

          <Link
            class="mt-10 inline-block underline"
            :href="logout()"
          >
            Log out
          </Link>
        </Form>
      </SharedCard>
    </div>
  </div>
</template>
