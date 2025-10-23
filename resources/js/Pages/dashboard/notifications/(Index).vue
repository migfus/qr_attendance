<template>
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <div class="flex flex-col gap-4 xl:col-span-3">
            <SearchCard
                v-model="query"
                @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
                :search_filters
                :no_create="true"
                :no_print="true"
                @search="getIndexData"
                :index_data_id="index_data.data.map((row) => row.id)"
            />

            <ContentCard
                :links="content_card_links"
                :index_data="content_card_data"
                title="Employee"
                :start_select="query.start_select"
                v-model="query.select_data"
            />

            <PaginationCard :data="index_data" @changePage="getIndexData" />
        </div>

        <div class="flex flex-col">
            <BasicCard title="Actions" description="Additional actions" :icon="WindowIcon">
                <div class="flex gap-2 flex-col">
                    <AppButton
                        @click="markRead()"
                        :icon="CheckCircleIcon"
                        :disabled="!checkPermissions(['Notifications/Update/All'], $page.props.auth?.permissions)"
                    >
                        Mark Read
                    </AppButton>
                    <AppButton
                        @click="markUnread()"
                        :icon="ArrowLeftIcon"
                        :disabled="!checkPermissions(['Notifications/Update/All'], $page.props.auth?.permissions)"
                    >
                        Mark Unread
                    </AppButton>
                    <AppButton
                        @click="open_modal = true"
                        :icon="ArrowPathIcon"
                        color="danger"
                        :disabled="!checkPermissions(['Notifications/Delete/All'], $page.props.auth?.permissions)"
                    >
                        Clear
                    </AppButton>
                </div>
            </BasicCard>
        </div>

        <RemovalPrompt v-model="open_modal" title="Clear All Notifications?" confirmMessage="Yes, Clear all" :confirmIcon="TrashIcon" @confirm="clear()">
            <li>All notifications will be deleted</li>
            <li>You cannot undo this action.</li>
        </RemovalPrompt>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import { ArrowLeftIcon, ArrowPathIcon, ArrowRightIcon, CheckCircleIcon, TrashIcon, WindowIcon } from '@heroicons/vue/24/outline'
import ContentCard from '@/components/cards/content-card/ContentCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import BasicCard from '@/components/cards/BasicCard.vue'
import PaginationCard from '@/components/data/PaginationCard.vue'
import RemovalPrompt from '@/components/modals/RemovalPrompt.vue'

import { Pagination, SearchQuery, ContentCardData } from '@/globalTypes'
import { Notification } from './notificationInt'

import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import search_filters from './searchFilters'
import { checkPermissions, initSearchQuery, messengerStyleTime, searchQuery } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const { index_data } = defineProps<{
    index_data: Pagination<Notification>
}>()

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
const content_card_data = ref<ContentCardData[]>(
    index_data.data.map((item) => {
        return {
            image_url: 'https://cdn-icons-png.flaticon.com/512/1827/1827312.png',
            title: typeof item.data === 'string' ? item.data : item.data?.message ?? '',
            sub_title: messengerStyleTime(item.created_at),
            ago: item.read_at ? `Read at ${messengerStyleTime(item.read_at)}` : '',
            sub_ago: '',
            id: String(item.id)
        }
    })
)

const open_modal = ref(false)
const content_card_links = [
    {
        href: '/dashboard/departments/[%id%]/edit',
        icon: ArrowRightIcon,
        label: 'Details',
        callback: () => {}
    }
]

function getIndexData(page = 1) {
    router.get(route('dashboard.notifications.index'), searchQuery(query, page))
}

function markRead() {
    router.post(route('dashboard.notifications.store'), {
        ids: index_data.data.map((item) => item.id),
        type: 'mark-read'
    })
}

function markUnread() {
    router.post(route('dashboard.notifications.store'), {
        ids: index_data.data.map((item) => item.id),
        type: 'mark-unread'
    })
}

function clear() {
    router.post(route('dashboard.notifications.store'), {
        ids: index_data.data.map((item) => item.id),
        type: 'clear'
    })
}
</script>
