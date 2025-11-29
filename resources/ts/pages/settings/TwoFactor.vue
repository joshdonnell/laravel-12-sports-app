<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { disable, enable } from '@/routes/two-factor'

interface Props {
  requiresConfirmation?: boolean
  twoFactorEnabled?: boolean
}

withDefaults(defineProps<Props>(), {
  requiresConfirmation: true,
  twoFactorEnabled: false,
})

defineOptions({ layout: DashboardLayout })

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth()
const showSetupModal = ref<boolean>(false)

onUnmounted(() => {
  clearTwoFactorAuthData()
})
</script>

<template>
  <Head title="Two Factor Settings" />

  <section>
    <div class="container">
      <div class="flex flex-col items-start gap-y-20">
        <p class="bold underline">Manage your two-factor authentication settings</p>

        <div
          v-if="!twoFactorEnabled"
          class="space-y-4"
        >
          <p>Two-factor authentication is disabled</p>

          <p>
            When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a
            TOTP-supported application on your phone.
          </p>

          <div>
            <BtnPrimary
              v-if="hasSetupData"
              tag="button"
              @click="showSetupModal = true"
              >Continue Setup</BtnPrimary
            >
            <Form
              v-else
              v-slot="{ processing }"
              v-bind="enable.form()"
              @success="showSetupModal = true"
            >
              <BtnPrimary
                tag="button"
                type="submit"
                :disabled="processing"
                >Enable 2FA</BtnPrimary
              >
            </Form>
          </div>
        </div>

        <div
          v-else
          class="space-y-4 flex flex-col items-start justify-start"
        >
          <p>Two-factor authentication is enabled</p>

          <p>
            With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the
            TOTP-supported application on your phone.
          </p>

          <AuthTwoFactorRecoveryCodes />

          <div class="relative inline">
            <Form
              v-slot="{ processing }"
              v-bind="disable.form()"
            >
              <BtnPrimary
                tag="button"
                type="submit"
                :disabled="processing"
              >
                Disable 2FA
              </BtnPrimary>
            </Form>
          </div>
        </div>

        <AuthTwoFactorSetupModal
          v-model:is-open="showSetupModal"
          :requires-confirmation="requiresConfirmation"
          :two-factor-enabled="twoFactorEnabled"
        />
      </div>
    </div>
  </section>
</template>
