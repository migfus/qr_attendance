<template>
    <div>
        <BasicCard title="Files" description="Files uploaded" :icon="SquaresPlusIcon">
            <div class="flex gap-2 flex-wrap">
                <button v-for="item in files" @click="showPreview(item)" class="rounded-lg cursor-pointer">
                    <img :src="item.thumbnail_url" alt="File" class="size-16 rounded-lg object-cover border border-light-003 dark:border-dark-003" />
                </button>

                <button
                    @click="show_upload = true"
                    class="cursor-pointer dark:bg-dark-001 bg-brand-50 hover:bg-brand-100 dark:hover:bg-dark-003 rounded-lg p-2 size-16"
                >
                    <PlusIcon class="size-8 m-auto text-brand-600 dark:text-neutral-400" />
                </button>
            </div>
        </BasicCard>

        <PhotoPreviewModal v-model:show="show_preview" v-model:photo="photo_preview" v-model:file_id="file_id" :employee_id @next="next" @previous="prev" />
        <UploadFileModal v-model:show="show_upload" :employee_id />
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { PlusIcon, SquaresPlusIcon } from '@heroicons/vue/24/outline'
import UploadFileModal from './UploadFileModal.vue'
import PhotoPreviewModal from '@/components/modals/PhotoPreviewModal.vue'

import { File } from '@/globalTypes'

import { ref } from 'vue'

const { files } = defineProps<{
    files: File[]
    employee_id: string
}>()

const show_preview = ref(false)
const show_upload = ref(false)
const photo_preview = ref<File | null>(null)
const file_id = ref<number>(0)

function showPreview(file: File) {
    show_preview.value = true
    photo_preview.value = file
    file_id.value = file.id
}

function next() {
    if (!photo_preview) return
    const currentIndex = files.findIndex((item) => item.id === photo_preview.value?.id)

    const nextIndex = (currentIndex + 1) % files.length
    showPreview(files[nextIndex])
}

function prev() {
    if (!photo_preview) return
    const currentIndex = files.findIndex((item) => item.id === photo_preview.value?.id)

    const prevIndex = (currentIndex - 1 + files.length) % files.length
    showPreview(files[prevIndex])
}
</script>
