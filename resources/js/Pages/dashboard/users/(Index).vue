<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited />
        <SearchCard
            v-model="query"
            @search="getIndex"
            :search_filters
            :index_data_id="index_data.data.map((row) => row.id)"
            :no_create="!checkPermissions(['Users/Create/All'], $page.props.auth?.permissions)"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
            @create="router.visit(route('dashboard.users.create'))"
            @print="prints()"
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
import { ContentCardData, Pagination, SearchQuery, User } from '@/globalTypes'
import PaginationCard from '@/components/data/PaginationCard.vue'
import ContentCard from '@/components/cards/content-card/ContentCard.vue'

import { useRoute } from 'ziggy-js'
import { router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import { checkPermissions, initSearchQuery, searchQuery } from '@/utils'
import search_filters from './searchFilters'
import { ArrowRightIcon } from '@heroicons/vue/24/outline'
import Unedited from '@/components/test/Unedited.vue'

const $route = useRoute()

const { index_data } = defineProps<{
    index_data: Pagination<User>
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
            image_url: item.avatar_url,
            title: item.name, // or another appropriate property for the title
            sub_title: item.roles[0].display_name,
            ago: item.email,
            sub_ago: item.department.name,
            id: item.id
        }
    })
)

const content_card_links = [
    {
        href: '/dashboard/users/[%id%]/edit',
        icon: ArrowRightIcon,
        label: 'Details',
        callback: () => {}
    }
]

function getIndex(page = 1) {
    router.get($route('dashboard.users.index'), searchQuery(query, page))
}

function prints() {
    window.open(route('dashboard.users.prints', searchQuery(query)), '_blank')
}
</script>
