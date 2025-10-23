<template>
    <TransitionRoot as="template" :show="$open_modal_model">
        <Dialog as="div" class="relative z-40" @close="$open_modal_model = false">
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
                            :class="[`sm:max-w-xl `, 'flex-grow relative text-left rounded-xl bg-white shadow-xl transition-all sm:w-full max-w-full']"
                        >
                            <!-- NOTE: FORM MODAL -->
                            <BasicCard :icon :title :description size="lg">
                                <!-- NOTE: CONTENTS -->
                                <slot></slot>
                            </BasicCard>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import BasicCard from '@/components/cards/BasicCard.vue'

import { FunctionalComponent } from 'vue'

defineProps<{
    icon: FunctionalComponent
    title: string
    description: string
}>()
const $open_modal_model = defineModel<boolean>()
</script>
