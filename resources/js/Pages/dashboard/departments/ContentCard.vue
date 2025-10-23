<template>
    <DataTransition
        role="list"
        class="divide-y divide-brand-100 dark:divide-neutral-600 overflow-hidden bg-white dark:bg-neutral-700 ring-1 ring-gray-900/5 sm:rounded-2xl border border-brand-200 dark:border-neutral-600"
    >
        <li v-for="item in data" :key="item.id" class="relative flex justify-between gap-x-6 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2">
                <div class="flex gap-4 items-center">
                    <img :src="item.image_url" class="size-8 rounded-full" />
                    <div class="flex flex-col">
                        <p :class="['text-brand-600 dark:text-neutral-300', 'text-sm font-semibold leading-6 ']">
                            <Link :href="route('dashboard.departments.edit', { department: item.id })">
                                <span class="absolute inset-x-0 -top-px bottom-0 line-clamp-1" />
                                {{ item.name }}
                            </Link>
                        </p>
                        <p :class="['text-brand-400 dark:text-neutral-300 font-semibold', 'flex text-xs leading-5 ']">
                            {{ item.short_name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-x-4">
                <div class="hidden sm:flex sm:flex-col sm:items-end gap-1">
                    <p class="text-brand-400 dark:text-neutral-300 font-semibold text-xs">{{ item.email ?? '' }}</p>
                    <p class="text-brand-400 dark:text-neutral-300 font-semibold text-xs">{{ phoneNumberFormatter(item.phone_number) }}</p>
                </div>
                <ChevronRightIcon class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
            </div>
        </li>
        <li v-if="data.length == 0" class="relative flex justify-between gap-x-6 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2">
                <div class="min-w-0 flex-auto text-brand-500 dark:text-neutral-300">No department available.</div>
            </div>
        </li>
    </DataTransition>
</template>

<script setup lang="ts">
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import DataTransition from '@/components/transitions/DataTransition.vue'
import { phoneNumberFormatter } from '@/utils'

import { Department } from './departmentInt'

defineProps<{
    data: Department[]
}>()
</script>
