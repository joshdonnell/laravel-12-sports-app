<script setup lang="ts">
import buttonIcon from '@/../svg/button.svg'
import logoutIcon from '@/../svg/logout.svg'
import settingsIcon from '@/../svg/settings.svg'
import { logout } from '@/routes'
import userProfile from '@/routes/user-profile'
import type { SharedData } from '@/types'
import { Link } from '@inertiajs/vue3'
import { onClickOutside } from '@vueuse/core'
import gsap from 'gsap'

const page = usePage<SharedData>()
const user = page.props.auth.user

const useMenu = () => {
  const isOpen = ref(false)
  const menuItems = shallowRef([
    {
      name: 'Settings',
      icon: settingsIcon,
      link: userProfile.edit(),
    },
  ])
  const actions = useTemplateRef<HTMLElement>('actions')

  const showActionsPanel = (el: Element, done: () => void) => {
    gsap
      .to(el, {
        duration: 0.2,
        y: '-5px',
        autoAlpha: 1,
      })
      .then(() => done())
  }

  const hideActionsPanel = (el: Element, done: () => void) => {
    gsap
      .to(el, {
        duration: 0.2,
        autoAlpha: 0,
        y: 0,
      })
      .then(() => done())
  }

  onClickOutside(actions, () => {
    isOpen.value = false
  })

  return {
    isOpen,
    menuItems,
    actions,
    showActionsPanel,
    hideActionsPanel,
  }
}
const { isOpen, menuItems, actions, showActionsPanel, hideActionsPanel } = useMenu()
</script>

<template>
  <div
    ref="actions"
    class="shared-userActions relative mt-auto shrink-0 bg-white-100 px-20 pb-20"
  >
    <div class="absolute bottom-full left-0 h-40 w-full bg-gradient-to-b from-transparent to-white-100"></div>

    <button
      class="default-transition flex w-full items-center justify-between rounded-[10px] border border-white p-10 text-black hover:bg-white"
      :class="{ 'bg-white': isOpen }"
      @click="isOpen = !isOpen"
    >
      <span class="copy flex items-center gap-x-10">
        <SharedAvatar />
        <span class="font-medium">{{ user.name }}</span>
      </span>

      <InlineSvg
        :src="buttonIcon"
        class="w-[6px]"
      />
    </button>

    <Transition
      @enter="showActionsPanel"
      @leave="hideActionsPanel"
    >
      <div
        v-if="isOpen"
        class="invisible absolute bottom-full left-0 w-full px-20 opacity-0"
      >
        <div class="w-full rounded-[10px] bg-purple">
          <div class="copy flex items-center gap-x-10 p-10">
            <SharedAvatar />

            <div class="flex flex-col gap-[2px]">
              <p class="copy-sm font-medium">{{ user.name }}</p>
              <p class="copy-xs font-medium">{{ user.email }}</p>
            </div>
          </div>

          <ul>
            <template v-if="menuItems">
              <li
                v-for="(item, key) in menuItems"
                :key="key"
                class="border-t border-grey-200 px-10 py-20"
              >
                <Link
                  :href="item.link"
                  class="heading-md default-transition flex items-center gap-x-20 text-black hover:text-blue-200"
                  :class="{ 'text-blue-300': $page.url === item.link.url }"
                >
                  <InlineSvg
                    class="w-15"
                    :src="item.icon"
                  />
                  <span>{{ item.name }}</span>
                </Link>
              </li>
            </template>

            <li class="border-t border-grey-200 px-10 py-20">
              <Link
                :href="logout()"
                class="heading-md default-transition flex items-center gap-x-20 text-black hover:text-blue-200"
              >
                <InlineSvg
                  class="w-15"
                  :src="logoutIcon"
                />
                <span>Logout</span>
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </Transition>
  </div>
</template>
