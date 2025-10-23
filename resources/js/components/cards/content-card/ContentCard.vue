<template>
    <DataTransition role="list" class="gap-2 space-y-1 bg-brand-50 dark:bg-dark-001 sm:rounded-2xl">
        <ContentRow
            v-for="(item, idx) in index_data"
            :key="item.id"
            :item="item"
            :links="links"
            :start_select="start_select"
            :select_data="$model"
            @change="selectRow"
            class="dark:bg-dark-002 bg-white rounded"
            :style="loading_row_data ? { transitionDelay: `${idx * 15}ms` } : {}"
        />
    </DataTransition>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import ContentRow from './ContentRow.vue'

import { ContentCardData } from '@/globalTypes'
import { FunctionalComponent } from 'vue'
const { index_data, start_select } = defineProps<{
    title: string
    index_data: ContentCardData[]
    links: {
        href?: string
        icon: FunctionalComponent
        label: string
        callback: (employee_id: string, email: string) => void
    }[]
    start_select: boolean
}>()

const loading_row_data = ref(true)
setTimeout(() => {
    loading_row_data.value = false
}, index_data.length * 15)

const $model = defineModel<string[]>({ required: true })

function selectRow(select: boolean, id: string) {
    if (select) {
        $model.value.push(id)
    } else {
        $model.value.splice($model.value.indexOf(id), 1)
    }
}
</script>
