<template>
    <div class="px-4 bg-white dark:bg-neutral-800 sm:rounded-2xl border border-brand-200 dark:border-neutral-600">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-600">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-brand-900 dark:text-neutral-200 sm:pl-0">Names</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-brand-900 dark:text-neutral-200 sm:pl-0">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-600 h-4">
                <tr v-for="item in data" :key="item.id" class="group">
                    <td
                        width="auto"
                        class="whitespace-nowrap py-4 pl-4 pr-3 text-xs text-brand-600 dark:text-neutral-300 sm:pl-0 truncate font-bold line-clamp-1 xl:w-[200px]"
                    >
                        {{ item.name }}
                    </td>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-xs text-brand-600 dark:text-neutral-300 sm:pl-0 truncate overflow-hidden font-bold">
                        <Link :href="`${content_link.replace('[%id%]', item.id.toString())}`" class="bg-brand-100 dark:bg-neutral-700 px-2 rounded-xl py-1">
                            {{ item.status }}
                            <ChevronRightIcon class="size-0 inline mb-1 group-hover:size-3 transition-all" />
                        </Link>
                    </td>
                </tr>

                <tr v-if="data.length > 5" class="my-4 pb-4 text-center text-sm">
                    <th colspan="2">
                        <Link :href="see_more_link">See More</Link>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { SimpleDataTable } from '@/globalTypes'
import { ChevronRightIcon } from '@heroicons/vue/24/outline'

defineProps<{
    data: SimpleDataTable[]
    content_link: string
    see_more_link: string
}>()
</script>
