<template>
    <div>
        <BasicCard title="Search" :icon="MagnifyingGlassIcon" description="Search and filter">
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-2">
                    <AppInput v-model="$model.start" name="Start" type="date" placeholder="Start Date" />
                    <AppInput v-model="$model.end" name="End" type="date" placeholder="End Date Date" />
                    <AppInput v-model="$model.search" name="Search" />
                </div>

                <div class="flex flex-row justify-between gap-2 items-end">
                    <!-- SECTION DESKTOP FILTERS -->
                    <div class="gap-2 hidden lg:flex flex-wrap">
                        <AppButton
                            v-for="item in search_filters"
                            @click="changeFilter(item)"
                            :icon="item.icon"
                            :color="item.value == $model.search_filter.value ? 'brand' : undefined"
                            size="sm"
                        >
                            {{ item.display_name }}
                        </AppButton>
                    </div>
                    <!-- SECTION MOBILE FILTERS -->
                    <div class="flex lg:hidden gap-2 justify-start">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="cursor-pointer inline-flex w-full justify-center px-4 py-2 rounded-2xl bg-white dark:bg-dark-002 dark:hover:bg-brand-700 border border-light-003 dark:border-neutral-800 text-sm font-semibold text-brand-900 dark:text-neutral-300 hover:bg-brand-50"
                                >
                                    <span class="text-brand-700 dark:text-neutral-300">
                                        Filters
                                        <span class="bg-brand-100 px-2 rounded-full dark:text-neutral-700">{{ search_filters.length }}</span>
                                    </span>
                                    <EllipsisVerticalIcon class="size-4 text-brand-700 dark:text-neutral-300 my-auto" aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <BasicTransition>
                                <MenuItems
                                    class="gap-1 flex flex-col absolute left-0 z-10 w-42 origin-top-right rounded-xl bg-white dark:bg-dark-002 shadow-lg border border-light-003 p-2 focus:outline-hidden"
                                >
                                    <MenuItem v-for="item in search_filters" @click="changeFilter(item)" v-slot="{ active }">
                                        <div
                                            :class="[
                                                item.value == $model.search_filter.value
                                                    ? 'bg-brand-500 text-brand-50'
                                                    : active
                                                    ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-200'
                                                    : 'text-brand-700 dark:text-neutral-300',
                                                'block pl-3 py-2 text-sm rounded last:rounded-b-xl first:rounded-t-xl font-semibold cursor-pointer'
                                            ]"
                                        >
                                            <component
                                                :is="item.icon"
                                                :class="[
                                                    item.value == $model.search_filter.value
                                                        ? 'bg-brand-500 text-brand-50'
                                                        : active
                                                        ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-200'
                                                        : 'text-brand-700 dark:text-neutral-300',

                                                    'h-4 w-5 text-brand-400 inline mr-2'
                                                ]"
                                            />
                                            {{ item.display_name }}
                                        </div>
                                    </MenuItem>
                                </MenuItems>
                            </BasicTransition>
                        </Menu>
                    </div>
                </div>

                <div class="flex flex-row justify-between gap-2 items-end">
                    <BasicTransition>
                        <AppButton v-if="!$model.start_select" @click="$model.start_select = true" :icon="CheckCircleIcon">Select</AppButton>
                        <div v-else class="flex gap-4">
                            <AppCheckbox name="Select All" v-model="select_all" @change="selectAll()" no_label />
                            <AppButton @click="$model.start_select = false" :icon="TrashIcon" size="transparent" color="transparent" icon_only />
                            <AppButton
                                :icon="XMarkIcon"
                                size="transparent"
                                color="transparent"
                                icon_only
                                @click="
                                    () => {
                                        $model.start_select = false
                                        $emit('deleteSelected')
                                    }
                                "
                            ></AppButton>
                        </div>
                    </BasicTransition>

                    <!-- SECTION DESKTOP ACTIONS -->
                    <div class="gap-2 justify-end hidden md:flex">
                        <AppButton v-if="!no_print" @click="$emit('print')" :icon="PrinterIcon">Print</AppButton>
                        <AppButton v-if="!no_create" @click="$emit('create')" :icon="PlusIcon" color="brand">Create</AppButton>
                        <AppButton @click="$emit('reset')" :icon="ArrowPathIcon">Reset</AppButton>
                    </div>
                    <!-- SECTION MOBILE ACTIONS -->
                    <div class="flex md:hidden gap-2 justify-end">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="cursor-pointer inline-flex w-full justify-center px-4 py-2 rounded-2xl bg-white dark:bg-dark-002 dark:hover:bg-brand-700 border border-light-003 dark:border-neutral-800 text-sm font-semibold text-brand-900 hover:bg-brand-50"
                                >
                                    <span class="text-brand-700 dark:text-neutral-300">Actions</span>
                                    <EllipsisVerticalIcon class="size-4 text-brand-700 my-auto dark:text-neutral-300" aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <BasicTransition>
                                <MenuItems
                                    class="gap-1 flex flex-col absolute right-0 z-10 w-32 origin-top-right rounded-xl bg-white dark:bg-neutral-700 shadow-lg border border-light-003 focus:outline-hidden p-2"
                                >
                                    <MenuItem v-if="!no_print" @click="$emit('print')" v-slot="{ active }">
                                        <div
                                            :class="[
                                                active
                                                    ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                    : 'text-brand-700 dark:text-neutral-300',
                                                'block pl-3 py-2 text-sm font-semibold cursor-pointer rounded rounded-t-xl'
                                            ]"
                                        >
                                            <PrinterIcon
                                                :class="[
                                                    active
                                                        ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                        : 'text-brand-700 dark:text-neutral-300',
                                                    'h-4 w-5 text-brand-400 inline mr-2'
                                                ]"
                                            />
                                            Print
                                        </div>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }" @click="$emit('reset')">
                                        <div
                                            :class="[
                                                active
                                                    ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                    : 'text-brand-700 dark:text-neutral-300',
                                                'block pl-3 py-2 text-sm font-semibold cursor-pointer rounded'
                                            ]"
                                        >
                                            <ArrowPathIcon
                                                :class="[
                                                    active
                                                        ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                        : 'text-brand-700 dark:text-neutral-300',
                                                    'h-4 w-5 text-brand-400 inline mr-2'
                                                ]"
                                            />
                                            Reset
                                        </div>
                                    </MenuItem>
                                    <MenuItem v-if="!no_create" v-slot="{ active }" @click="$emit('create')">
                                        <div
                                            :class="[
                                                active
                                                    ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                    : 'text-brand-700 dark:text-neutral-300',
                                                'block pl-3 py-2 text-sm font-semibold cursor-pointer rounded rounded-b-xl'
                                            ]"
                                        >
                                            <PlusIcon
                                                :class="[
                                                    active
                                                        ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300'
                                                        : 'text-brand-700 dark:text-neutral-300',
                                                    'h-4 w-5 text-brand-400 inline mr-2'
                                                ]"
                                            />
                                            Create
                                        </div>
                                    </MenuItem>
                                </MenuItems>
                            </BasicTransition>
                        </Menu>
                    </div>
                </div>
            </div>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
    ArrowPathIcon,
    CheckCircleIcon,
    EllipsisVerticalIcon,
    MagnifyingGlassIcon,
    PlusIcon,
    PrinterIcon,
    TrashIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'
import BasicCard from './BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import AppCheckbox from '@/components/form/AppCheckbox.vue'

import { ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import { SearchFilter, SearchQuery } from '@/globalTypes'

const { search_filters, no_create, index_data_id } = defineProps<{
    search_filters: SearchFilter[]
    no_create?: boolean
    no_print?: boolean
    index_data_id: string[]
}>()

const $model = defineModel<SearchQuery>({ required: true })
const $emit = defineEmits(['search', 'reset', 'changeFilter', 'create', 'print', 'selectAll', 'deleteSelected'])
const select_all = ref(false)

function changeFilter(item: SearchFilter) {
    $model.value.search_filter = item
    $emit('changeFilter', item)
}

const debounceFn = useDebounceFn(() => {
    $emit('search', 1)
}, 500)

// SEARCH TRIGGER (debounce mode)
watch(
    () => $model.value.search,
    () => {
        debounceFn()
    }
)
// OTHER PARAMETER TRIGGERS
watch(
    () => [$model.value.start, $model.value.end, $model.value.search_filter],
    () => {
        $emit('search', 1)
    }
)

function selectAll() {
    if (select_all.value) {
        $model.value.select_data = index_data_id
    } else {
        $model.value.select_data = []
    }
}
</script>
