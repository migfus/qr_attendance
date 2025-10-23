<template>
    <div class="flex flex-col gap-4 dark:bg-dark-001">
        <Unedited />

        <SearchCard
            v-model="query"
            :index_data_id="index_data.data.map((row) => row.id.toString())"
            :search_filters
            :no_print="true"
            @reset="Object.assign(query, initSearchQuery(search_filters[0]))"
            @search="getIndexData"
            no_create
        />

        <DataTransition class="flex flex-col sm:grid grid-cols-2 xl:grid-cols-4 gap-4">
            <GalleryCard
                v-for="(item, idx) in index_data.data"
                :item
                @showPreview="showPreview"
                v-model="query.select_data"
                :start_select="query.start_select"
                :style="{ transitionDelay: `${idx * 20}ms` }"
            />
        </DataTransition>

        <PaginationCard :data="index_data" @changePage="getIndexData" />

        <PhotoPreviewModal
            v-model:show="show_preview"
            v-model:photo="preview_modal.photo"
            :employee_id="preview_modal.employee_id"
            @next="next()"
            @previous="prev()"
        />
    </div>
</template>

<script setup lang="ts">
import SearchCard from '@/components/cards/SearchCard.vue'
import PaginationCard from '@/components/data/PaginationCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import PhotoPreviewModal from '@/components/modals/PhotoPreviewModal.vue'
import GalleryCard from '@/components/cards/GalleryCard.vue'

import { Pagination, SearchQuery, File } from '@/globalTypes'

import search_filters from './searchFilters'
import { initSearchQuery, searchQuery, defaultRouterState } from '@/utils'
import { router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import Unedited from '@/components/test/Unedited.vue'

const { index_data } = defineProps<{
    index_data: Pagination<File>
}>()

const query = reactive<SearchQuery>({
    search: route().queryParams.search?.toString() ?? '',
    start: route().queryParams.start?.toString() ?? '',
    end: route().queryParams.end?.toString() ?? '',
    search_filter: route().queryParams.search_filter
        ? search_filters.find((item) => item.value == route().queryParams.search_filter) ?? search_filters[0]
        : search_filters[0],
    start_select: false,
    select_data: []
})

const show_preview = ref(false)

const preview_modal = reactive({
    photo: null as File | null,
    employee_id: ''
})

function showPreview(file: File) {
    show_preview.value = true
    preview_modal.photo = file
    preview_modal.employee_id = file.fileable?.id?.toString() ?? ''
}

function getIndexData(page = 1) {
    window.scrollTo({ top: 0, left: 0, behavior: 'smooth' })
    router.get(route('dashboard.galleries.index'), searchQuery(query, page), defaultRouterState())
}

function next() {
    if (!preview_modal.photo) return
    const currentIndex = index_data.data.findIndex((item) => item.id === preview_modal.photo?.id)
    if (currentIndex === index_data.data.length - 1) {
        // At last item, try to go to next page if available
        if (index_data.current_page < index_data.last_page) {
            getIndexData(index_data.current_page + 1)
            // Wait for data to load, then show first item
            setTimeout(() => {
                if (index_data.data.length > 0) {
                    showPreview(index_data.data[0])
                }
            }, 300)
        }
    } else {
        const nextIndex = (currentIndex + 1) % index_data.data.length
        showPreview(index_data.data[nextIndex])
    }
}

function prev() {
    if (!preview_modal.photo) return
    const currentIndex = index_data.data.findIndex((item) => item.id === preview_modal.photo?.id)
    if (currentIndex === 0) {
        // At first item, try to go to previous page if available
        if (index_data.current_page > 1) {
            getIndexData(index_data.current_page - 1)
            // Wait for data to load, then show last item
            setTimeout(() => {
                if (index_data.data.length > 0) {
                    showPreview(index_data.data[index_data.data.length - 1])
                }
            }, 300)
        }
    } else {
        const prevIndex = (currentIndex - 1 + index_data.data.length) % index_data.data.length
        showPreview(index_data.data[prevIndex])
    }
}
</script>
