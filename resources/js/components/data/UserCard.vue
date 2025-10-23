<template>
    <Link :href="route('dashboard.manage-users.edit', { manage_user: data.id })" @click="loading = true" class="block hover:bg-white bg-brand-50 pb-4">
        <div class="flex items-center px-4 py-4 sm:px-6">
            <div class="flex min-w-0 flex-1 items-center">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" :src="data.avatar_url" alt="" />
                </div>
                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                    <div>
                        <p class="truncate text-sm font-semibold text-brand-700">{{ data.name }}</p>
                        <p class="mt-2 flex items-center text-sm text-brand-500 font-semibold">
                            <span class="truncate">{{ data.email }}</span>
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div>
                            <p class="text-sm text-gray-900">
                                Joined on
                                {{ ' ' }}
                                <time :datetime="data.created_at" class="font-medium">{{ moment(data.created_at).format('MMM DD, YYYY') }}</time>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <ArrowPathIcon v-if="loading" class="animate-spin h-5 w-5 text-gray-400" aria-hidden="true" />
                <ChevronRightIcon v-else class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </div>
        </div>
    </Link>
</template>

<script setup lang="ts">
import { ChevronRightIcon, ArrowPathIcon } from '@heroicons/vue/20/solid'
import moment from 'moment'

import { User } from '@/globalTypes'
import { ref } from 'vue'

defineProps<{
    data: User
}>()

const loading = ref<boolean>(false)
</script>
