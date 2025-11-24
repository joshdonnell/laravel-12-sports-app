<script setup lang="ts">
import { regenerateRecoveryCodes } from '@/routes/two-factor'

const { recoveryCodesList, fetchRecoveryCodes, errors } = useTwoFactorAuth()
const isRecoveryCodesVisible = ref<boolean>(false)
const recoveryCodeSectionRef = ref<HTMLDivElement | null>(null)

const toggleRecoveryCodesVisibility = async () => {
  if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
    await fetchRecoveryCodes()
  }

  isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value

  if (isRecoveryCodesVisible.value) {
    await nextTick()
    recoveryCodeSectionRef.value?.scrollIntoView({ behavior: 'smooth' })
  }
}

onMounted(async () => {
  if (!recoveryCodesList.value.length) {
    await fetchRecoveryCodes()
  }
})
</script>

<template>
  <div>
    <div>
      <h2>2FA Recovery Codes</h2>
      <p>Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.</p>
    </div>
    <div>
      <div
        id="recovery-codes"
        class="gap-3 sm:flex-row sm:items-center sm:justify-between flex flex-col"
      >
        <BtnPrimary
          tag="button"
          @click="toggleRecoveryCodesVisibility"
        >
          {{ isRecoveryCodesVisible ? 'Hide' : 'View' }} Recovery Codes
        </BtnPrimary>

        <Form
          v-if="isRecoveryCodesVisible && recoveryCodesList.length"
          v-slot="{ processing }"
          v-bind="regenerateRecoveryCodes.form()"
          method="post"
          :options="{ preserveScroll: true }"
          @success="fetchRecoveryCodes"
        >
          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
            >Regenerate Codes</BtnPrimary
          >
        </Form>
      </div>
      <div :class="['relative overflow-hidden transition-all duration-300', isRecoveryCodesVisible ? 'h-auto opacity-100' : 'h-0 opacity-0']">
        <div v-if="errors?.length">
          <p
            v-for="(error, index) in errors"
            :key="index"
          >
            {{ error }}
          </p>
        </div>
        <div
          v-else
          class="space-y-3"
        >
          <div
            ref="recoveryCodeSectionRef"
            class="font-mono grid gap-1 text-sm"
          >
            <div
              v-if="!recoveryCodesList.length"
              class="space-y-2"
            >
              <div
                v-for="n in 8"
                :key="n"
              >
                Loading...
              </div>
            </div>
            <div
              v-for="(code, index) in recoveryCodesList"
              v-else
              :key="index"
            >
              {{ code }}
            </div>
          </div>
          <p class="text-xs">
            Each recovery code can be used once to access your account and will be removed after use. If you need more, click
            <span class="font-bold">Regenerate Codes</span> above.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
