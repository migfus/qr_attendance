<template>
    <li class="relative flex justify-between gap-x-6 px-6 py-2 hover:bg-gray-50 dark:hover:bg-dark-003 sm:px-6 sm:first:rounded-t-2xl sm:last:rounded-b-2xl">
        <div class="flex min-w-0 gap-x-2 flex-1">
            <BasicTransition>
                <AppCheckbox
                    v-if="start_select"
                    :name="`checkbox-${item.id}`"
                    :no_label="true"
                    class="mr-[20px]"
                    :checked="select_data.filter((row) => row == item.id).length > 0"
                    @change="$emit('change', select, item.id)"
                    v-model="select"
                />
                <img :src="item.image_url" class="size-10 rounded-full mt-1" />
            </BasicTransition>

            <div class="min-w-0 flex-auto">
                <p :class="['text-brand-700 dark:text-neutral-300', 'text-sm font-semibold leading-6 ']">
                    <span class="inset-x-0 -top-px bottom-0" />
                    {{ item.title }}
                </p>
                <div :class="['text-brand-500 dark:text-neutral-400', 'mt-1 flex text-xs leading-5 ']">
                    <div class="font-semibold -mt-1 flex gap-1 items-center">
                        {{ item.sub_title }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-x-4 line-clamp-1">
            <div class="hidden sm:flex sm:flex-col sm:items-end">
                <p v-if="item.ago" class="text-xs leading-5 text-gray-500 dark:text-neutral-300">{{ item.ago }}</p>
                <p v-if="item.sub_ago" class="text-xs leading-5 text-gray-500 dark:text-neutral-400 font-semibold flex gap-2 items-center">
                    {{ item.sub_ago }}
                </p>
            </div>

            <Menu as="div" class="inline-block text-left">
                <div>
                    <MenuButton class="">
                        <EllipsisVerticalIcon class="size-5 text-brand-700 dark:text-neutral-300 my-auto cursor-pointer" aria-hidden="true" />
                    </MenuButton>
                </div>

                <BasicTransition>
                    <MenuItems
                        class="absolute right-6 z-10 w-48 origin-top-right rounded-xl bg-white dark:bg-dark-002 shadow-lg ring-1 ring-brand-200 dark:ring-neutral-600 ring-opacity-5 focus:outline-hidden p-2"
                    >
                        <MenuItem v-for="link in links" v-slot="{ active }">
                            <Link
                                v-if="link.href"
                                :href="`${link.href.replace('[%id%]', item.id)}`"
                                :class="[
                                    active ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300' : 'text-brand-700 dark:text-neutral-300',
                                    'block pl-3 py-2 text-sm rounded font-semibold cursor-pointer group hover:dark:bg-dark-003 first:rounded-t-xl last:rounded-b-xl'
                                ]"
                            >
                                <component
                                    :is="link.icon"
                                    :class="[
                                        active ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300' : 'text-brand-700 dark:text-neutral-300',
                                        'h-4 w-5 text-brand-400 inline mr-2 group-hover:dark:bg-dark-003'
                                    ]"
                                />
                                {{ link.label }}
                            </Link>
                            <div
                                v-else
                                @click="
                                    () => {
                                        link.callback(item.id, item.sub_ago)
                                    }
                                "
                                :class="[
                                    active ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300' : 'text-brand-700 dark:text-neutral-300',
                                    'block pl-3 py-2 text-sm rounded font-semibold cursor-pointer group hover:dark:bg-dark-003 first:rounded-t-xl last:rounded-b-xl'
                                ]"
                            >
                                <component
                                    :is="link.icon"
                                    :class="[
                                        active ? 'bg-brand-50 dark:bg-dark-002 text-brand-600 dark:text-neutral-300' : 'text-brand-700 dark:text-neutral-300',
                                        'h-4 w-5 text-brand-400 inline mr-2 group-hover:dark:bg-dark-003'
                                    ]"
                                />
                                {{ link.label }}
                            </div>
                        </MenuItem>
                    </MenuItems>
                </BasicTransition>
            </Menu>
        </div>
    </li>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import AppCheckbox from '@/components/form/AppCheckbox.vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid'

import { ContentCardData } from '@/globalTypes'
import { FunctionalComponent, ref } from 'vue'

const { item, select_data } = defineProps<{
    item: ContentCardData
    links: {
        href?: string
        icon: FunctionalComponent
        label: string
        callback: (employee_id: string, email: string) => void
    }[]
    start_select: boolean
    select_data: string[]
}>()

const select = ref(false)
const $emit = defineEmits(['change'])
</script>
