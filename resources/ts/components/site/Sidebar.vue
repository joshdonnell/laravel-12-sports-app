<script setup lang="ts">
import clientsIcon from '@/../svg/clients.svg'
import clubsIcon from '@/../svg/club.svg'
import dashboardIcon from '@/../svg/dashboard.svg'
import downIcon from '@/../svg/down.svg'
import fixturesIcon from '@/../svg/fixtures.svg'
import logoMarque from '@/../svg/logo-marque.svg'
import logoName from '@/../svg/logo-name.svg'
import playersIcon from '@/../svg/player.svg'
import roundsIcon from '@/../svg/rounds.svg'
import scoringIcon from '@/../svg/scoring.svg'
import seasonIcon from '@/../svg/season.svg'
import sportsIcon from '@/../svg/sports.svg'
import standingsIcon from '@/../svg/standings.svg'
import statsIcon from '@/../svg/stats.svg'
import superAdminIcon from '@/../svg/superAdmin.svg'
import teamIcon from '@/../svg/teams.svg'
import tournamentsIcon from '@/../svg/tournaments.svg'
import usersIcon from '@/../svg/users.svg'
import venuesIcon from '@/../svg/venues.svg'
import rulesIcon from '../../../svg/rules.svg'

import { dashboard } from '@/routes'
import seasons from '@/routes/seasons'
import sports from '@/routes/sports'
import users from '@/routes/users'
import { SharedData } from '@/types'
import type { RouteDefinition } from '@/wayfinder'
import { Link } from '@inertiajs/vue3'
import gsap from 'gsap'
interface MenuItem {
  name: string
  icon: string
  link?: string | RouteDefinition<'get'>
  active?: boolean
  permission?: string
  children?: MenuItem[]
}

const useMenu = () => {
  const menuItems = ref<MenuItem[]>([
    {
      name: 'Dashboard',
      icon: dashboardIcon,
      link: dashboard(),
    },
    {
      name: 'Scoring',
      icon: scoringIcon,
      link: '/',
      permission: 'list-scoring',
    },
    {
      name: 'Seasons & Rounds',
      icon: seasonIcon,
      permission: 'list-scoring',
      active: false,
      children: [
        {
          name: 'Seasons',
          icon: seasonIcon,
          link: seasons.index(),
          permission: 'list-scoring',
        },
        {
          name: 'Rounds',
          icon: roundsIcon,
          link: '/',
          permission: 'list-rounds',
        },
      ],
    },
    {
      name: 'Tournaments',
      icon: tournamentsIcon,
      link: '/',
      permission: 'list-tournaments',
    },
    {
      name: 'Fixtures',
      icon: fixturesIcon,
      link: '/',
      permission: 'list-fixtures',
    },
    {
      name: 'Clubs, Teams & Players',
      icon: clubsIcon,
      active: false,
      permission: 'list-clubs',
      children: [
        {
          name: 'Clubs',
          icon: clubsIcon,
          link: '/',
          permission: 'list-clubs',
        },
        {
          name: 'Team',
          icon: teamIcon,
          link: '/',
          permission: 'list-teams',
        },
        {
          name: 'Players',
          icon: playersIcon,
          link: '/',
          permission: 'list-players',
        },
        {
          name: 'Venues',
          icon: venuesIcon,
          link: '/',
          permission: 'list-venues',
        },
      ],
    },
    {
      name: 'Stats & Standings',
      icon: statsIcon,
      active: false,
      permission: 'list-stats',
      children: [
        {
          name: 'Stats',
          icon: statsIcon,
          link: '/',
          permission: 'list-stats',
        },
        {
          name: 'Standings',
          icon: standingsIcon,
          link: '/',
          permission: 'list-standings',
        },
      ],
    },
    // {
    //   name: 'Tools',
    //   icon: toolsIcon,
    //   link: '/',
    // },
    // {
    //   name: 'Reports',
    //   icon: rulesIcon,
    //   link: '/',
    // },
    {
      name: 'Rulesets',
      icon: rulesIcon,
      link: '/',
      permission: 'list-rulesets',
    },
    {
      name: 'Users',
      icon: usersIcon,
      link: users.index(),
      permission: 'list-users',
    },
    {
      name: 'Super Admin',
      icon: superAdminIcon,
      permission: 'list-sports',
      children: [
        {
          name: 'Sports',
          icon: sportsIcon,
          link: sports.index(),
          permission: 'list-sports',
        },
        {
          name: 'API Clients',
          icon: clientsIcon,
          link: '/',
          permission: 'list-clients',
        },
      ],
    },
  ])
  const toggleChildren = (item: MenuItem) => {
    if (!item.children || item.link) return

    item.active = !item.active
  }

  const showChildren = (el: Element, done: () => void) => {
    gsap
      .to(el, {
        duration: 0.2,
        height: 'auto',
        autoAlpha: 1,
      })
      .then(() => done())
  }

  const hideChildren = (el: Element, done: () => void) => {
    gsap
      .to(el, {
        duration: 0.2,
        height: 0,
        autoAlpha: 0,
      })
      .then(() => done())
  }

  onMounted(() => {
    const currentUrl = page.url

    menuItems.value.forEach((item) => {
      if (!item.children?.length) return

      item.active = item.children.some((child) => typeof child.link === 'object' && currentUrl.includes(child.link.url))
    })
  })

  return {
    menuItems,
    toggleChildren,
    showChildren,
    hideChildren,
  }
}

const page = usePage<SharedData>()
const auth = page.props.auth
const { menuItems, toggleChildren, showChildren, hideChildren } = useMenu()
</script>

<template>
  <div class="site-sidebar flex h-full max-h-full w-full max-w-[255px] flex-col overflow-hidden rounded-tr-[20px] bg-white-100">
    <div class="flex items-center justify-between border-b border-white p-20">
      <InlineSvg
        class="w-[32px] shrink-0"
        :src="logoMarque"
      />

      <InlineSvg
        class="w-[88px]"
        :src="logoName"
      />
    </div>

    <nav class="relative h-full max-h-full overflow-auto px-10 py-20">
      <ul class="gap flex flex-col gap-y-[2px]">
        <template
          v-for="(item, key) in menuItems"
          :key="key"
        >
          <li
            v-if="!item.permission || auth.can[item.permission]"
            class="group w-full"
          >
            <component
              :is="item.link ? Link : 'button'"
              v-bind="item.link ? { href: item.link } : {}"
              class="heading-md default-transition flex w-full items-center justify-between gap-x-10 px-10 py-10 text-black hover:bg-white hover:text-blue-200"
              :class="{
                'rounded-[10px]': !item.children,
                'rounded-t-[10px]': item.children,
                'bg-white': item.active,
                'bg-white text-blue-300':
                  item.link &&
                  typeof item.link === 'object' &&
                  (item.link.url === '/dashboard' ? page.url === item.link.url : page.url.includes(item.link.url)),
              }"
              @click="toggleChildren(item)"
            >
              <span class="flex items-center gap-x-20">
                <InlineSvg
                  class="w-15"
                  :src="item.icon"
                />
                <span>{{ item.name }}</span>
              </span>

              <InlineSvg
                v-if="item.children"
                class="default-transition w-[6px] shrink-0"
                :class="{ 'rotate-180': item.active }"
                :src="downIcon"
              />
            </component>

            <Transition
              @enter="showChildren"
              @leave="hideChildren"
            >
              <ul
                v-if="item.children && item.active"
                class="relative mb-10 mt-1 h-0 overflow-hidden rounded-b-[10px] bg-white opacity-0"
              >
                <template v-if="!item.permission || auth.can[item.permission]">
                  <li
                    v-for="(child, childKey) in item.children"
                    :key="childKey"
                    class="px-10 py-[5px] first:pt-10 last:pb-10"
                  >
                    <Link
                      :href="child.link"
                      class="heading-md default-transition flex items-center gap-x-20 text-black hover:text-blue-200"
                      :class="{
                        'text-blue-300': child.link && typeof child.link === 'object' && page.url.includes(child.link.url),
                      }"
                    >
                      <InlineSvg
                        class="w-15"
                        :src="child.icon"
                      />
                      <span>{{ child.name }}</span>
                    </Link>
                  </li>
                </template>
              </ul>
            </Transition>
          </li>
        </template>
      </ul>
    </nav>

    <SharedUserActions />
  </div>
</template>
