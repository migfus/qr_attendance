<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-brand-50 pl-2 pb-2 text-sm font-semibold text-brand-900 hover:bg-brand-50"
            >
                <EllipsisVerticalIcon class="-mr-1 h-5 w-5 text-brand-400" aria-hidden="true" />
            </MenuButton>
        </div>

        <BasicTransition>
            <MenuItems
                class="absolute right-0 z-10 w-32 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-hidden p-1"
            >
                <div class="">
                    <MenuItem v-slot="{ active }">
                        <div
                            @click="PostStore.pinPostApi(postId, groupId)"
                            :class="[active ? 'bg-brand-100 text-brand-600' : 'text-brand-700', 'block pl-3 py-2 text-sm rounded-xl']"
                        >
                            <MapPinIcon :class="[active ? 'bg-brand-100 text-brand-600' : 'text-brand-700', 'h-4 w-5 text-brand-400 inline mr-2']" />
                            <span>{{ isPinned ? 'Unpin' : 'Pin' }}</span>
                        </div>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <div
                            @click="PostStore.selectPost(postId)"
                            :class="[active ? 'bg-brand-100 text-brand-600' : 'text-brand-700', 'block pl-3 py-2 text-sm rounded-xl']"
                        >
                            <PencilIcon :class="[active ? 'bg-brand-100 text-brand-600' : 'text-brand-700', 'h-4 w-5 text-brand-400 inline mr-2']" />
                            <span>Edit</span>
                        </div>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <div @click="report()" :class="[active ? 'bg-red-100 text-red-900' : 'text-brand-700', 'block pl-3 py-2 text-sm rounded-xl']">
                            <FlagIcon :class="[active ? 'bg-red-100 text-red-900' : 'text-brand-700', 'h-4 w-5 text-brand-400 inline mr-2']" />
                            <span>Report</span>
                        </div>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                        <div
                            @click="PostStore.attemptRemovePost(postId, index)"
                            :class="[active ? 'bg-red-100 text-red-900' : 'text-brand-700', 'block pl-3 py-2 text-sm rounded-xl']"
                        >
                            <XMarkIcon :class="[active ? 'bg-red-100 text-red-900' : 'text-brand-700', 'h-5 w-5 text-brand-400 inline mr-2']" />
                            <span>Delete</span>
                        </div>
                    </MenuItem>
                </div>
            </MenuItems>
        </BasicTransition>
    </Menu>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon, XMarkIcon, FlagIcon, PencilIcon, MapPinIcon } from '@heroicons/vue/20/solid'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { useGroupPostStore } from '@/stores/GroupPostStore'

defineProps<{
    isPinned: number // 0 or 1,
    postId: string
    index: number
    groupId: string
}>()

const PostStore = useGroupPostStore()

function report() {
    alert('[reported]')
}
</script>
