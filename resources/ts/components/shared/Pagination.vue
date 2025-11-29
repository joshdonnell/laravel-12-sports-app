<script setup lang="ts" generic="T">
import leftIcon from '@/../svg/left.svg'
import rightIcon from '@/../svg/right.svg'
import { Link } from '@inertiajs/vue3'
import { usePaginator } from 'momentum-paginator'
interface Props {
  pagination: Paginator<T>
}

const props = defineProps<Props>()
const { total, itemsPerPage, previous, next, pages } = usePaginator(props.pagination)
</script>

<template>
  <div
    v-if="total > itemsPerPage"
    class="shared-pagination mt-40"
  >
    <nav class="relative flex items-center gap-x-15">
      <component
        :is="previous.isActive ? Link : 'span'"
        :href="previous.url"
        class="default-transition default-transition flex h-25 w-25 items-center justify-center rounded-[3px]"
        :class="{
          'bg-grey-200 text-black hover:bg-blue-200 hover:text-white': previous.isActive,
          'cursor-not-allowed bg-grey-100 text-grey-300': !previous.isActive,
        }"
      >
        <InlineSvg
          :src="leftIcon"
          class="w-[13px]"
        />
      </component>

      <component
        :is="page.isActive ? Link : 'span'"
        v-for="(page, key) in pages"
        :key="key"
        :href="page.url"
        class="copy default-transition text-black"
        :class="{
          'font-semibold': page.isCurrent,
          'hover:text-blue-200': !page.isCurrent && page.isActive,
        }"
      >
        {{ page.label }}
      </component>

      <component
        :is="next.isActive ? Link : 'span'"
        :href="next.url"
        class="default-transition default-transition flex h-25 w-25 items-center justify-center rounded-[3px]"
        :class="{
          'bg-grey-200 text-black hover:bg-blue-200 hover:text-white': next.isActive,
          'cursor-not-allowed bg-grey-100 text-grey-300': !next.isActive,
        }"
      >
        <InlineSvg
          :src="rightIcon"
          class="w-[13px]"
        />
      </component>
    </nav>
  </div>
</template>
