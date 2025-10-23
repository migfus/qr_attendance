<template>
    <div v-if="!hide" class="rounded-xl bg-yellow-50 p-4 shadow mb-4">
        <div class="flex">
            <!-- NOTE: ICON -->
            <div class="flex-shrink-0">
                <ExclamationTriangleIcon class="h-5 w-5 text-yellow-600" aria-hidden="true" />
            </div>
            <!-- NOTE: TITLE/DESCRIPTION -->
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">{{ title }}</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <slot></slot>
                </div>
            </div>
            <!-- NOTE: TOGGLE -->
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button
                        @click="remove()"
                        type="button"
                        class="inline-flex rounded-xl bg-white shadow p-1.5 text-yellow-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 focus:ring-offset-yellow-50"
                    >
                        <span class="sr-only">Dismiss</span>
                        <XMarkIcon class="h-5 w-5 text-yellow-700" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ExclamationTriangleIcon, XMarkIcon } from '@heroicons/vue/20/solid'

import { useStorage } from '@vueuse/core'

const $props = defineProps<{
    title: string
    storageKey: string
}>()

const hide = useStorage(`hide-alert-${$props.storageKey}`, false)

function remove() {
    hide.value = true
}
</script>
