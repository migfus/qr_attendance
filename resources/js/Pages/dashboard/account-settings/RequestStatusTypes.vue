<template>
    <div>
        <BasicCard title="Request Status Messages" description="Set request status types messages" :icon="ClockIcon">
            <form class="flex flex-col gap-4" @submit.prevent="updateRequestStatusMessages()">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col lg:grid grid-cols-2 xl:grid-cols-3 gap-4">
                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Unverified" name="Unverified" />
                        </div>
                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Processing" name="Processing" />
                        </div>

                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Completed" name="Completed" />
                        </div>

                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Claimed" name="Claimed" />
                        </div>
                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Rejected" name="Rejected" />
                        </div>
                        <div class="flex flex-col">
                            <AppTextArea v-model="form.Removed" name="Removed" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end">
                        <AppButton
                            color="brand"
                            @click="$inertia.post(route('dashboard.account-settings.request-status-types.store'), form)"
                            :icon="CircleStackIcon"
                            :disabled="!checkPermissions(['Account Settings/Update/All'], $page.props.auth?.permissions)"
                        >
                            Save
                        </AppButton>
                    </div>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { CircleStackIcon, ClockIcon } from '@heroicons/vue/24/outline'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppButton from '@/components/form/AppButton.vue'

import { UserSettings } from './accountSettingsInt'

import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { checkPermissions } from '@/utils'

const { user_settings } = defineProps<{
    user_settings: UserSettings
}>()

const form = reactive({
    Unverified: user_settings.request_status_messages.Unverified,
    Processing: user_settings.request_status_messages.Processing,
    Completed: user_settings.request_status_messages.Completed,
    Claimed: user_settings.request_status_messages.Claimed,
    Rejected: user_settings.request_status_messages.Rejected,
    Removed: user_settings.request_status_messages.Removed
})

function updateRequestStatusMessages() {
    router.post(route('dashboard.account-settings.store'), { type: 'request-status-messages', request_status_messages: form, route_id: 4 })
}
</script>
