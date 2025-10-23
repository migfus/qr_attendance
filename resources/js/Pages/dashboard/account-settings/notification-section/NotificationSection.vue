<template>
    <div class="grid grid-cols-6 gap-4">
        <AlertNotificationCard v-model="notifications.push" class="col-span-6 lg:col-span-3 xl:col-span-2" />
        <EmailNotificationCard v-model="notifications.email" class="col-span-6 lg:col-span-3 xl:col-span-2" />
        <SMSNotificationCard v-model="notifications.sms" class="col-span-6 lg:col-span-6 xl:col-span-2" />
    </div>
</template>

<script setup lang="ts">
import AlertNotificationCard from './PushNotificationCard.vue'
import EmailNotificationCard from './EmailNotificationCard.vue'
import SMSNotificationCard from './SMSNotificationCard.vue'

import { UserSettings } from '../accountSettingsInt'

import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { defaultRouterState } from '@/utils'

const $props = defineProps<{
    user_settings: UserSettings
}>()
const notifications = ref($props.user_settings.notification)

watch(notifications.value, () => {
    router.post(
        route('dashboard.account-settings.store'),
        { type: 'notification-config', notifications_config: notifications.value, route_id: 0 },
        defaultRouterState([])
    )
})
</script>
