<script setup lang="ts">
import { update } from '@/actions/App/Http/Controllers/Settings/ProfileController'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { send } from '@/routes/verification'
import { SharedData } from '@/types'

interface Props {
  status?: string
}

withDefaults(defineProps<Props>(), {
  status: '',
})

defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()

const user = page.props.auth.user
</script>

<template>
  <Head title="Update Profile" />

  <section>
    <div class="container">
      <div class="flex flex-col items-start gap-y-20">
        <p class="bold underline">Update your name and email address</p>

        <Form
          v-slot="{ processing, errors }"
          v-bind="update.form()"
          class="flex flex-col items-start gap-y-10"
        >
          <FormGroup
            :error="errors.name"
            label="Name"
          >
            <FormInput
              id="name"
              required
              type="text"
              name="name"
              placeholder="Name"
              :value="user.name"
            />
          </FormGroup>

          <FormGroup
            :error="errors.email"
            label="Email"
          >
            <FormInput
              id="email"
              required
              type="email"
              name="email"
              placeholder="Email"
              :value="user.email"
            />
          </FormGroup>

          <div v-if="user.email_verified_at === null">
            <BtnPrimary :href="send()"> Click here to resend the verification email. </BtnPrimary>

            <div v-if="status === 'verification-link-sent'">
              <p>A new verification link has been sent to your email address.</p>
            </div>
          </div>

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
          >
            {{ processing ? 'Updating...' : 'Update' }}
          </BtnPrimary>
        </Form>
      </div>
    </div>
  </section>
</template>
