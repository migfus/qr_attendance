<template>
    <div class="flex justify-between gap-2">
        <DataTransition class="flex flex-col gap-2 overflow-hidden flex-grow">
            <div
                v-for="(item, idx) in requests_statuses"
                :key="item.id"
                class="bg-light-001 dark:bg-dark-002 p-4 rounded xl:first:rounded-tl-xl xl:last:rounded-bl-xl"
                :style="{ transitionDelay: `${idx * 15}ms` }"
            >
                <div class="relative">
                    <span
                        v-if="idx !== requests_statuses.length - 1"
                        aria-hidden="true"
                        :class="['absolute left-5 top-5 -ml-px w-0.5 bg-gray-200 dark:bg-dark-002 h-[100px]']"
                    />
                    <div class="relative flex flex-col">
                        <div v-if="item.user">
                            <div class="flex justify-between">
                                <div class="flex gap-3 relative">
                                    <div class="relative">
                                        <img
                                            :class="[
                                                idx == 0 ? 'ring-green-300 animate-pulse dark:ring-green-500' : 'ring-white dark:ring-neutral-800',
                                                'flex size-8 items-center justify-center rounded-full bg-gray-400 ring-4 m-1 z-10 '
                                            ]"
                                            :src="item.user.avatar_url"
                                        />
                                        <span class="absolute top-5 left-5 rounded-full bg-white dark:bg-neutral-700 p-0.5 pb-0 pr-0">
                                            <div
                                                v-html="item.request_status_type.hero_icon.content"
                                                class="h-5 w-5 text-gray-400 dark:text-neutral-300"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <div class="font-medium text-gray-900 dark:text-neutral-200">
                                                    {{ item.user.name }}
                                                </div>
                                            </div>
                                            <p class="mt-0.5 text-sm text-brand-700 font-semibold dark:text-neutral-300">{{ item.request_status_type.name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded text-sm text-gray-900 dark:text-neutral-200">
                                    {{ messengerStyleTime(item.created_at) }}
                                </div>
                            </div>

                            <div class="mt-2 text-sm text-gray-700 dark:text-neutral-300 dark:bg-dark-001 rounded-xl p-4">
                                <p v-html="item.content" />
                            </div>
                        </div>
                        <div v-else>
                            <div class="flex justify-between">
                                <div class="flex gap-3 relative">
                                    <div class="relative">
                                        <div
                                            v-html="item.request_status_type.hero_icon.content"
                                            class="size-5 mt-1 ml-1 text-gray-500 dark:text-neutral-300"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <p class="mt-0.5 text-sm text-brand-700 font-semibold dark:text-neutral-300">{{ item.request_status_type.name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded text-sm text-gray-900 dark:text-neutral-200">
                                    {{ messengerStyleTime(item.created_at) }}
                                </div>
                            </div>

                            <div class="mt-2 text-sm text-gray-700 dark:text-neutral-300 dark:bg-dark-001 rounded-xl p-4">
                                <p v-html="item.content" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DataTransition>

        <div class="hidden xl:flex bg-light-001 dark:bg-dark-002 rounded-r-xl p-4 flex-col justify-between items-center">
            <ChevronUpIcon class="size-5 text-gray-400 dark:text-neutral-300 mb-1" />
            <div
                class="flex-1 w-0.5 mx-auto mt-2"
                style="background: linear-gradient(to bottom, rgba(100, 100, 100, 1) 0%, rgba(100, 100, 100, 0.1) 100%)"
                :class="['dark:bg-none', 'bg-gray-200']"
            />
            <span
                class="text-gray-900 dark:text-light-002 font-semibold text-sm"
                style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 0.1em"
            >
                Request Status
            </span>
            <div
                class="flex-1 w-0.5 mx-auto mt-2"
                style="background: linear-gradient(to bottom, rgba(100, 100, 100, 1) 0%, rgba(100, 100, 100, 0.1) 100%)"
                :class="['dark:bg-none', 'bg-gray-200']"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'

import { RequestStatus } from '../artaIdInt'
import { messengerStyleTime } from '@/utils'

import { ChevronUpIcon } from '@heroicons/vue/24/outline'

defineProps<{
    requests_statuses: RequestStatus[]
    name: string
}>()
</script>
