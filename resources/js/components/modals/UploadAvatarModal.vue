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
                <div class="fixed inset-0 bg-dark-003/50 backdrop-blur-md" />
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
                            class="relative transform overflow-hidden sm:rounded-xl bg-white dark:bg-dark-002 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg"
                        >
                            <!-- NOTE: UPLOAD AVATAR MODAL -->
                            <div class="bg-white dark:bg-dark-002 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <!-- NOTE: CROP AREA -->
                                <div class="flex flex-col">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                        <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-300" id="modal-title">{{ name }}</h3>
                                        <div class="mt-2 flex flex-col items-center mx-auto">
                                            <VuePictureCropper
                                                :boxStyle="config.cropperBoxStyle"
                                                :img="$model"
                                                :options="config.cropperOption"
                                                class="rounded-xl"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- NOTE: UPLOAD AREA -->
                                <!-- FIXME: Need drag and down feature (currently not) -->
                                <div class="mt-2">
                                    <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                        <div class="space-y-1 text-center">
                                            <svg
                                                class="mx-auto h-12 w-12 text-gray-400"
                                                stroke="currentColor"
                                                fill="none"
                                                viewBox="0 0 48 48"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 dark:text-neutral-300">
                                                <label
                                                    for="file-upload"
                                                    class="relative cursor-pointer rounded-xl px-2 bg-white dark:bg-neutral-800 font-medium text-primary-600 focus-within:outline-hidden focus-within:ring-2 focus-within:ring-primary-500 focus-within:ring-offset-2 hover:text-primary-500"
                                                >
                                                    <span>Upload a file</span>
                                                    <input
                                                        @change="SelectFile"
                                                        ref="uploadInput"
                                                        id="file-upload"
                                                        name="file-upload"
                                                        type="file"
                                                        accept="image/jpg, image/jpeg, image/png, image/gif"
                                                        class="sr-only"
                                                    />
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-neutral-300">PNG, JPG, GIF up to 10MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- NOTE: ACTIONS BUTTON -->
                            <div class="flex flex-col gap-2 p-4 sm:flex-row justify-end">
                                <AppButton
                                    :icon="ArrowUpOnSquareIcon"
                                    block
                                    color="brand"
                                    @click="
                                        () => {
                                            GetResult()
                                            show = false
                                        }
                                    "
                                    class="mt-1"
                                >
                                    Save
                                </AppButton>
                                <AppButton :icon="XMarkIcon" @click="show = false" class="mt-1">Cancel</AppButton>
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
import { XMarkIcon, ArrowUpOnSquareIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { ref } from 'vue'
import VuePictureCropper, { cropper } from 'vue-picture-cropper'

const $model = defineModel<string>()
const show = defineModel<boolean>('show')
const $props = defineProps<{
    size: number[]
    name: string
    ratio: number
}>()
const $emit = defineEmits(['upload'])
const upload_input = ref<string | null>(null)
const config = {
    cropperOption: {
        viewMode: 2,
        dragMode: 'crop',
        aspectRatio: $props.ratio
    },
    cropperBoxStyle: {
        width: '100%',
        height: '100%',
        backgroundColor: '#f8f8f8',
        margin: 'auto'
    }
}

function SelectFile(event: Event) {
    // @ts-ignore
    const { files } = event.target
    if (!files || !files.length) return

    const file = files[0]
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => {
        $model.value = String(reader.result)
        if (!upload_input.value) return
        upload_input.value = ''
    }
}

async function GetResult() {
    if (!cropper) return
    const base64 = cropper.getDataURL({
        maxWidth: $props.size[0],
        maxHeight: $props.size[1],
        imageSmoothingQuality: 'high'
    })
    $model.value = base64
    $emit('upload', base64)
}
</script>
