<template>
    <div v-if="data.last_page > 1" class="flex bg-white dark:bg-dark-002 sm:rounded-xl p-4 justify-end">
        <TailwindPagination
            :data
            @pagination-change-page="changePage"
            :active-classes="['bg-brand-600 dark:bg-brand-800', 'text-white', 'border-0', 'shadow-inner', '']"
            :item-classes="[
                'border-0 hover:bg-brand-100 dark:hover:bg-dark-003',
                'text-brand-600 dark:text-neutral-300',
                'bg-brand-50 dark:bg-dark-001 cursor-pointer'
            ]"
            :limit="3"
        />
    </div>
</template>

<script setup lang="ts">
import { Pagination } from '@/globalTypes'
import { TailwindPagination } from 'laravel-vue-pagination'

defineProps<{
    data: Pagination<Object>
}>()

const $emit = defineEmits(['changePage'])

function changePage(page: number) {
    $emit('changePage', page)
}
</script>
