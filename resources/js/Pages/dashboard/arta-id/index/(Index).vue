<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001 bg-brand-50">
        <Unedited />
        <SearchCard
            v-model="query"
            :index_data_id="index_data.data.map((row) => row.id)"
            :search_filters
            @search="getIndex"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
            @create="router.visit(route('arta-id.index'))"
            @print="prints()"
        />

        <ContentCard
            :links="content_card_links"
            :index_data="content_card_data"
            title="Employee"
            :select_all="false"
            :start_select="query.start_select"
            v-model="query.select_data"
        />

        <PaginationCard :data="index_data" @changePage="getIndex" />

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
                <AppToggle :name="` to ${form.email}`" v-model="form.send_to_email" />

                <div class="flex justify-end gap-2">
                    <AppButton @click="open_modal = false" :icon="XMarkIcon" type="button">Cancel</AppButton>
                    <AppButton color="brand" :icon="PaperAirplaneIcon">Update</AppButton>
                </div>
            </form>
        </FormModal>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import { Pagination, SearchQuery, ContentCardData } from '@/globalTypes'
import { RequestStatusType } from '../artaIdInt'
import { Employee } from '../artaIdInt'
import PaginationCard from '@/components/data/PaginationCard.vue'
import ContentCard from '@/components/cards/content-card/ContentCard.vue'
import FormModal from '@/components/modals/FormModal.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppToggle from '@/components/form/AppToggle.vue'

import { useRoute } from 'ziggy-js'
import { router, usePage } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import { initSearchQuery, searchQuery, employeeFullName, messengerStyleTime } from '@/utils'
import search_filters from './searchFilters'
import { ArrowRightIcon, ClockIcon, PaperAirplaneIcon, PencilIcon, PhotoIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import Unedited from '@/components/test/Unedited.vue'

const $route = useRoute()

const { index_data, request_status_types } = defineProps<{
    index_data: Pagination<Employee>
    request_status_types: RequestStatusType[]
}>()

const request_status_type_links = ref(
    request_status_types.map((item) => {
        return {
            callback: (employee_id: string, email: string) => {
                changeStatus(item.id.toString(), item.name, employee_id, email)
            },
            icon: ClockIcon,
            label: `Set to ${item.name}`
        }
    })
)

const query = reactive<SearchQuery>({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: route().queryParams.search_filter
        ? search_filters.find((item) => item.value == route().queryParams.search_filter) ?? search_filters[0]
        : search_filters[0],
    start_select: false,
    select_data: []
})

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

const content_card_links = [
    {
        href: '/dashboard/arta-id/[%id%]',
        icon: ArrowRightIcon,
        label: 'Details',
        callback: () => {}
    },
    {
        href: '/dashboard/arta-id/[%id%]/edit',
        icon: PhotoIcon,
        label: 'Edit',
        callback: () => {}
    },
    {
        icon: TrashIcon,
        label: 'Delete',
        callback: (id: string, email: string) => deleteEmployee(id)
    },

    ...request_status_type_links.value
]

const content_card_data = ref<ContentCardData[]>(
    index_data.data.map((item) => {
        return {
            image_url: item.files.length > 0 ? item.files[item.files.length - 2].thumbnail_url : '',
            title: employeeFullName(item), // or another appropriate property for the title
            sub_title: `${item.latest_request_status ? item.latest_request_status.request_status_type.name : 'N/A'} - ${item.department.name}`,
            ago: messengerStyleTime(item.created_at),
            sub_ago: item.email,
            id: item.id
        }
    })
)

function getIndex(page = 1) {
    router.get($route('dashboard.arta-id.index'), searchQuery(query, page))
}

function prints() {
    window.open(route('dashboard.arta_id.prints', searchQuery(query)), '_blank')
}

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
    router.put(route('dashboard.arta-id.update', { arta_id: form.id }), {
        type: 'request-status',
        request_status_type_id: form.status_type_id,
        content: form.message,
        notify_email: form.send_to_email
    })

    open_modal.value = false
}

function deleteEmployee(id: string) {
    if (query.select_data.length > 0) {
        router.delete(route('dashboard.arta-id.destroy', { arta_id: 0 }), {
            data: {
                ids: query.select_data
            }
        })
    } else {
        router.delete(route('dashboard.arta-id.destroy', { arta_id: id }))
    }
    getIndex(1)
}
</script>
