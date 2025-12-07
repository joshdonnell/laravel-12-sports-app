<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { store } from '@/routes/clubs'
import { SharedData } from '@/types'

interface Props {
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head title="Create Club" />

  <section class="club-create">
    <SharedHero
      title="Create Club"
      description="Create a new club, once created we can assign a club to a team via the team edit page."
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="store.form()"
      class="form form--crud"
    >
      <FormGroup
        :error="errors.name"
        label="Name"
        input-id="name"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="name"
          type="text"
          name="name"
          required
          autofocus
          placeholder="Name eg: (London Pulse)"
        />
      </FormGroup>

      <FormGroup
        :error="errors.known_as"
        label="Known As"
        input-id="known_as"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="known_as"
          type="text"
          name="known_as"
          placeholder="Known As eg: (PUL) Optional"
        />
      </FormGroup>

      <FormGroup
        :error="errors.official_name"
        label="Official Name (Optional)"
        input-id="official_name"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="official_name"
          type="text"
          name="official_name"
          placeholder="Official Name eg: (London Pulse Netball Club)"
        />
      </FormGroup>

      <FormGroup
        :error="errors.code"
        label="Code (Optional)"
        input-id="code"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="code"
          type="text"
          name="code"
          placeholder="Code eg: (ENG)"
        />
      </FormGroup>

      <FormGroup
        :error="errors.logo"
        label="Logo (Optional)"
        input-id="logo"
        class="column w-full"
      >
        <FormFileUpload
          id="logo"
          name="logo"
        />
      </FormGroup>

      <FormGroup
        :error="errors.bio"
        label="Bio (Optional)"
        input-id="bio"
        class="column w-full"
      >
        <FormTextarea
          id="code"
          name="bio"
          placeholder="Write bio here"
        />
      </FormGroup>

      <template v-if="auth.can['create-sport']">
        <FormGroup
          :error="errors.sport"
          label="Sport"
          input-id="sport"
          class="column w-full lg:w-1/2"
        >
          <FormSelect
            id="sport"
            :options="sports || []"
            name="sport_id"
            required
            autocomplete="none"
            placeholder="Select a sport"
            :disabled="!sports"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Create Club' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
