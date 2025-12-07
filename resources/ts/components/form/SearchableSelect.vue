<script setup lang="ts">
import searchIcon from '@/../svg/search.svg'
import { onClickOutside } from '@vueuse/core'
interface Props {
  options: App.Data.Shared.SelectData[]
}

const props = defineProps<Props>()
const model = defineModel<string | number>()

const useSearchableSelect = () => {
  const searchQuery = ref('')
  const isOpen = ref(false)
  const selectedOption = ref<App.Data.Shared.SelectData | null>(null)
  const searchableSelect = ref<HTMLElement | null>(null)

  const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options

    const query = searchQuery.value.toLowerCase()
    return props.options.filter((option) => option.label.toLowerCase().includes(query))
  })

  const handleChange = (option: App.Data.Shared.SelectData) => {
    selectedOption.value = option
    searchQuery.value = option.label
    isOpen.value = false
  }

  onClickOutside(searchableSelect, () => (isOpen.value = false))

  watch(selectedOption, () => {
    model.value = selectedOption.value?.value ?? ''
  })

  onMounted(() => {
    selectedOption.value = props.options.find((option) => option.value === model.value) ?? null
  })

  return {
    searchQuery,
    isOpen,
    filteredOptions,
    handleChange,
    searchableSelect,
  }
}
const { searchQuery, isOpen, filteredOptions, handleChange, searchableSelect } = useSearchableSelect()
</script>

<template>
  <div
    ref="searchableSelect"
    class="form-searchableSelect relative"
  >
    <FormInput
      v-model="searchQuery"
      type="text"
      role="combobox"
      :aria-expanded="isOpen ? 'true' : 'false'"
      autocomplete="off"
      placeholder="Type to search..."
      :icon="searchIcon"
      @click="isOpen = true"
    />

    <div
      v-if="isOpen"
      class="absolute left-0 top-full z-10 mt-10 w-full rounded-[10px] bg-grey-100 p-20"
    >
      <div class="flex max-h-[300px] flex-col gap-y-10 overflow-y-auto">
        <template v-if="filteredOptions.length">
          <button
            v-for="(option, key) in filteredOptions"
            :key="option.value"
            role="option"
            :value="option.value"
            :aria-selected="model === option.value ? 'true' : 'false'"
            :tabindex="key"
            class="flex items-center justify-between gap-x-10"
            :class="{
              'text-grey-300': model !== option.value,
              'text-black': model === option.value,
            }"
            @click="handleChange(option)"
            @keyup.enter="handleChange(option)"
          >
            <span>{{ option.label }}</span>

            <img
              v-if="option.icon"
              :src="option.icon"
              :alt="`${option.label} Flag`"
              class="h-25 w-25 shrink-0 object-cover"
            />
          </button>
        </template>

        <SharedNoResults v-else>No results found</SharedNoResults>
      </div>
    </div>
  </div>
</template>
