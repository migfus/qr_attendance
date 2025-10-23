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
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
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
                            class="relative transform overflow-hidden rounded-xl bg-white dark:bg-dark-002 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg"
                        >
                            <!-- NOTE: UPLOAD AVATAR MODAL -->
                            <div class="bg-white dark:bg-dark-002 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <!-- NOTE: CROP AREA -->
                                <div class="flex">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-200" id="modal-title">
                                        {{ name }}
                                    </h3>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                        <div class="mt-2 flex flex-col items-center">
                                            <VuePictureCropper
                                                :boxStyle="config.cropperBoxStyle"
                                                :img="$model"
                                                :options="config.cropperOption"
                                                class="mx-auto"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- NOTE: UPLOAD AREA -->
                                <!-- FIXME: Need drag and down feature (currently not) -->
                                <div class="mt-2">
                                    <div
                                        class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 dark:hover:bg-dark-003 cursor-pointer px-6 pt-5 pb-6 group"
                                        @click="triggerUpload()"
                                    >
                                        <div class="space-y-1 text-center">
                                            <svg
                                                class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-200"
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
                                            <div class="flex text-sm text-gray-600">
                                                <label for="file-upload" class="dark:text-neutral-200">
                                                    <span>Upload a file</span>
                                                    <input
                                                        @change="SelectFile"
                                                        ref="uploadInput"
                                                        id="file-upload"
                                                        name="file-upload"
                                                        type="file"
                                                        accept="image/jpg, image/jpeg, image/png, image/gif, image/heic"
                                                        class="sr-only"
                                                        style="pointer-events: none"
                                                    />
                                                </label>
                                                <p class="pl-1 dark:text-neutral-300">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-neutral-300">PNG, JPG, GIF, HEIC up to 10MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- NOTE: ACTIONS BUTTON -->
                            <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 sm:mt-0 mx-4 mb-4">
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

import { useTemplateRef } from 'vue'
import VuePictureCropper, { cropper } from 'vue-picture-cropper'

const $model = defineModel<string>()
const show = defineModel<boolean>('show')
const $props = defineProps<{
    size: number[]
    name: string
    ratio: number
}>()
const $emit = defineEmits(['upload'])
const upload_input = useTemplateRef('uploadInput')
const config = {
    cropperOption: {
        viewMode: 2,
        autoCropArea: 1,
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
const raw_image = defineModel<string>('raw', { required: true })

function SelectFile(event: Event) {
    // @ts-ignore
    const { files } = event.target
    if (!files || !files.length) return

    const file = files[0]
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => {
        const img = new Image()
        img.src = String(reader.result)
        img.onload = () => {
            const canvas = document.createElement('canvas')
            const ctx = canvas.getContext('2d')
            if (!ctx) return

            const maxDimension = 800
            let { width, height } = img

            if (width > height && width > maxDimension) {
                height = (height * maxDimension) / width
                width = maxDimension
            } else if (height > width && height > maxDimension) {
                width = (width * maxDimension) / height
                height = maxDimension
            }

            canvas.width = width
            canvas.height = height
            ctx.drawImage(img, 0, 0, width, height)

            const resizedBase64 = canvas.toDataURL('image/jpeg', 1.0)
            $model.value = resizedBase64
            raw_image.value = resizedBase64
        }
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
    $emit('upload', {
        cropped: base64,
        raw: raw_image
    })
}

function triggerUpload() {
    upload_input.value?.click()
}
</script>

<style>
.cropper-point {
    width: 10px;
    height: 10px;
    border-radius: 100%;
}
</style>
