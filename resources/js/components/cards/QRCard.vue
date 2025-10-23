<template>
    <div :key="item.id" class="bg-white dark:bg-dark-002 rounded-2xl p-4 items-center flex flex-col gap-2 hover:dark:bg-dark-003">
        <QRCode :id="item.id" :employee_name="item.name" :selected_office="item.department" :status="item.status" noLabel noImage noRemove class="w-full" />
        <Link :href="route('dashboard.guest-qr.edit', { guest_qr: item.id })" class="flex flex-col items-start w-full">
            <span class="text-brand-700 dark:text-neutral-200 font-bold line-clamp-1">{{ item.name }}</span>
            <span class="text-brand-500 dark:text-neutral-300 font-semibold line-clamp-1">{{ item.department.name }}</span>
            <div class="flex justify-between items-center">
                <span :class="[item.deleted_at ? 'text-red-500' : 'text-brand-500 dark:text-neutral-400', 'font-semibold']">
                    {{ messengerStyleTime(item.created_at) }}
                </span>
                <TrashIcon v-if="item.deleted_at" class="inline size-4 ml-2 text-red-500" />
            </div>
        </Link>

        <!-- <div class="flex justify-end ml-auto mr-5">
            <AppCheckbox
                v-if="start_select"
                name="Select"
                v-model="select"
                no_label
                :checked="$model.filter((row) => row == item.id).length > 0"
                @change="selectRow()"
            />
        </div> -->
    </div>
</template>

<script setup lang="ts">
import { GuestQR } from '@/globalTypes'
import QRCode from '@/components/cards/QRCode.vue'
import { TrashIcon } from '@heroicons/vue/24/outline'
import AppCheckbox from '@/components/form/AppCheckbox.vue'

import { messengerStyleTime } from '@/utils'
import { ref } from 'vue'

const { item } = defineProps<{
    item: GuestQR
    start_select: boolean
}>()

const $model = defineModel<string[]>({ default: [] })

const select = ref(false)

function selectRow() {
    if (select.value) {
        $model.value.push(item.id)
    } else {
        $model.value.splice($model.value.indexOf(item.id), 1)
    }
}
</script>
