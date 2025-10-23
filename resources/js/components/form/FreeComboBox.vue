<template>
    <BasicTransition>
        <div>
            <!-- NOTE: COMBOBOX -->
            <Combobox as="div" v-model="$model">
                <!-- NOTE: LABEL -->
                <ComboboxLabel v-if="!noLabel" class="block text-sm font-medium text-gray-700 dark:text-brand-100">{{ name }}</ComboboxLabel>
                <div class="relative mt-1">
                    <!-- NOTE: INPUT -->
                    <ComboboxInput
                        :displayValue="(val) => val.display_name"
                        :placeholder="placeholder"
                        @input="
                            query = $event.target.value
                            $model = $event.target.value
                        "
                        class="w-full rounded-xl border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 py-2 pl-3 pr-10 shadow-sm focus:border-brand-500 focus:outline-hidden focus:ring-1 focus:ring-brand-500 sm:text-sm dark:text-brand-100"
                    />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-hidden">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>

                    <!-- NOTE: OPTIONS -->
                    <ComboboxOptions
                        v-if="filtered_rows.length > 0"
                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-neutral-700 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-hidden sm:text-sm"
                    >
                        <ComboboxOption v-for="row in filtered_rows" :key="row.id" :value="row" as="template" v-slot="{ active, selected }">
                            <li
                                :class="[
                                    'relative cursor-default select-none py-2 pl-3 pr-9',
                                    active
                                        ? 'bg-brand-600 text-white dark:bg-neutral-800'
                                        : selected
                                        ? 'bg-brand-600 text-white dark:bg-brand-800'
                                        : 'text-gray-900 dark:bg-neutral-700 dark:text-neutral-300',
                                    'cursor-pointer'
                                ]"
                            >
                                <div :class="['truncate flex gap-2 items-center', selected && 'font-semibold']">
                                    <span
                                        v-if="row.hero_icon"
                                        v-html="row.hero_icon.content"
                                        :class="[active ? 'text-brand-100' : selected ? 'text-neutral-200' : 'text-brand-600 dark:text-brand-200', 'inline']"
                                    />
                                    <img v-if="row.image_url" :src="row.image_url" class="size-4" />
                                    <span v-if="row.short_name" class="inline">{{ row.short_name }} -</span>
                                    <span class="inline">{{ row.name }}</span>
                                </div>

                                <span
                                    v-if="selected"
                                    :class="[
                                        'absolute inset-y-0 right-0 flex items-center pr-4',
                                        active ? 'text-white' : selected ? 'text-neutral-300' : 'text-brand-600'
                                    ]"
                                >
                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </div>

                <label v-if="errors" class="block text-sm font-medium text-red-500 dark:text-red-500">
                    <li v-for="item in errors">
                        {{ item }}
                    </li>
                </label>
            </Combobox>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import BasicTransition from '../transitions/BasicTransition.vue'
import { Combobox, ComboboxButton, ComboboxInput, ComboboxLabel, ComboboxOption, ComboboxOptions } from '@headlessui/vue'

import { computed, ref } from 'vue'
import { Select } from '@/globalTypes'

const $props = defineProps<{
    name: string
    data: Select[]
    noLabel?: boolean
    placeholder?: string
    errors?: string[]
    is_department?: boolean
}>()
const $model = defineModel<string>({ default: '' })

const query = ref('')
const filtered_rows = computed(() =>
    query.value === ''
        ? $props.data
        : $props.data.filter((row) => {
              const q = query.value.toLowerCase()
              if (row.short_name) {
                  return row.name.toLowerCase().includes(q) || row.short_name.toLowerCase().includes(q)
              } else {
                  return row.name.toLowerCase().includes(q)
              }
          })
)
</script>
