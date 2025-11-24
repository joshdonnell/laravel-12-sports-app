<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Settings/ProfileController'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
</script>

<template>
  <DashboardLayout title="Update Password">
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
            >
              <FormText
                id="current_password"
                required
                type="password"
                name="current_password"
                placeholder="Current Password"
              />
            </FormGroup>

            <FormGroup
              :error="errors.password"
              label="New Password"
            >
              <FormText
                id="password"
                required
                type="password"
                name="password"
                placeholder="New Password"
              />
            </FormGroup>

            <FormGroup
              :error="errors.password_confirmation"
              label="Confirm New Password"
            >
              <FormText
                id="password_confirmation"
                required
                type="password"
                name="password_confirmation"
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
  </DashboardLayout>
</template>
