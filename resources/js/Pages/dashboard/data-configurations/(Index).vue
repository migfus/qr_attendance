<template>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <DataConfiguationTable v-for="(item, key) in index_data" :data="item" :key_name="key" />
    </div>
</template>

<script setup lang="ts">
import DataConfiguationTable from '@/components/tables/DataConfiguationTable.vue'

import { Pagination } from '@/globalTypes'
import { DataConfiguration } from './dataConfigurationsInt'

import search_filters from './searchFilters'
import { reactive } from 'vue'
import { defaultRouterState, searchQuery } from '@/utils'
import { router } from '@inertiajs/vue3'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    index_data: {
        [key: string]: Pagination<DataConfiguration>
    }
}>()

const query = reactive({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: search_filters[0]
})

function getData(page = 1) {
    router.get(route('dashboard.data-configurations.index'), searchQuery(query, page), defaultRouterState())
}
</script>
