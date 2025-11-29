<script setup lang="ts">
import menuIcon from '@/../svg/settings.svg'
import { onClickOutside } from '@vueuse/core'

const sidebar = useTemplateRef<HTMLElement>('sidebar')
const isOpenOnMobile = ref(false)

onClickOutside(sidebar, () => {
  if (!isOpenOnMobile.value) return
  isOpenOnMobile.value = false
})
</script>

<template>
  <section class="dashboard-layout min-h-svh flex w-full">
    <aside
      class="default-transition invisible fixed left-0 top-0 z-50 h-svh w-full shrink-0 pt-10 opacity-0 xl:visible xl:w-[255px] xl:opacity-100"
      :class="{
        'xl-max:visible xl-max:opacity-100': isOpenOnMobile,
      }"
    >
      <div class="absolute inset-0 bg-black/40 xl:hidden"></div>

      <SiteSidebar
        ref="sidebar"
        class="default-transition relative"
        :class="{
          'left-0 xl-max:visible xl-max:opacity-100': isOpenOnMobile,
          '-left-[255px] xl:left-0': !isOpenOnMobile,
        }"
      />
    </aside>

    <div class="w-full flex-1 p-20 xl:ml-[370px] xl:pl-0 xl:pt-30">
      <div class="mb-20 rounded-[10px] bg-white-100 px-10 py-5 xl:hidden">
        <button
          class="heading-md default-transition flex w-full items-center justify-end gap-x-20 rounded-t-[10px] px-10 py-10 text-black hover:text-blue-200"
          @click="isOpenOnMobile = !isOpenOnMobile"
        >
          <InlineSvg
            class="w-15"
            :src="menuIcon"
          />
          <span>Open Menu</span>
        </button>
      </div>

      <slot />
    </div>
  </section>
</template>
