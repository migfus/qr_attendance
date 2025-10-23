<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited />

        <SearchCard
            v-model="query"
            :search_filters
            :no_print="true"
            :index_data_id="index_data.data.map((row) => row.id.toString())"
            :no_create="!checkPermissions(['Department/Create/All'], $page.props.auth?.permissions)"
            @create="router.visit(route('dashboard.departments.create'))"
            @search="getIndex(1)"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
        />

        <ContentCard
            :links="content_card_links"
            :index_data="content_card_data"
            title="Employee"
            :start_select="query.start_select"
            v-model="query.select_data"
        />

        <PaginationCard :data="index_data" @changePage="getIndex" />
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import ContentCard from '@/components/cards/content-card/ContentCard.vue'
import PaginationCard from '@/components/data/PaginationCard.vue'

import { ContentCardData, Pagination, SearchQuery } from '@/globalTypes'
import { Department } from './departmentInt'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { searchQuery, initSearchQuery, checkPermissions } from '@/utils'
import search_filters from './searchFilters'
import { ArrowRightIcon } from '@heroicons/vue/24/outline'
import Unedited from '@/components/test/Unedited.vue'

const { index_data } = defineProps<{
    index_data: Pagination<Department>
}>()

const query = reactive<SearchQuery>({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: search_filters[0],
    start_select: false,
    select_data: []
})

const content_card_data = ref<ContentCardData[]>(
    index_data.data.map((item) => {
        return {
            image_url: item.image_url ?? '',
            title: item.name, // or another appropriate property for the title
            sub_title: item.short_name,
            ago: item.email ?? '',
            sub_ago: item.phone_number ? String(item.phone_number) : '',
            id: String(item.id)
        }
    })
)

const content_card_links = [
    {
        href: '/dashboard/departments/[%id%]/edit',
        icon: ArrowRightIcon,
        label: 'Details',
        callback: () => {}
    }
]

function getIndex(page = 1) {
    router.get(route('dashboard.departments.index'), searchQuery(query, page))
    window.scrollTo({ top: 0, left: 0, behavior: 'smooth' })
}
</script>
