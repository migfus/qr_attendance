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
                            class="relative transform overflow-hidden sm:rounded-xl bg-white dark:bg-dark-002 text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-2xl p-4"
                        >
                            <div class="bg-white dark:bg-dark-002">
                                <div class="mt-2">
                                    <div class="flex justify-center rounded-xl relative group">
                                        <img :src="`${$page.props.base_url}${$model?.file_url}`" alt="Preview" class="rounded-xl w-full h-auto -mt-2" />

                                        <div
                                            v-if="!no_navigation"
                                            class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none opacity-0 group-hover:opacity-100 transition-all"
                                        >
                                            <div
                                                @click="$emit('previous')"
                                                class="pointer-events-auto bg-white/80 dark:bg-dark-003/80 dark:text-neutral-200 p-4 rounded-full cursor-pointer shadow"
                                            >
                                                <ArrowLeftIcon class="size-4" />
                                            </div>
                                            <div
                                                @click="$emit('next')"
                                                class="pointer-events-auto bg-white/80 dark:bg-dark-003/80 dark:text-neutral-200 p-4 rounded-full cursor-pointer shadow"
                                            >
                                                <ArrowRightIcon class="size-4" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col mt-2">
                                    <div class="flex justify-between gap-2 dark:text-neutral-200">
                                        <span> {{ $model?.file_type.name }}</span>
                                        <span> {{ $model?.width }} x {{ $model?.height }}</span>
                                        <span> {{ formatBytes($model?.size ?? 0) }}</span>
                                    </div>
                                    <!-- <span> {{ $model.width }}</span> -->
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 sm:flex-row justify-end mt-4">
                                <AppButton
                                    v-if="!no_details"
                                    :icon="InformationCircleIcon"
                                    :href="route('dashboard.arta-id.show', { arta_id: employee_id })"
                                    class="mt-1"
                                >
                                    Details
                                </AppButton>
                                <AppButton
                                    :icon="is_copied ? CheckIcon : Square2StackIcon"
                                    @click="
                                        copyImage(
                                            $model?.file_url ?? '',
                                            () => (is_copied = true),
                                            () => (is_copied = false)
                                        )
                                    "
                                    class="mt-1"
                                >
                                    {{ is_copied ? 'Copied' : 'Copy' }}
                                </AppButton>
                                <AppButton :icon="ArrowDownIcon" color="brand" @click="download()" class="mt-1">Download</AppButton>
                                <AppButton :icon="XMarkIcon" @click="show = false" class="mt-1">Close</AppButton>
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
import { XMarkIcon, ArrowDownIcon, Square2StackIcon, CheckIcon, ArrowLeftIcon, ArrowRightIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { ref } from 'vue'
import { File } from '@/globalTypes'
import { formatBytes, copyImage } from '@/utils'

const $model = defineModel<File | null>('photo')
const show = defineModel<boolean>('show')
const is_copied = ref<boolean>(false)
const { employee_id } = defineProps<{
    employee_id: string
    no_navigation?: boolean
    no_details?: boolean
}>()

const $emit = defineEmits(['next', 'previous'])

function download() {
    if (!$model.value) return
    const link = document.createElement('a')

    link.href = $model.value?.file_url ?? ''
    link.download = 'image.jpg' // You can customize the filename here
    link.click()
    show.value = false
}
</script>
