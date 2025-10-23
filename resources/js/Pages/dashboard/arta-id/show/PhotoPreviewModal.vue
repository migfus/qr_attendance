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
                            class="relative transform overflow-hidden sm:rounded-xl bg-white dark:bg-neutral-700 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-2xl"
                        >
                            <div class="bg-white dark:bg-neutral-700">
                                <div class="mt-2">
                                    <div class="mt-1 flex justify-center rounded-xl">
                                        <img :src="$model?.file_url" alt="Preview" class="sm:rounded-t-xl w-full h-auto -mt-2" />
                                    </div>
                                </div>
                                <div class="flex flex-col mt-4 p-4">
                                    <div class="flex justify-between gap-2">
                                        <span> {{ $model?.file_type.name }}</span>
                                        <span> {{ $model?.width }} x {{ $model?.height }}</span>
                                        <span> {{ formatBytes($model?.size ?? 0) }}</span>
                                    </div>
                                    <!-- <span> {{ $model.width }}</span> -->
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end gap-2 p-4">
                                <AppButton
                                    :icon="is_copied ? CheckIcon : Square2StackIcon"
                                    @click="
                                        copyImage(
                                            $model?.file_url ?? '',
                                            () => (is_copied = true),
                                            () => (is_copied = false)
                                        )
                                    "
                                >
                                    {{ is_copied ? 'Copied' : 'Copy' }}
                                </AppButton>
                                <AppButton :icon="TrashIcon" @click="deleteImage()" color="danger">Delete</AppButton>
                                <AppButton :icon="ArrowDownIcon" color="brand" @click="download()">Download</AppButton>
                                <AppButton :icon="XMarkIcon" @click="show = false">Close</AppButton>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, ArrowDownIcon, Square2StackIcon, CheckIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { File } from '@/globalTypes'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { formatBytes, copyImage } from '@/utils'

const $model = defineModel<File | null>({ default: null })
const show = defineModel<boolean>('show')
const is_copied = ref<boolean>(false)
const file_id = defineModel<number>('file_id')
const { employee_id } = defineProps<{
    employee_id: string
}>()

function download() {
    if (!$model.value) return
    const link = document.createElement('a')

    link.href = $model.value?.file_url ?? ''
    link.download = 'image.jpg' // You can customize the filename here
    link.click()
    show.value = false
}

function deleteImage() {
    show.value = false

    if (file_id.value) {
        const form_data = new FormData()
        form_data.append('type', 'delete-image')
        form_data.append('id', file_id.value.toString())
        form_data.append('_method', 'PUT')

        // Example: Replace with your actual upload endpoint
        router.post(route('dashboard.arta-id.update', { arta_id: employee_id }), form_data, { preserveState: false })
    }
}
</script>
