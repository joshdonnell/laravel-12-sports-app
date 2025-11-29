<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Settings/ProfileController'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head title="Update Password" />

  <section>
    <div class="container">
      <div class="flex flex-col items-start gap-y-20">
        <p class="bold underline">Update Password</p>

        <Form
          v-slot="{ errors, processing, recentlySuccessful }"
          v-bind="update.form()"
          :options="{
            preserveScroll: true,
          }"
          reset-on-success
          :reset-on-error="['password', 'password_confirmation', 'current_password']"
          class="space-y-6"
        >
          <FormGroup
            :error="errors.current_password"
            label="Current Password"
            input-id="current_password"
          >
            <FormInput
              id="current_password"
              name="current_password"
              placeholder="Current Password"
              autocomplete="current-password"
              required="required"
            />
          </FormGroup>

          <FormGroup
            :error="errors.password"
            label="New Password"
            input-id="new_password"
          >
            <FormInput
              id="password"
              type="password"
              name="password"
              placeholder="New Password"
              autocomplete="new-password"
              required="required"
            />
          </FormGroup>

          <FormGroup
            :error="errors.password_confirmation"
            label="Confirm New Password"
            input-id="confirm_password"
          >
            <FormInput
              id="password_confirmation"
              required
              type="password"
              name="password_confirmation"
              autocomplete="confirm-password"
              placeholder="Confirm New Password"
            />
          </FormGroup>

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
          >
            {{ processing ? 'Updating...' : 'Update' }}
          </BtnPrimary>

          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-show="recentlySuccessful">Password updated successfully.</p>
          </Transition>
        </Form>
      </div>
    </div>
  </section>
</template>
