<template>
    <div>
        <!-- NOTE: TITLE -->
        <label class="ml-2 font-semibold text-brand-700 text-sm dark:text-neutral-200">Active Users</label>
        <!-- NOTE: CONTENT -->
        <DataTransition>
            <div v-if="$page.props.auth?.active_users.length == 0">
                <p class="text-sm ml-2 text-brand-400">No active users for now</p>
            </div>
            <div
                v-for="(item, index) in $page.props.auth?.active_users"
                :key="index"
                @click="loadingAnimation(index)"
                :class="[
                    'text-brand-700 dark:text-neutral-300 hover:bg-brand-50 dark:hover:bg-dark-003 dark:hover:text-neutral-200 hover:text-brand-800 hover:shadow hover:border-neutral-100 border-white dark:border-neutral-700',
                    'group flex flex-col px-2 py-2 text-sm font-medium rounded-md mb-1 pl-3'
                ]"
            >
                <div class="flex justify-between">
                    <div class="flex justify-start truncate relative">
                        <!-- NOTE IF LOADING -->
                        <img :src="item.user.avatar_url" :class="['mr-3 size-5 rounded-full']" />
                        <div
                            v-if="moment().diff(moment.unix(item.last_seen), 'minutes') <= 5"
                            class="size-2 absolute bg-green-400 rounded-full bottom-0 left-3"
                        >
                            .
                        </div>
                        <div
                            v-else-if="moment().diff(moment.unix(item.last_seen), 'minutes') <= 10"
                            class="size-2 absolute bg-yellow-400 rounded-full bottom-0 left-3"
                        >
                            .
                        </div>

                        <div class="truncate">{{ item.user.name }}</div>
                    </div>
                    <span
                        v-if="moment().diff(moment.unix(item.last_seen), 'minutes') <= 5"
                        class="bg-green-300 px-2 rounded-2xl text-brand-500 border border-green-100 text-xs"
                    >
                        {{ shortTimeAgoUnix(item.last_seen) }}
                    </span>
                    <span
                        v-else-if="moment().diff(moment.unix(item.last_seen), 'minutes') <= 30"
                        class="bg-yellow-300 px-2 rounded-2xl text-yellow-700 border border-yellow-400 text-xs"
                    >
                        {{ shortTimeAgoUnix(item.last_seen) }}
                    </span>
                    <span v-else class="bg-brand-50 px-2 rounded-2xl text-brand-500 border border-brand-100 text-xs">
                        {{ shortTimeAgoUnix(item.last_seen) }}
                    </span>
                </div>
            </div>
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'
import { shortTimeAgoUnix } from '@/utils'
import moment from 'moment'

import { ref } from 'vue'

const index_loading = ref<number | null>(null)

function loadingAnimation(index: number) {
    index_loading.value = index
}
</script>
