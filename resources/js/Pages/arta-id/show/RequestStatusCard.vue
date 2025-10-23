<template>
    <div>
        <BasicCard title="Request Status" description="Request status update list" :icon="ClockIcon">
            <div class="divide-y divide-gray-100 dark:divide-neutral-500 overflow-hidden">
                <div v-for="(item, idx) in requests_statuses" :key="item.id" class="py-4">
                    <div class="relative">
                        <span
                            v-if="idx !== requests_statuses.length - 1"
                            :class="['absolute left-5 top-5 -ml-px h-[100px] w-0.5 bg-gray-200 dark:bg-neutral-500']"
                            aria-hidden="true"
                        />
                        <div class="relative flex items-start space-x-3">
                            <template v-if="item.user">
                                <div class="relative">
                                    <img
                                        :class="[
                                            idx == 0 ? 'ring-green-300 dark:ring-green-600 animate-pulse' : 'ring-white dark:ring-neutral-800',
                                            'flex size-8 items-center justify-center rounded-full bg-gray-400 ring-4 m-1 z-10 dark:bg-neutral-800'
                                        ]"
                                        :src="item.user.avatar_url"
                                    />

                                    <span class="absolute -bottom-0.5 -right-1 rounded-tl bg-white dark:bg-neutral-700 px-0.5 py-px">
                                        <div
                                            v-html="item.request_status_type.hero_icon.content"
                                            class="h-5 w-5 text-gray-400 dark:text-neutral-200"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 dark:text-neutral-200">
                                                {{ item.user.name }} - {{ moment(item.created_at).fromNow(true) }}
                                            </div>
                                        </div>
                                        <p class="mt-0.5 text-sm text-brand-700 font-semibold dark:text-neutral-200">{{ item.request_status_type.name }}</p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700 dark:text-neutral-300">
                                        <p v-html="item.content" />
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div>
                                    <div class="relative px-1">
                                        <div
                                            :class="[
                                                idx == 0 ? 'ring-green-300 animate-pulse dark:ring-green-600' : 'ring-white dark:ring-neutral-800',
                                                'flex size-8 items-center justify-center rounded-full bg-brand-50 ring-4 m-1 z-10 dark:bg-neutral-700 ml-0'
                                            ]"
                                        >
                                            <div
                                                v-html="item.request_status_type.hero_icon.content"
                                                class="size-5 mt-1 ml-1 text-gray-500 dark:text-neutral-200"
                                                aria-hidden="true"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 py-1.5">
                                    <div class="text-sm text-gray-500">
                                        <div class="font-medium text-gray-900 dark:text-neutral-200">
                                            {{ item.request_status_type.name }} - {{ moment(item.created_at).fromNow(true) }}
                                        </div>
                                        {{ ' ' }}
                                        <!-- assigned
                                        {{ ' ' }}
                                        <div class="font-medium text-gray-900">{{ item.request_status_type.name }}</div> -->
                                        {{ ' ' }}
                                        <span class="whitespace-nowrap dark:text-neutral-300" v-html="item.content" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import { ClockIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'

import { RequestStatus } from '@/Pages/dashboard/arta-id/artaIdInt'

import moment from 'moment'

defineProps<{
    requests_statuses: RequestStatus[]
}>()
</script>
