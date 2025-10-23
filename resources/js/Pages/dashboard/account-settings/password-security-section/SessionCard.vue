<template>
    <div>
        <BasicCard title="Sessions" description="Manage Login Sessions" :icon="ArrowRightEndOnRectangleIcon">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col">
                    <ul role="list" class="divide-y divide-brand-100 overflow-hidden bg-white dark:bg-dark-001 shadow-sm ring-1 ring-gray-900/5 rounded-2xl">
                        <li
                            v-for="item in sessions"
                            :key="item.id"
                            class="relative flex flex-col gap-4 py-4 px-4 hover:bg-gray-50 dark:hover:bg-dark-003 sm:px-6 group"
                        >
                            <div class="flex justify-between">
                                <div class="flex min-w-0 gap-x-2">
                                    <div class="min-w-0 flex-auto dark:text-neutral-200">
                                        {{ item.ip_address }}
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-x-4 relative">
                                    <div class="opacity-100 group-hover:opacity-0 transition-all text-sm text-brand-500 dark:text-neutral-300">
                                        {{ shortTimeAgoUnix(item.last_activity) }}
                                    </div>
                                    <div class="opacity-0 group-hover:opacity-100 absolute transition-all right-0 w-36 flex justify-end">
                                        <AppButton size="sm" color="danger" @click="removeDevice(item.id)" :icon="TrashIcon">Remove Device</AppButton>
                                    </div>
                                </div>
                            </div>

                            <div class="flex">
                                <div class="text-xs dark:text-neutral-300">
                                    {{ item.user_agent }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row justify-end">
                    <AppButton color="danger" :icon="ArrowRightStartOnRectangleIcon" @click="show_modal = true">Logout All Devices</AppButton>
                </div>
            </div>
        </BasicCard>

        <RemovalPrompt
            title="Remove All Devices?"
            :icon="ArrowRightStartOnRectangleIcon"
            confirmMessage="Remove All Devices"
            :confirmIcon="ArrowRightStartOnRectangleIcon"
            v-model="show_modal"
            @confirm="removeAllDevice()"
        >
            <li>You will be forced to lougout</li>
            <li>Other devices will no longer access.</li>
        </RemovalPrompt>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import RemovalPrompt from '@/components/modals/RemovalPrompt.vue'

import { UserSession } from '../accountSettingsInt'

import { ArrowRightEndOnRectangleIcon, ArrowRightStartOnRectangleIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { shortTimeAgoUnix } from '@/utils'

defineProps<{
    sessions: UserSession[]
}>()

const show_modal = ref(false)

function removeAllDevice() {
    router.post(route('dashboard.account-settings.store'), { type: 'remove-devices', route_id: 2 })
}

function removeDevice(id: string) {
    router.post(route('dashboard.account-settings.store'), { type: 'remove-device', id, route_id: 2 })
}
</script>
