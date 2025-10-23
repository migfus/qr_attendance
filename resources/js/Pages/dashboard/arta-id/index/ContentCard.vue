<template>
    <DataTransition
        role="list"
        class="gap-2 divide-y divide-brand-100 dark:divide-neutral-600 overflow-hidden bg-white dark:bg-neutral-700 sm:rounded-2xl border border-brand-200 dark:border-neutral-600"
    >
        <li v-for="item in data" :key="item.id" class="relative flex justify-between gap-x-6 px-6 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2 flex-1">
                <BasicTransition>
                    <img v-if="item.files.length > 0" :src="item.files[0].thumbnail_url ?? ''" class="size-10 rounded-full mt-1" />
                </BasicTransition>

                <div class="min-w-0 flex-auto">
                    <p :class="['text-brand-700 dark:text-neutral-300', 'text-sm font-semibold leading-6 ']">
                        <Link :href="route('dashboard.arta-id.show', { arta_id: item.id })">
                            <span class="absolute inset-x-0 -top-px bottom-0" />
                            {{ employeeFullName(item, true) }}
                        </Link>
                    </p>
                    <div
                        :class="[
                            item.created_at ? 'text-brand-500 dark:text-neutral-400' : 'text-brand-700 dark:text-neutral-300',
                            'mt-1 flex text-xs leading-5 '
                        ]"
                    >
                        <div v-if="item.latest_request_status" class="font-semibold -mt-1 flex gap-1 items-center">
                            <div v-html="item.latest_request_status.request_status_type.hero_icon.content" class="inline" />
                            {{ item.latest_request_status.request_status_type.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-x-4 line-clamp-1">
                <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p v-if="item.created_at" class="text-xs leading-5 text-gray-500 dark:text-neutral-300">{{ moment(item.created_at).fromNow(true) }}</p>
                    <p v-if="item.created_at" class="text-xs leading-5 text-gray-500 dark:text-neutral-400 font-semibold flex gap-2 items-center">
                        <img :src="item.department.image_url" class="size-3 inline" />{{ item.department.name }}
                    </p>
                </div>
                <ChevronRightIcon class="h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
            </div>
        </li>
        <li v-if="data.length == 0" class="relative flex justify-between gap-x-6 px-4 py-2 hover:bg-gray-50 dark:hover:bg-neutral-800 sm:px-6">
            <div class="flex min-w-0 gap-x-2">
                <div class="min-w-0 flex-auto text-brand-500 dark:text-neutral-300">No employee found.</div>
            </div>
        </li>
    </DataTransition>
</template>

<script setup lang="ts">
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import moment from 'moment'
import DataTransition from '@/components/transitions/DataTransition.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { employeeFullName } from '@/utils'
import { Employee } from '../artaIdInt'

defineProps<{
    data: Employee[]
}>()
</script>
