<script setup lang="ts" generic="T">
import leftIcon from '@/../svg/left.svg'
import rightIcon from '@/../svg/right.svg'
import { Link } from '@inertiajs/vue3'
import { usePaginator } from 'momentum-paginator'
interface Props {
  pagination: Paginator<T>
}

const props = defineProps<Props>()
const paginator = ref(usePaginator(props.pagination))

watchEffect(() => {
  paginator.value = usePaginator(props.pagination)
})
</script>

<template>
  <div
    v-if="paginator.total > paginator.itemsPerPage"
    class="shared-pagination mt-40"
  >
    <nav class="relative flex items-center gap-x-15">
      <component
        :is="paginator.previous.isActive ? Link : 'span'"
        :href="paginator.previous.url"
        class="default-transition default-transition flex h-25 w-25 items-center justify-center rounded-[3px]"
        :class="{
          'bg-grey-200 text-black hover:bg-blue-200 hover:text-white': paginator.previous.isActive,
          'cursor-not-allowed bg-grey-100 text-grey-300': !paginator.previous.isActive,
        }"
      >
        <InlineSvg
          :src="leftIcon"
          class="w-[13px]"
        />
      </component>

      <component
        :is="page.isActive ? Link : 'span'"
        v-for="(page, key) in paginator.pages"
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
        :is="paginator.next.isActive ? Link : 'span'"
        :href="paginator.next.url"
        class="default-transition default-transition flex h-25 w-25 items-center justify-center rounded-[3px]"
        :class="{
          'bg-grey-200 text-black hover:bg-blue-200 hover:text-white': paginator.next.isActive,
          'cursor-not-allowed bg-grey-100 text-grey-300': !paginator.next.isActive,
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
