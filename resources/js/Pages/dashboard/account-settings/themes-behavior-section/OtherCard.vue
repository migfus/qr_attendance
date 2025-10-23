<template>
    <div>
        <BasicCard title="Other Configuration" :icon="PaintBrushIcon" description="Miscelanious Configuration.">
            <SwitchGroup as="div" class="flex items-center justify-between bg-white dark:bg-dark-001 p-4 rounded-xl">
                <span class="flex flex-grow flex-col">
                    <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200" passive>Show Description</SwitchLabel>
                    <SwitchDescription as="span" class="text-sm text-gray-500 dark:text-neutral-300">
                        Show description of every card. It helps to understand the functionality.
                    </SwitchDescription>
                </span>
                <Switch
                    v-model="show_description_"
                    :class="[
                        show_description_ ? 'bg-brand-600' : 'bg-gray-200 dark:bg-neutral-600',
                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus:ring-2 focus:ring-brand-600 focus:ring-offset-2'
                    ]"
                >
                    <span
                        aria-hidden="true"
                        :class="[
                            show_description_ ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white  shadow ring-0 transition duration-200 ease-in-out'
                        ]"
                    />
                </Switch>
            </SwitchGroup>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { PaintBrushIcon } from '@heroicons/vue/20/solid'
import { Switch, SwitchDescription, SwitchGroup, SwitchLabel } from '@headlessui/vue'

import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { defaultRouterState } from '@/utils'

const $props = defineProps<{
    card: {
        show_description: boolean
    }
}>()

const show_description_ = ref($props.card.show_description)

watch(show_description_, () => {
    router.post(
        route('dashboard.account-settings.store'),
        { type: 'card-show-description', show_description: show_description_.value, route_id: 3 },
        defaultRouterState([])
    )
})
</script>
