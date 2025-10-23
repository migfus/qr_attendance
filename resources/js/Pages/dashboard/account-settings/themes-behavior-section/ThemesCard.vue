<template>
    <BasicCard title="Themes" :icon="PaintBrushIcon" description="Themes available in the system">
        <label for="">Disabled</label>
        <!-- <RadioGroup v-model="selected_mode">
            <div class="mt-4 grid grid-cols-2 gap-2">
                <RadioGroupOption as="template" v-for="row in modes" :key="row.name" :value="row.name" v-slot="{ active, checked }">
                    <div
                        :class="[
                            active ? 'border-brand-600 ring-2 ring-brand-600' : 'border-gray-300',
                            row.bg,
                            'relative flex cursor-pointer rounded-xl p-4 focus:outline-hidden border border-brand-200 hover:shadow-md transition-all'
                        ]"
                    >
                        <span class="flex flex-1">
                            <span class="flex flex-col">
                                <RadioGroupLabel as="span" :class="[row.text, 'block text-sm font-medium ']">
                                    <component :is="row.icon" class="h-4 w-4 inline" />
                                    {{ row.name }}
                                </RadioGroupLabel>
                            </span>
                        </span>
                        <CheckCircleIcon :class="[!checked ? 'invisible' : '', row.text, 'h-5 w-5']" aria-hidden="true" />
                        <span
                            :class="[
                                active ? 'border' : 'border-2',
                                checked ? row.outline : 'border-transparent',
                                'pointer-events-none absolute -inset-px rounded-xl'
                            ]"
                            aria-hidden="true"
                        />
                    </div>
                </RadioGroupOption>
            </div>
        </RadioGroup> -->
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { PaintBrushIcon, CheckCircleIcon, MoonIcon, SunIcon, DevicePhoneMobileIcon } from '@heroicons/vue/20/solid'
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'

import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const $props = defineProps<{
    themes: 'Light' | 'Dark'
}>()

const modes = [
    {
        name: 'Light',
        description: 'Last message sent an hour ago',
        icon: MoonIcon,
        bg: 'bg-white',
        text: 'text-brand-900',
        outline: 'border-brand-600'
    },
    {
        name: 'Dark',
        description: 'Last message sent 2 weeks ago',
        icon: SunIcon,
        bg: 'bg-neutral-800 ',
        text: 'text-brand-50',
        outline: 'border-neutral-700'
    }
    // {
    //     name: 'Auto',
    //     description: 'Last message sent 2 weeks ago',
    //     icon: DevicePhoneMobileIcon,
    //     bg: 'bg-gradient-to-bl from-brand-50 to-neutral-800',
    //     text: 'text-brand-50',
    //     outline: 'border-gradient-to-bl from-neutral-800 to-brand-50'
    // }
]

const selected_mode = ref($props.themes)

watch(selected_mode, () => {
    router.post(route('dashboard.account-settings.store'), { type: 'themes', mode: selected_mode.value, route_id: 3 }, { preserveState: false })
})
</script>
