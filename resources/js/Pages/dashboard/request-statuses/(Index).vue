<template>
    <div class="flex flex-col lg:grid lg:grid-cols-2 xl:grid-cols-3 gap-4 dark:bg-dark-001">
        <Unedited class="col-span-3" />
        <div class="flex gap-2 lg:col-span-2 xl:col-span-3 bg-white dark:bg-dark-002 p-4 px-6 sm:rounded-2xl">
            <AppButton :icon="is_recent ? BarsArrowUpIcon : BarsArrowDownIcon" @click="is_recent = !is_recent">
                {{ `${is_recent ? 'Set to Recent' : 'Set to Oldest'}` }}
            </AppButton>

            <AppButton :icon="collapse_all ? ChevronDownIcon : MinusIcon" @click="collapse_all = !collapse_all">
                {{ `${collapse_all ? 'Show All' : 'Collapse All'}` }}
            </AppButton>
        </div>

        <div v-for="(item, idx) in index_data" :key="idx">
            <RequestStatusCard :title="idx" :data="item" :icon="ClockIcon" @changeStatus="changeStatus" :request_status_types :collapse_all="collapse_all" />
        </div>

        <FormModal
            :icon="ClockIcon"
            :title="`To be ${form.status_type_name.toUpperCase()}`"
            description="Update request status"
            size="w-xl"
            v-model="open_modal"
        >
            <form @submit.prevent="updateRequest">
                <AppButton
                    @click="open_modal = false"
                    :icon="PencilIcon"
                    type="button"
                    :href="route('dashboard.account-settings.show', { account_setting: 4 })"
                >
                    Update Default Message
                </AppButton>

                <AppTextArea name="Message" v-model="form.message" />
                <AppToggle :name="`Email to ${form.email}`" v-model="form.send_to_email" />

                <div class="flex flex-col gap-2 mt-4 sm:flex-row justify-end">
                    <AppButton color="brand" :icon="PaperAirplaneIcon">Notify</AppButton>
                    <AppButton @click="open_modal = false" :icon="XMarkIcon" type="button">Cancel</AppButton>
                </div>
            </form>
        </FormModal>
    </div>
</template>

<script setup lang="ts">
import RequestStatusCard from '@/components/cards/RequestStatusCard.vue'
import { ClockIcon, PaperAirplaneIcon, PencilIcon, XMarkIcon, BarsArrowDownIcon, BarsArrowUpIcon, ChevronDownIcon, MinusIcon } from '@heroicons/vue/24/outline'
import FormModal from '@/components/modals/FormModal.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppToggle from '@/components/form/AppToggle.vue'
import AppButton from '@/components/form/AppButton.vue'

import { Pagination } from '@/globalTypes'
import { Employee, RequestStatusType } from '../arta-id/artaIdInt'

import { reactive, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useStorage } from '@vueuse/core'
import { defaultRouterState } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    index_data: {
        Unverified: Pagination<Employee>
        Processing: Pagination<Employee>
    }
    request_status_types: RequestStatusType[]
}>()

const open_modal = ref(false)
const form = reactive({
    id: '',
    status_type_id: '',
    status_type_name: '',
    message: '',
    email: '',
    send_to_email: true
})
const $page = usePage()
const is_recent = useStorage<boolean>('dashboard/request-status/is_recent', true, localStorage)
const collapse_all = ref(false)

function changeStatus(status_type_id: string, status_type_name: string, employee_id: string, email: string) {
    form.id = employee_id
    form.email = email
    form.status_type_id = status_type_id
    form.status_type_name = status_type_name
    form.send_to_email = true
    open_modal.value = true

    switch (status_type_name.toLowerCase()) {
        case 'unverified':
            form.message = $page.props.auth?.user_settings.request_status_messages.Unverified ?? ''
            break
        case 'processing':
            form.message = $page.props.auth?.user_settings.request_status_messages.Processing ?? ''
            break
        case 'completed':
            form.message = $page.props.auth?.user_settings.request_status_messages.Completed ?? ''
            break
        case 'claimed':
            form.message = $page.props.auth?.user_settings.request_status_messages.Claimed ?? ''
            break
        case 'rejected':
            form.message = $page.props.auth?.user_settings.request_status_messages.Rejected ?? ''
            break
        case 'removed':
            form.message = $page.props.auth?.user_settings.request_status_messages.Removed ?? ''
            break
    }
}

function updateRequest() {
    router.put(route('dashboard.request-statuses.update', { request_status: form.id }), {
        status_type_id: form.status_type_id,
        message: form.message,
        send_to_email: form.send_to_email
    })

    open_modal.value = false
}

watch(is_recent, () => {
    router.get(
        route('dashboard.request-statuses.index'),
        {
            sort: is_recent.value ? 'ASC' : 'DESC'
        },
        defaultRouterState()
    )
})
</script>
