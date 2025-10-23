<template>
    <div class="py-4 flex flex-col lg:grid lg:grid-cols-2 xl:grid-cols-3 gap-2">
        <div v-for="item in users" :key="item.id" class="bg-brand-50 dark:bg-dark-001 rounded-xl p-4 flex justify-between items-start">
            <div class="">
                <img :src="item.avatar_url" :alt="item.name" class="inline-block size-4 rounded-full ring-2 dark:ring-neutral-500 ring-neutral-300 mr-2" />
                <label class="text-sm font-semibold dark:text-gray-100">{{ item.name }}</label>

                <div class="whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">{{ item.email }}</div>
            </div>

            <div class="">
                <AppButton
                    size="sm"
                    color="brand"
                    :icon="ArrowPathIcon"
                    @click="$emit('transfer', item.id, item.name, item.avatar_url)"
                    :disabled="!checkPermissions(['Roles & Permissions/Update/All'], $page.props.auth?.permissions)"
                >
                    Transfer
                </AppButton>
            </div>
        </div>

        <div v-if="users.length == 0" class="bg-brand-50 dark:bg-dark-001 rounded-xl p-4 flex justify-center lg:col-span-2 xl:col-span-3">
            <div class="font-semibold text-brand-600 dark:text-light-001">No users assigned</div>
        </div>

        <div class="flex lg:col-span-2 xl:col-span-3 justify-end">
            <AppButton :icon="XMarkIcon" @click="$emit('close')">Close</AppButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ArrowPathIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { User } from '@/globalTypes'

import { checkPermissions } from '@/utils'

defineProps<{
    users: User[]
}>()

const $emit = defineEmits(['close', 'transfer'])
</script>
