<template>
    <div>
        <slot></slot>
        <div class="mb-4 sm:rounded-2xl bg-white dark:bg-dark-002">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                <select
                    v-model="$model"
                    id="tabs"
                    name="tabs"
                    class="cursor-pointer dark:text-light-002 h-10 block w-full border-gray-300 dark:bg-dark-002 dark:border-neutral-700 focus:border-brand-500 focus:ring-brand-500 accent-brand-700"
                >
                    <option v-for="(tab, index) in data" :key="tab.display_name" :value="index" class="cursor-pointer">{{ tab.display_name }}</option>
                </select>
            </div>
            <div class="hidden sm:block px-4">
                <div class="border-b border-brand-100 dark:border-neutral-600">
                    <nav>
                        <DataTransition class="-mb-px flex gap-4" aria-label="Tabs">
                            <TabSectionContent
                                v-for="(tab, index) in data"
                                :key="tab.display_name"
                                :display_name="tab.display_name"
                                :index="index"
                                :icon="tab.hero_icon.content"
                                :disableLoading
                                v-model="$model"
                            />
                        </DataTransition>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import DataTransition from '@/components/transitions/DataTransition.vue'
import TabSectionContent from './TabSectionContent.vue'

import { Tab } from '@/globalTypes'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps<{
    data: Tab[]
    disableLoading?: boolean
    selected: number
}>()

const $model = ref(Number(route().params.account_setting))

watch($model, () => {
    router.visit(route('dashboard.account-settings.show', { account_setting: $model.value, route_id: 0 }), { preserveState: true })
})
</script>
