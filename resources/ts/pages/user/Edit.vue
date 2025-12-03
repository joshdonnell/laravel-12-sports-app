<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { update } from '@/routes/users'
import { SharedData } from '@/types'

interface Props {
  user: App.Data.User.UserData
  roles: App.Data.Shared.SelectData[]
  sports?: App.Data.Shared.SelectData[] | null
}

defineProps<Props>()
defineOptions({ layout: DashboardLayout })

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <Head :title="`Edit User ${user.name}`" />

  <section class="user-edit">
    <SharedHero
      :title="`Edit User ${user.name}`"
      :description="`Edit and update ${user.name}.`"
    />

    <Form
      v-slot="{ processing, errors }"
      v-bind="update.form({ user: user.uuid })"
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
          placeholder="Name eg: (Joe Blogs)"
          :value="user.name"
        />
      </FormGroup>

      <FormGroup
        :error="errors.email"
        label="Email"
        input-id="email"
        class="column w-full lg:w-1/2"
      >
        <FormInput
          id="email"
          type="email"
          name="email"
          required
          placeholder="example@email.com"
          :value="user.email"
        />
      </FormGroup>

      <template v-if="auth.can['delete-user']">
        <FormGroup
          :error="errors.password"
          label="New Password"
          input-id="password"
          class="column w-full lg:w-1/2"
        >
          <FormInput
            id="password"
            type="password"
            name="password"
            autocomplete="none"
            placeholder="Password"
          />
        </FormGroup>

        <FormGroup
          :error="errors.password_confirmation"
          label="Confirm New Password"
          input-id="password_confirmation"
          class="column w-full lg:w-1/2"
        >
          <FormInput
            id="confirm_password"
            type="password"
            name="password_confirmation"
            autocomplete="none"
            placeholder="Password"
          />
        </FormGroup>
      </template>

      <FormGroup
        :error="errors.role"
        label="User Role"
        input-id="role"
        class="column w-full lg:w-1/2"
      >
        <FormSelect
          id="role"
          :options="roles || []"
          name="role"
          required
          autocomplete="none"
          placeholder="Select a role"
          :value="user.role"
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
            name="sport"
            required
            autocomplete="none"
            placeholder="Select a sport"
            :disabled="!sports"
            :value="user.sport_id"
          />
        </FormGroup>
      </template>

      <div class="column w-full">
        <BtnPrimary
          tag="button"
          type="submit"
          :disabled="processing"
        >
          {{ processing ? 'Saving...' : 'Update' }}
        </BtnPrimary>
      </div>
    </Form>
  </section>
</template>
