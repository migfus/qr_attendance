<template>
    <BasicCard :icon="BellIcon" title="Push Notification" description="Notification from website.">
        <DataTransition class="flex flex-col gap-2">
            <div class="relative flex items-start bg-white dark:bg-dark-001 rounded-xl p-4">
                <div class="flex h-6 items-center">
                    <input
                        v-model="$model.registered"
                        :checked="$model.registered"
                        name="Push Posts"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-600"
                    />
                </div>
                <div class="ml-3 text-sm leading-6">
                    <label class="font-medium text-gray-900 dark:text-neutral-200">Newly Registered</label>
                    <p id="comments-description" class="text-gray-500 dark:text-neutral-300">Registered QR</p>
                </div>
            </div>
        </DataTransition>
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'

import { NotificationUserSettings } from '../accountSettingsInt'

import { BellIcon } from '@heroicons/vue/20/solid'

const $model = defineModel<NotificationUserSettings>({ required: true })
</script>
