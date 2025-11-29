<script setup lang="ts" generic="T">
import type { Pagination, SharedData } from '@/types'

type Props<T> = {
  data: Pagination<T>
  columns: {
    label: string
    field: keyof T
  }[]
  createText?: string
  createPermission?: string
  createEndpoint?: string
  editPermission?: string
  editEndpoint?: (id: string) => string
  editField?: keyof T & string
  noResults: string
}

defineProps<Props<T>>()

const page = usePage<SharedData>()
const auth = page.props.auth
</script>

<template>
  <section class="shared-resourceTable mt-20 md:mt-30 xl:mt-40">
    <template v-if="data.data && data.data.length">
      <div class="w-[calc(100%+20px)] overflow-auto pb-10 pr-20 lg:w-full lg:overflow-hidden lg:pb-0 lg:pr-0">
        <div class="relative w-full min-w-[600px] max-w-full">
          <div class="row relative mb-10 flex px-15">
            <div
              v-for="column in columns"
              :key="column.field"
              class="column w-3/12"
            >
              <p class="copy font-semibold text-black">{{ column.label }}</p>
            </div>
          </div>

          <div class="flex flex-col gap-y-5">
            <div
              v-for="(item, key) in data.data"
              :key="key"
              class="rounded-[10px] bg-white-100 px-15 py-[12px]"
            >
              <div class="row flex items-center">
                <div
                  v-for="column in columns"
                  :key="column.field"
                  class="column w-3/12"
                >
                  <p class="heading-lg line-clamp-1 text-black">{{ item[column.field] }}</p>
                </div>

                <div
                  v-if="editPermission && editEndpoint && editField && auth.can[editPermission]"
                  class="column ml-auto flex w-3/12 justify-end"
                >
                  <Link
                    :href="editEndpoint(item[editField] as string)"
                    class="default-transition text-blue-200 underline hover:text-blue-300"
                  >
                    Edit
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <SharedPagination :pagination="data" />
    </template>

    <template v-else>
      <p class="heading-lg font-semibold text-black">{{ noResults }}</p>
    </template>

    <div
      v-if="createPermission && auth.can[createPermission]"
      class="mt-40 flex justify-end"
    >
      <BtnPrimary :href="createEndpoint">{{ createText }}</BtnPrimary>
    </div>
  </section>
</template>
