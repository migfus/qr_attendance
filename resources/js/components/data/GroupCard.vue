<template>
    <Link :href="route('dashboard.manage-groups.edit', { manage_group: data.id })" @click="loading = true" class="block hover:bg-gray-50 bg-white rounded-xl shadow">
        <div class="flex justify-between p-4">
            <img class="h-12 w-12 rounded-xl flex-none flex-0 mr-2" :src="data.avatar" alt="" />
            <div class="flex flex-col truncate flex-grow">
                <div class="flex justify-between">
                    <p class="truncate text-sm font-semibold text-brand-700 p-0 m-0 flex-grow">{{ data.name }}</p>
                    <p class="truncate text-sm font-semibold text-brand-700 p-0 m-0">
                        <ArrowPathIcon v-if="loading" class="h-5 w-4 text-gray-400 animate-spin" aria-hidden="true" />
                        <ChevronRightIcon v-else class="h-5 w-4 text-gray-400" aria-hidden="true" />
                    </p>
                </div>

                <div class="flex justify-between">
                    <p class="flex items-center text-sm text-brand-500 font-semibold p-0 m-0 flex-grow">
                        {{ data.description }}
                    </p>
                    <p class="truncate text-sm font-semibold text-brand-700 p-0 m-0">
                        <GlobeAsiaAustraliaIcon v-if="data.is_visible" class="h-4 w-4 text-gray-400" aria-hidden="true" />
                        <LockClosedIcon v-else class="h-4 w-4 text-gray-400" aria-hidden="true" />
                    </p>
                </div>
            </div>
        </div>
    </Link>
</template>

<script setup lang="ts">
import { ChevronRightIcon, GlobeAsiaAustraliaIcon, LockClosedIcon, ArrowPathIcon } from "@heroicons/vue/20/solid"

import { ref } from "vue"
import { Group } from "@/globalTypes"

defineProps<{
    data: Group
}>()

const loading = ref<boolean>(false)
</script>
