<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineOptions({ layout: DashboardLayout })
</script>

<template>
  <Head title="Dashboard" />

  <SharedHero
    title="Dashboard"
    description="Your overview of fixtures, results, standings, and transfers."
  />
</template>
