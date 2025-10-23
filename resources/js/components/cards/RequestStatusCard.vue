<template>
    <BasicTransition>
        <div :class="['bg-white dark:bg-dark-002  p-6 sm:rounded-xl transition-all ']">
            <!-- NOTE: BASIC CARD HEADER -->
            <div>
                <div class="flex justify-between mb-4 items-start">
                    <h3 class="text-base font-semibold leading-7 text-brand-900 dark:text-brand-100 truncate">
                        <component :is="icon" class="text-sm text-brand-700 dark:text-brand-50 h-4 w-4 inline mr-3 mb-[3px] align-middle" />
                        <span>{{ title }} </span>
                    </h3>
                    <!-- <AppInput name="Search" v-model="search" placeholder="Search" noLabel /> -->
                    <div class="flex gap-2 items-center">
                        <div v-if="data.total > 0">
                            <span v-if="title == 'Unverified'" class="text-xs bg-red-500 px-2 py-0.5 text-neutral-50 rounded-full font-semibold">
                                {{ data.total }}
                            </span>
                            <span v-else-if="title == 'Processing'" class="text-xs bg-brand-500 px-2 py-0.5 text-neutral-50 rounded-full font-semibold">
                                {{ data.total }}
                            </span>
                        </div>
                        <AppButton v-if="data.total > 0" :icon="collapse ? ChevronDownIcon : MinusIcon" iconOnly @click="collapse = !collapse" />
                    </div>
                </div>

                <DataTransition v-if="!collapse">
                    <div
                        v-for="(item, idx) in data.data"
                        :key="item.id"
                        class="flex flex-col bg-brand-50 dark:bg-dark-001 my-2 p-4 rounded first:rounded-t-xl"
                        :style="loading_row_data ? { transitionDelay: `${idx * 15}ms` } : {}"
                    >
                        <div class="flex justify-between gap-2">
                            <img
                                v-if="item.files.length > 0"
                                :src="item.files[item.files.length - 2].thumbnail_url ?? ''"
                                class="flex size-28 rounded-md rounded-tl-xl object-cover"
                            />
                            <div class="flex flex-grow flex-col gap-0.5">
                                <div class="flex justify-between">
                                    <div class="font-semibold text-brand-700 dark:text-neutral-300 text-sm line-clamp-1">
                                        <p>{{ messengerStyleTime(item.created_at) }}</p>
                                    </div>

                                    <Menu as="div" class="relative inline-block text-left">
                                        <div>
                                            <MenuButton class="cursor-pointer">
                                                <EllipsisVerticalIcon class="size-5 text-brand-700 dark:text-neutral-300 my-auto" aria-hidden="true" />
                                            </MenuButton>
                                        </div>

                                        <BasicTransition>
                                            <MenuItems
                                                class="p-2 absolute right-0 z-10 w-52 origin-top-right rounded-xl bg-white dark:bg-dark-002 shadow-lg ring-1 ring-brand-200 dark:ring-neutral-600 ring-opacity-5 focus:outline-hidden"
                                            >
                                                <MenuItem v-slot="{ active }" class="rounded-md rounded-t-xl">
                                                    <Link
                                                        :href="route('dashboard.arta-id.show', { arta_id: item.id })"
                                                        :class="[
                                                            active
                                                                ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                : 'text-brand-700 dark:text-neutral-300',
                                                            'block pl-3 py-2 text-sm font-semibold cursor-pointer'
                                                        ]"
                                                    >
                                                        <ArrowRightIcon
                                                            :class="[
                                                                active
                                                                    ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                    : 'text-brand-700 dark:text-neutral-300',
                                                                'h-4 w-5 text-brand-400 inline mr-2'
                                                            ]"
                                                        />
                                                        Details
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem
                                                    v-if="checkPermissions(['ARTA ID/Update/All'], $page.props.auth?.permissions)"
                                                    v-slot="{ active }"
                                                    class="rounded-md"
                                                >
                                                    <Link
                                                        :href="route('dashboard.arta-id.edit', { arta_id: item.id })"
                                                        :class="[
                                                            active
                                                                ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                : 'text-brand-700 dark:text-neutral-300',
                                                            'block pl-3 py-2 text-sm font-semibold cursor-pointer'
                                                        ]"
                                                    >
                                                        <PhotoIcon
                                                            :class="[
                                                                active
                                                                    ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                    : 'text-brand-700 dark:text-neutral-300',
                                                                'h-4 w-5 text-brand-400 inline mr-2'
                                                            ]"
                                                        />
                                                        Edit
                                                    </Link>
                                                </MenuItem>

                                                <MenuItem
                                                    v-if="checkPermissions(['ARTA ID/Update/All'], $page.props.auth?.permissions)"
                                                    v-for="status_type in request_status_types"
                                                    v-slot="{ active }"
                                                    class="rounded-md last:rounded-b-xl"
                                                >
                                                    <div
                                                        @click="() => $emit('changeStatus', status_type.id, status_type.name, item.id, item.email)"
                                                        :class="[
                                                            active
                                                                ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                : 'text-brand-700 dark:text-neutral-300',
                                                            'block pl-3 py-2 text-sm font-semibold cursor-pointer'
                                                        ]"
                                                    >
                                                        <ClockIcon
                                                            :class="[
                                                                active
                                                                    ? 'bg-brand-50 dark:bg-dark-003 text-brand-600 dark:text-neutral-300'
                                                                    : 'text-brand-700 dark:text-neutral-300',
                                                                'h-4 w-5 text-brand-400 inline mr-2'
                                                            ]"
                                                        />
                                                        Set to {{ status_type.name }}
                                                    </div>
                                                </MenuItem>
                                            </MenuItems>
                                        </BasicTransition>
                                    </Menu>
                                </div>
                                <p class="font-semibold text-brand-700 dark:text-neutral-300 text-sm line-clamp-1">{{ employeeFullName(item) }}</p>
                                <p class="font-semibold text-brand-600 dark:text-neutral-300 text-sm line-clamp-1">{{ item.position.name }}</p>
                                <p class="font-semibold text-brand-600 dark:text-neutral-300 text-sm line-clamp-1">{{ item.department.name }}</p>
                                <p class="font-semibold text-brand-500 dark:text-neutral-300 text-xs line-clamp-1">{{ item.employee_status.name }}</p>
                            </div>
                        </div>
                        <p
                            v-html="youToName(item.latest_request_status.content, employeeFullName(item))"
                            class="font-semibold text-brand-600 text-sm line-clamp-6 bg-white dark:bg-dark-002 dark:text-neutral-300 rounded-md rounded-b-xl p-2 pt-2 mt-2"
                        />
                    </div>
                </DataTransition>

                <Link
                    v-if="data.total > 10 && !collapse"
                    :href="`${route('dashboard.arta-id.index')}?search_filter=${title}`"
                    class="flex flex-col bg-white dark:bg-dark-001 p-2 rounded rounded-b-xl items-center font-semibold text-brand-500 dark:text-neutral-300 text-sm mt-2"
                >
                    More Requests...
                </Link>

                <div
                    v-if="data.data.length == 0"
                    class="flex flex-col bg-white dark:bg-dark-001 p-2 rounded-xl items-center font-semibold text-brand-500 dark:text-neutral-300 text-sm"
                >
                    No Requests
                </div>

                <AppButton v-else-if="collapse" class="w-full" @click="collapse = false"> Show {{ data.total }} Requests </AppButton>
            </div>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { EllipsisVerticalIcon, PhotoIcon, ArrowRightIcon, ClockIcon, ChevronDownIcon, MinusIcon } from '@heroicons/vue/24/outline'
import DataTransition from '../transitions/DataTransition.vue'
import AppButton from '../form/AppButton.vue'

import { FunctionalComponent, ref, watch } from 'vue'
import { Employee, Pagination, RequestStatusType } from '@/globalTypes'
import { checkPermissions, employeeFullName, youToName, messengerStyleTime } from '@/utils'

const { collapse_all, data } = defineProps<{
    icon: FunctionalComponent
    title: string
    data: Pagination<Employee>
    request_status_types: RequestStatusType[]
    collapse_all: boolean
}>()

const $emit = defineEmits(['changeStatus'])

const collapse = ref(false)

const loading_row_data = ref(true)
setTimeout(() => {
    loading_row_data.value = false
}, data.data.length * 15)

watch(
    () => collapse_all,
    (newValue) => {
        collapse.value = newValue
    }
)
</script>
