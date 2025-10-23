<template>
    <TransitionRoot as="template" :show="$modal">
        <Dialog as="div" class="relative z-40" @close="$modal = false">
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

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
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
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-lg"
                        >
                            <form @submit.prevent="deleteUser()" class="mx-2 sm:mx-0">
                                <!-- NOTE: REMOVEAL  PROMPT -->
                                <div class="bg-white dark:bg-neutral-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="flex flex-col">
                                        <!-- NOTE: ICON -->
                                        <div class="flex gap-2 w-full">
                                            <div
                                                class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                                            >
                                                <ExclamationTriangleIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                                            </div>
                                            <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-200 mt-0.5">
                                                {{ title ?? 'Deletion Account' }}
                                            </DialogTitle>
                                        </div>

                                        <!-- NOTE: TITLE -->
                                        <div class="mt-3 sm:ml-4 sm:mt-0 sm:text-left">
                                            <div class="mt-2">
                                                <!-- <p class="text-sm text-gray-500">Are you sure you want to remove this account? All of user's data will be permanently removed. This action cannot be undone.</p> -->
                                                <p class="text-sm text-brand-700 dark:text-neutral-300"><slot> </slot></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- NOTE: ACTIONS BUTTON -->
                                <div class="bg-gray-50 dark:bg-neutral-700 px-4 py-3 flex flex-col-reverse sm:flex-row gap-2 justify-end">
                                    <AppButton @click="$modal = false" :icon="XMarkIcon" type="button">Cancel</AppButton>
                                    <AppButton color="danger" :icon="ArrowRightIcon">
                                        {{ confirmMessage ?? 'Yes, Delete User!' }}
                                    </AppButton>
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
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ExclamationTriangleIcon } from '@heroicons/vue/24/solid'
import AppButton from '@/components/form/AppButton.vue'
import { FunctionalComponent } from 'vue'
import { ArrowRightIcon, XMarkIcon } from '@heroicons/vue/24/outline'

defineProps<{
    confirmMessage: string
    confirmIcon: FunctionalComponent
    title: string
}>()
const $modal = defineModel<boolean>()
const $emit = defineEmits(['confirm'])

function deleteUser() {
    $modal.value = false
    $emit('confirm', true)
}
</script>
