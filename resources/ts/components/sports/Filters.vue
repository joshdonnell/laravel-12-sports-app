<script setup lang="ts">
import searchIcon from '@/../svg/search.svg'

const model = defineModel<string>()
</script>

<template>
  <div class="season-filters row mb-20 flex justify-end">
    <div class="column w-full md:w-1/2 xl:w-1/3">
      <FormGroup
        label="Search Sports"
        input-id="search"
      >
        <FormInput
          id="search"
          v-model="model"
          type="text"
          name="search"
          placeholder="Search Sports by name..."
          :icon="searchIcon"
        />
      </FormGroup>
    </div>
  </div>
</template>
