<script setup lang="ts">
import { confirm } from '@/routes/two-factor'
import { useClipboard } from '@vueuse/core'

interface Props {
  requiresConfirmation: boolean
  twoFactorEnabled: boolean
}

const props = defineProps<Props>()
const isOpen = defineModel<boolean>('isOpen')

const { copy, copied } = useClipboard()
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } = useTwoFactorAuth()

const showVerificationStep = ref(false)

const pinInputContainerRef = ref<HTMLElement | null>(null)

const modalConfig = computed<{
  title: string
  description: string
  buttonText: string
}>(() => {
  if (props.twoFactorEnabled) {
    return {
      title: 'Two-Factor Authentication Enabled',
      description: 'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
      buttonText: 'Close',
    }
  }

  if (showVerificationStep.value) {
    return {
      title: 'Verify Authentication Code',
      description: 'Enter the 6-digit code from your authenticator app',
      buttonText: 'Continue',
    }
  }

  return {
    title: 'Enable Two-Factor Authentication',
    description: 'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
    buttonText: 'Continue',
  }
})

const handleModalNextStep = () => {
  if (props.requiresConfirmation) {
    showVerificationStep.value = true

    nextTick(() => {
      pinInputContainerRef.value?.querySelector('input')?.focus()
    })

    return
  }

  clearSetupData()
  isOpen.value = false
}

const resetModalState = () => {
  if (props.twoFactorEnabled) {
    clearSetupData()
  }

  showVerificationStep.value = false
}

watch(
  () => isOpen.value,
  async (isOpen) => {
    if (!isOpen) {
      resetModalState()
      return
    }

    if (!qrCodeSvg.value) {
      await fetchSetupData()
    }
  },
)
</script>

<template>
  <div
    v-if="isOpen"
    class="auth-twoFactorSetupModal"
  >
    <template v-if="!showVerificationStep">
      <template v-if="errors?.length">
        <p
          v-for="(error, index) in errors"
          :key="index"
        >
          {{ error }}
        </p>
      </template>

      <template v-else>
        <div
          v-if="qrCodeSvg"
          class="w-64"
          v-html="qrCodeSvg"
        />
        <div v-else><span>Loading...</span></div>

        <BtnPrimary
          tag="button"
          class="w-full"
          @click="handleModalNextStep"
        >
          {{ modalConfig.buttonText }}
        </BtnPrimary>

        <p>or, enter the code manually</p>

        <div v-if="!manualSetupKey"><span>Loading...</span></div>

        <template v-else>
          <FormText
            id="manual-setup-key"
            name="manual-setup-key"
            readonly
            :value="manualSetupKey"
          />
          <BtnPrimary
            tag="button"
            @click="copy(manualSetupKey || '')"
          >
            {{ copied ? 'Copied' : 'Copy' }}
          </BtnPrimary>
        </template>
      </template>
    </template>

    <template v-else>
      <Form
        v-slot="{ errors: formErrors, processing }"
        v-bind="confirm.form()"
        reset-on-error
        :reset-on-success="['code']"
        @success="isOpen = false"
      >
        <div ref="pinInputContainerRef">
          <FormGroup
            :error="formErrors?.confirmTwoFactorAuthentication"
            label="Code"
          >
            <FormText
              id="code"
              required
              type="number"
              name="code"
              placeholder="Enter OTP code"
            />
          </FormGroup>

          <BtnPrimary
            tag="button"
            @click="showVerificationStep = false"
            >Back</BtnPrimary
          >

          <BtnPrimary
            tag="button"
            type="submit"
            :disabled="processing"
          >
            {{ processing ? 'Confirming...' : 'Confirm' }}
          </BtnPrimary>
        </div>
      </Form>
    </template>
  </div>
</template>
