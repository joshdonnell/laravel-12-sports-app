<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue'
import { store } from '@/routes/two-factor/login'

interface AuthConfigContent {
  title: string
  description: string
  toggleText: string
}

defineOptions({ layout: AuthLayout })

const authConfigContent = computed<AuthConfigContent>(() => {
  if (showRecoveryInput.value) {
    return {
      title: 'Two Factor Recovery Code',
      description: 'Please confirm access to your account by entering one of your emergency recovery codes.',
      toggleText: 'Use 2FA code',
    }
  }

  return {
    title: 'Two-Factor Challenge',
    description: 'Enter the authentication code provided by your authenticator application.',
    toggleText: 'Use recovery codes',
  }
})

const showRecoveryInput = ref<boolean>(false)

const toggleRecoveryMode = (clearErrors: () => void): void => {
  showRecoveryInput.value = !showRecoveryInput.value
  clearErrors()
}

const code = ref<string>('')
</script>

<template>
  <Head :title="authConfigContent.title" />

  <div class="row flex justify-center">
    <div class="column w-full xl:w-4/12">
      <SharedCard
        :title="authConfigContent.title"
        :description="authConfigContent.description"
        title-tag="h1"
      >
        <Form
          v-slot="{ errors, processing, clearErrors }"
          v-bind="store.form()"
          :reset-on-success="['code']"
        >
          <template v-if="!showRecoveryInput">
            <input
              type="hidden"
              name="code"
              :value="code"
            />

            <FormGroup
              :error="errors.code"
              input-id="codes"
              label="Code"
            >
              <FormPin v-model="code" />
            </FormGroup>
          </template>

          <template v-else>
            <FormGroup
              :error="errors.recovery_code"
              label="Recovery Code"
            >
              <FormInput
                id="recovery_code"
                type="text"
                name="recovery_code"
                required
                placeholder="Enter recovery code"
                :autofocus="showRecoveryInput"
              />
            </FormGroup>
          </template>

          <div class="mt-20 flex items-center gap-x-20">
            <BtnPrimary
              tag="button"
              type="submit"
              :disabled="processing"
            >
              {{ processing ? 'Confirming...' : 'Confirm' }}
            </BtnPrimary>

            <span class="copy-sm text-black">or</span>

            <button
              class="copy-sm default-transition font-medium text-black underline hover:text-blue-300"
              @click="() => toggleRecoveryMode(clearErrors)"
            >
              {{ authConfigContent.toggleText }}
            </button>
          </div>
        </Form>
      </SharedCard>
    </div>
  </div>
</template>
