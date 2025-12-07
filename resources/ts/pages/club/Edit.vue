<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/clubs'

interface Props {
  club: App.Data.Club.ClubData
}

const props = defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const existingLogo = ref<string | null>(props.club.logo ?? null)
</script>

<template>
  <Head :title="`Edit ${club.name}`" />

  <section class="club-edit">
    <SharedHero
      :title="`Edit ${club.name}`"
      :description="`Edit and update ${club.name}.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ club: club.uuid })"
      :transform="
        (data) => ({
          ...data,
          remove_logo: !!(club.logo && existingLogo === null),
        })
      "
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
          :value="club.name"
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
          :value="club.known_as ?? ''"
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
          :value="club.official_name ?? ''"
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
          :value="club.code ?? ''"
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
          :existing-url="existingLogo"
          @file-removed="existingLogo = null"
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
          :value="club.bio ?? ''"
        />
      </FormGroup>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update Club' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
