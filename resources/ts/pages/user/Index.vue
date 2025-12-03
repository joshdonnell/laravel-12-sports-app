<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { create, destroy, edit, index } from '@/routes/users'
import type { Pagination } from '@/types'
import { watchDebounced } from '@vueuse/core'

interface Props {
  users: Pagination<App.Data.User.UserData>
  search?: string
}

const props = withDefaults(defineProps<Props>(), {
  search: '',
})

defineOptions({ layout: DashboardLayout })

const search = ref<string>(props.search)

watchDebounced(
  search,
  () => {
    router.visit(index(), {
      only: ['users'],
      data: {
        search: search.value,
      },
      preserveState: true,
    })
  },
  {
    debounce: 300,
  },
)
</script>

<template>
  <Head title="Users" />

  <section class="user-index">
    <UserFilters v-model="search" />

    <SharedHero
      title="Users"
      description="A list of all users within your Sport."
    />

    <SharedResourceTable
      :data="users"
      :columns="[
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Email',
          field: 'email',
        },
        {
          label: 'Roles',
          field: 'role_names',
        },
      ]"
      create-text="Add User"
      create-permission="create-user"
      :create-endpoint="create.url()"
      edit-permission="update-user"
      :edit-endpoint="edit.url"
      edit-field="uuid"
      no-results="No Users found."
      delete-permission="delete-user"
      :delete-endpoint="destroy.url"
      delete-field="uuid"
      delete-modal-title="Are your sure you want to delete this user?"
    />
  </section>
</template>
