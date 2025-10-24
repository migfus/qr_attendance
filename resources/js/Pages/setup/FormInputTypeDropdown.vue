<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton class="cursor-pointer font-semibold w-full bg-dark-001 p-2 px-4 rounded-full object-shadow flex gap-2 items-center">
                <component :is="current_type.icon" class="size-4 text-brand-400" aria-hidden="true" />
                <div class="text-sm">{{ current_type.name }}</div>
            </MenuButton>
        </div>

        <BasicTransition>
            <MenuItems class="absolute right-0 z-10 w-42 origin-top-right rounded-xl p-2 focus:outline-hidden object-shadow bg-dark-001">
                <DataTransition class="flex flex-col gap-1">
                    <MenuItem v-for="item in types" :key="item.name" v-slot="{ active }" class="cursor-pointer font-semibold bg-dark-002 object-shadow">
                        <div
                            @click="$emit('changeType', id, item)"
                            :class="[active ? 'bg-dark-003 text-light-001' : 'text-light-003', 'pl-3 py-2 text-sm rounded-xl flex gap-2']"
                        >
                            <component
                                :is="item.icon"
                                :class="[active ? 'bg-dark-003 text-light-001' : 'text-light-003', 'h-4 w-5 text-brand-400 inline mr-2']"
                            />
                            <div>{{ item.name }}</div>
                        </div>
                    </MenuItem>
                </DataTransition>
            </MenuItems>
        </BasicTransition>
    </Menu>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { InputType } from './setupInterfaces'
import DataTransition from '@/components/transitions/DataTransition.vue'

const $emit = defineEmits(['changeType'])
defineProps<{
    types: InputType[]
    current_type: InputType
    id: string
}>()
</script>
