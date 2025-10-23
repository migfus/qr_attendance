<template>
    <div class="flex flex-col lg:grid grid-cols-3 gap-4 my-4">
        <div class="flex flex-col gap-4 col-span-2">
            <ClosingStatementCard />
            <RequestStatusCard :requests_statuses="show_data.request_statuses" />
        </div>
        <div class="flex flex-col gap-4">
            <!-- <QueueStatusCard /> -->
            <SummaryCard :show_data />
        </div>

        <ConfirmationPrompt
            v-model="show_modal"
            confirmMessage="Do not show again"
            :confirmIcon="CheckIcon"
            title="Closing Statement"
            id="closing-statement"
            :hideDoNotShowAgainButton="true"
            @confirm="show_modal = false"
        >
            <div class="flex flex-col gap-2 font-semibold">
                <p>Thank you for your submitting the request, your ID is being processed.</p>
                <p>
                    A confirmation has been sent to your email, please check your inbox for further details. If you have any questions or need assistance, feel
                    free to contact our email.
                </p>
                <a href="mailto:obm@cmu.edu.ph">obm@cmu.edu.ph</a>
            </div>
        </ConfirmationPrompt>
    </div>
</template>

<script setup lang="ts">
import { CheckIcon } from '@heroicons/vue/24/outline'
import RequestStatusCard from './RequestStatusCard.vue'
import SummaryCard from './SummaryCard.vue'
import ConfirmationPrompt from '@/components/modals/ConfirmationPrompt.vue'
import ClosingStatementCard from './ClosingStatementCard.vue'

import { Employee } from '@/Pages/dashboard/arta-id/artaIdInt'

import { useLocalStorage } from '@vueuse/core'

const { show_data } = defineProps<{
    show_data: Employee
}>()

const show_modal = useLocalStorage(show_data.id, true)
</script>
