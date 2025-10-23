<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-40" @close="show = false">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-transition-opacity backdrop-blur" />
            </TransitionChild>

            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden sm:rounded-xl bg-white dark:bg-dark-002 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-md"
                        >
                            <form @submit.prevent="uploadImage()">
                                <div class="bg-white dark:bg-dark-002 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div v-if="preview_url" class="mt-2">
                                        <div class="mt-1 flex items-center gap-4 rounded-xl border-2 border-dashed border-gray-300 p-2 flex-col">
                                            <img :src="preview_url" alt="Preview" class="rounded-xl w-full h-auto" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col mt-4 dark:bg-dark-002 bg-light-002 hover:dark:bg-dark-003 p-4 rounded-xl dark:text-neutral-200">
                                        <input type="file" accept="image/*" ref="fileInput" @change="handleFileUpload" />
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row justify-end p-4 gap-2">
                                    <AppButton :icon="ArrowUpIcon" color="brand" class="mt-1">Upload</AppButton>
                                    <AppButton :icon="XMarkIcon" @click="show = false" class="mt-1" type="button">Close</AppButton>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, ArrowUpIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const show = defineModel<boolean>('show')

const { employee_id } = defineProps<{
    employee_id: string
}>()

const selected_file = ref<File | null>(null)
const preview_url = ref<string | null>(null)

function handleFileUpload(event: Event) {
    const target = event.target as HTMLInputElement
    if (!target.files || !target.files.length) return

    selected_file.value = target.files[0]
    const reader = new FileReader()
    reader.onload = (e) => {
        preview_url.value = e.target?.result as string
    }
    reader.readAsDataURL(selected_file.value)
}

function uploadImage() {
    if (!selected_file.value) {
        alert('Please select a file to upload.')
        return
    }

    const form_data = new FormData()
    form_data.append('type', 'upload-image')
    form_data.append('file', selected_file.value)
    form_data.append('employee_id', employee_id)
    form_data.append('_method', 'PUT')

    // Example: Replace with your actual upload endpoint
    router.post(route('dashboard.arta-id.update', { arta_id: employee_id }), form_data, { preserveState: false })
}
</script>
