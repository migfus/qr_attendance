<template>
    <DataTransition
        role="list"
        class="divide-y divide-brand-100 dark:divide-neutral-600 overflow-hidden bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-gray-900/5 sm:rounded-2xl border border-brand-200 dark:border-neutral-600"
    >
        <li v-for="item in data" :key="item.id" class="relative flex justify-between gap-x-6 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2">
                <div class="min-w-0 flex-auto">
                    <p
                        :class="[
                            item.read_at ? 'text-brand-400 dark:text-neutral-300' : 'text-gray-800 dark:text-neutral-200',
                            'text-sm font-semibold leading-6 '
                        ]"
                    >
                        <Link :href="item.data.url">
                            <span class="absolute inset-x-0 -top-px bottom-0" />
                            {{ item.data.message }}
                        </Link>
                    </p>
                    <p
                        :class="[
                            item.read_at ? 'text-brand-500 dark:text-neutral-400' : 'text-brand-700 dark:text-neutral-300',
                            'mt-1 flex text-xs leading-5 '
                        ]"
                    >
                        <time :datetime="item.created_at">{{ moment(item.created_at).fromNow(true) }}</time>
                    </p>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-x-4">
                <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p v-if="item.read_at" class="mt-1 text-xs leading-5 text-gray-500 dark:text-neutral-300">Marked Read</p>
                </div>
                <ChevronRightIcon class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
            </div>
        </li>
        <li v-if="data.length == 0" class="relative flex justify-between gap-x-6 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2">
                <div class="min-w-0 flex-auto text-brand-500 dark:text-neutral-300">No notification available</div>
            </div>
        </li>
    </DataTransition>
</template>

<script setup lang="ts">
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import moment from 'moment'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { Notification } from './notificationInt'

defineProps<{
    data: Notification[]
}>()
</script>
