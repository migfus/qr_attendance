<template>
    <div>
        <!-- NOTE: TITLE -->
        <label class="ml-2 font-semibold text-brand-700 dark:text-neutral-200">{{ title }}</label>
        <!-- NOTE: CONTENT -->
        <DataTransition class="rounded-2xl">
            <Link
                v-for="(item, index) in data"
                :key="item.name"
                :href="item.href"
                @click="loadingAnimation(index)"
                :class="[
                    item.components.some((row: string) => row == $page.component) ?
                    'bg-brand-50 dark:bg-brand-800 text-brand-900 dark:text-brand-200 ' :
                    'text-brand-700 dark:text-neutral-300 hover:bg-brand-50 dark:hover:bg-dark-003 hover:text-brand-800 hover:shadow hover:dark:text-neutral-200',
                    'group flex flex-col px-2 py-2 text-sm font-medium rounded-md mb-1 pl-3 first:rounded-t-xl last:rounded-b-xl '
                ]"
            >
                <div class="flex justify-between items-center">
                    <div class="flex justify-start truncate items-center">
                        <!-- NOTE IF LOADING -->
                        <ArrowPathIcon v-if="index == index_loading" :class="['mr-3 h-5 w-5 animate-spin ']" aria-hidden="true" />
                        <!-- NOTE IF ACTIVE -->
                        <component
                            v-else-if="item.components.some((row: string) => row === $page.component)"
                            :is="item.icon"
                            class="text-brand-700 dark:text-neutral-300 mr-3 h-5 w-5"
                            aria-hidden="true"
                        />
                        <!-- NOTE IF DEFAULT -->
                        <component
                            v-else
                            :is="item.icon"
                            :class="['text-brand-700 dark:text-neutral-300 group-hover:text-brand-800 dark:group-hover:text-neutral-200', 'mr-3 h-5 w-5']"
                            aria-hidden="true"
                        />
                        <div class="truncate my-2 md:my-0">{{ item.name }}</div>
                    </div>
                    <div class="flex gap-1">
                        <div
                            v-for="row in item.count.filter((it) => it.number > 0)"
                            class="px-1.5 py-0.5 rounded-2xl text-red-50 shadow my-auto text-xs"
                            :style="`background-color: ${row.color}`"
                        >
                            <span>
                                {{ maxDigit(row.number) }}
                            </span>
                        </div>
                    </div>
                </div>
            </Link>
        </DataTransition>
    </div>
</template>

<script setup lang="ts">
import { ArrowPathIcon } from '@heroicons/vue/24/outline'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { TopNavigation } from '@/globalTypes'
import { maxDigit } from '@/utils'

defineProps<{
    title: string
    data: TopNavigation[]
}>()
const $model = defineModel<boolean>()

const index_loading = ref<number | null>(null)

router.on('finish', () => {
    index_loading.value = null
    $model.value = false
})

function loadingAnimation(index: number) {
    index_loading.value = index
}
</script>
