<template>
    <div class="px-4 sm:px-6 lg:px-8 bg-white dark:bg-dark-002 p-8 sm:rounded-2xl group">
        <div class="flex justify-between items-start">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-200">{{ key_name }}</h1>
            </div>
            <div class="flex">
                <AppButton
                    :icon="PencilIcon"
                    size="sm"
                    color="brand"
                    :href="route('dashboard.data-configurations.show', { data_configuration: key_name })"
                    class="group-hover:opacity-100 opacity-75"
                    >Edit</AppButton
                >
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-dark-003">
                        <thead>
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-brand-900 dark:text-neutral-200 sm:pl-0">
                                    Data Value
                                </th>
                                <th scope="col" width="150" class="py-3.5 pl-4 pr-3 text-sm font-semibold text-gray-900 dark:text-neutral-200 sm:pl-0 text-end">
                                    Used by users
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-600 h-4">
                            <tr v-for="(item, idx) in data.data" :key="item.name" :style="{ transitionDelay: `${idx * 20}ms` }">
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-normal text-brand-700 dark:text-white sm:pl-0 truncate overflow-hidden"
                                >
                                    {{ item.name }}
                                </td>
                                <td class="text-sm font-normal text-brand-700 dark:text-gray-200 text-end pr-2">{{ item.count }}</td>
                            </tr>

                            <tr v-if="data.total > 5" class="my-4 pb-4 text-center" colspan="2">
                                <Link
                                    :href="route('dashboard.data-configurations.show', { data_configuration: key_name })"
                                    class="pl-4 pr-3 text-sm font-semibold text-brand-900 dark:text-neutral-200 sm:pl-0 text-left mt-4"
                                >
                                    <p class="mb-0 ml-3 sm:ml-0 mt-4">See More ({{ data.total - 5 }})</p>
                                </Link>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { PencilIcon } from '@heroicons/vue/24/outline'
import AppButton from '../form/AppButton.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { Pagination, DataConfiguration } from '@/globalTypes'

defineProps<{
    data: Pagination<DataConfiguration>
    key_name: string | number
}>()
</script>
