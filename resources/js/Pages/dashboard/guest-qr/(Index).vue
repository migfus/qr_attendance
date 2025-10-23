<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited />
        <SearchCard
            v-model="query"
            :index_data_id="index_data.data.map((row) => row.id)"
            @search="getIndex"
            :search_filters
            :no_create="!checkPermissions(['Guest QR/Create/All'], $page.props.auth?.permissions)"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
            @create="router.visit(route('dashboard.guest-qr.create'))"
        />

        <DataTransition class="flex lg:grid flex-col gap-4 items-center lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <QRCard
                v-for="(item, idx) in index_data.data"
                :item="item"
                :start_select="query.start_select"
                v-model="query.select_data"
                :style="{ transitionDelay: `${idx * 20}ms` }"
            />
        </DataTransition>

        <BasicTransition>
            <div
                v-if="index_data.data.length == 0"
                class="flex bg-white p-4 sm:rounded-2xl border border-brand-200 -mt-4 dark:bg-neutral-700 dark:border-neutral-700"
            >
                <p>No QR Available</p>
            </div>
        </BasicTransition>

        <PaginationCard :data="index_data" @changePage="getIndex" />
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import PaginationCard from '@/components/data/PaginationCard.vue'
import QRCard from '@/components/cards/QRCard.vue'

import { Pagination } from '@/globalTypes'
import { GuestQR } from './guestQrInt'

import { useRoute } from 'ziggy-js'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import { checkPermissions, defaultRouterState, initSearchQuery, searchQuery } from '@/utils'
import search_filters from './searchFilters'
import Unedited from '@/components/test/Unedited.vue'

const $route = useRoute()

defineProps<{
    index_data: Pagination<GuestQR>
}>()

const query = reactive({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: search_filters[0],
    start_select: false,
    select_data: []
})

function getIndex(page = 1) {
    router.get($route('dashboard.guest-qr.index'), searchQuery(query, page), defaultRouterState())
}
</script>
