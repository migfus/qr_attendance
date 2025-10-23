<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <SearchCard
            v-model="query"
            :search_filters
            :no_print="true"
            :index_data_id="index_data.map((item) => item.id)"
            @search="getIndex"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
            @create="router.visit(route('dashboard.roles-permissions.create'))"
            :no_create="!checkPermissions(['Roles & Permissions/Create/All'], $page.props.auth?.permissions)"
        />
        <div class="flex flex-col gap-4">
            <RoleCard v-for="item in index_data" :role="item" :key="item.id" :permission_modules :permission_types :roles="index_data" />
        </div>
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import { KeyIcon, MagnifyingGlassIcon, UsersIcon } from '@heroicons/vue/24/outline'
import RoleCard from './RoleCard.vue'

import { SearchFilter, SearchQuery } from '@/globalTypes'
import { PermissionModule, PermissionType, Role } from './rolesPermissionsInt'

import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { checkPermissions, defaultRouterState, initSearchQuery, searchQuery } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    index_data: Role[]
    permission_modules: PermissionModule[]
    permission_types: PermissionType[]
}>()

const search_filters: SearchFilter[] = [
    {
        display_name: 'Roles',
        value: '',
        icon: MagnifyingGlassIcon
    },
    {
        display_name: 'Users',
        value: 'Users',
        icon: UsersIcon
    },
    {
        display_name: 'Permissions',
        value: 'Permissions',
        icon: KeyIcon
    }
]

const query = reactive<SearchQuery>({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: search_filters[0],
    start_select: false,
    select_data: ['']
})

function getIndex(page = 1) {
    router.get(route('dashboard.roles-permissions.index'), searchQuery(query, page), defaultRouterState())
}
</script>
