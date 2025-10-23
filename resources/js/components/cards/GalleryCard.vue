<template>
    <div class="bg-white dark:bg-dark-002 sm:rounded-xl">
        <div @click="$emit('showPreview', item)" class="flex flex-col gap-2 cursor-pointer">
            <img :src="`${$page.props.base_url}${item.thumbnail_url}`" class="w-full h-64 object-cover sm:rounded-t-xl" />
            <div class="text-xs bg-neutral-700 absolute text-neutral-100 rounded-xl m-2 px-2 py-1">{{ item.file_type.name }}</div>
            <div class="flex flex-col gap-2 mb-2">
                <div class="px-4 flex justify-between gap-2">
                    <h3 class="text-xm text-brand-500 dark:text-neutral-200 font-semibold line-clamp-1 grow">
                        {{ employeeFullName(item.fileable, true) }}
                    </h3>
                    <h3 class="text-sm text-brand-500 dark:text-neutral-300 font-semibold line-clamp-1 shrink-0">{{ formatBytes(item.size) }}</h3>
                </div>
                <div class="px-4 flex justify-between gap-2">
                    <h3 class="text-sm text-brand-500 dark:text-neutral-400 font-semibold line-clamp-1 shrink-0">
                        {{ `${item.width} x ${item.height}` }}
                    </h3>
                    <h3 class="text-sm text-brand-500 dark:text-neutral-400 font-semibold line-clamp-1 shrink-0">
                        {{ messengerStyleTime(item.created_at) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="flex justify-end m-4">
            <AppCheckbox
                v-if="start_select"
                name="select"
                v-model="select"
                :checked="$model.filter((row) => row == item.id.toString()).length > 0"
                no_label
                @change="selectRow()"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { File } from '@/globalTypes'
import { messengerStyleTime, employeeFullName, formatBytes } from '@/utils'
import AppCheckbox from '../form/AppCheckbox.vue'
import { ref } from 'vue'

const { item } = defineProps<{
    item: File
    start_select: boolean
}>()

const select = ref(false)
const $model = defineModel<string[]>({ required: true })

const $emit = defineEmits(['showPreview'])

function selectRow() {
    if (select.value) {
        $model.value.push(item.id.toString())
    } else {
        $model.value.splice($model.value.indexOf(item.id.toString()), 1)
    }
}
</script>
