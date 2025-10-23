<template>
    <div class="flex flex-col dark:bg-dark-001 gap-4">
        <Unedited />
        <div class="flex flex-col lg:grid grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="flex flex-col xl:col-span-2 gap-4 order-2 lg:order-1">
                <div class="flex justify-end bg-white dark:bg-dark-002 p-4 sm:rounded-2xl gap-2 flex-wrap">
                    <AppButton :icon="ArrowLeftIcon" :href="route('dashboard.request-statuses.index')">Request Status</AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('dashboard.arta-id.index')">Arta ID</AppButton>
                    <!-- <AppButton :icon="PrinterIcon">Print</AppButton> -->
                </div>

                <RequestStatusForm
                    :employee_email="show_data.email"
                    :request_status_types
                    :latest_request_status_id="
                        show_data.request_statuses.length > 0 ? show_data.request_statuses[0].request_status_type.id : Number(request_status_types[0].id)
                    "
                    :employee_id="show_data.id"
                />
                <RequestStatusCard :requests_statuses="show_data.request_statuses" :name="employeeFullName(show_data)" />
            </div>

            <div class="order-1 lg:order-2 flex flex-col gap-4">
                <FilesCard :files="show_data.files" :employee_id="show_data.id" />
                <SummaryCard :show_data />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import SummaryCard from './SummaryCard.vue'
import RequestStatusCard from './RequestStatusCard.vue'
import RequestStatusForm from './RequestStatusForm.vue'
import AppButton from '@/components/form/AppButton.vue'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
import FilesCard from './FilesCard.vue'

import { Select } from '@/globalTypes'
import { Employee } from '../artaIdInt'

import { employeeFullName } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    show_data: Employee
    request_status_types: Select[]
}>()
</script>
