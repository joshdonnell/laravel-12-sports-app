<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { logout } from '@/routes'
import { edit as password } from '@/routes/password'
import { show as twoFactor } from '@/routes/two-factor'
import { edit as userProfile } from '@/routes/user-profile'
import { SharedData } from '@/types'

usePage<SharedData>()
</script>

<template>
  <DashboardLayout>
    <section>
      <div class="container">
        <div class="flex flex-col items-start gap-y-20">
          <p class="bold underline">Dashboard</p>

          <Link
            class="underline"
            :href="userProfile()"
            >Account Settings</Link
          >
          <Link
            class="underline"
            :href="password()"
            >Update Password</Link
          >
          <Link
            class="underline"
            :href="twoFactor()"
            >Two-Factor Settings</Link
          >
          <Link
            class="underline"
            :href="logout()"
          >
            Log out
          </Link>
        </div>
      </div>
    </section>
  </DashboardLayout>
</template>
