<template>
    <BasicTransition>
        <div v-if="Object.keys(errors).length != 0" class="rounded-xl bg-red-50 p-4 mb-4 shadow">
            <div class="flex">
                <!-- NOTE: TOGGLE -->
                <div class="flex-shrink-0">
                    <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                </div>
                <!-- NOTE: ERRORS -->
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were {{ count }} errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <DataTransition>
                            <ul v-for="[key, value] in Object.entries(errors)" role="list" class="list-disc space-y-1 pl-5">
                                <p v-html="`${key.toUpperCase()}: ${value}`"></p>
                            </ul>
                        </DataTransition>
                    </div>
                </div>
            </div>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import { XCircleIcon } from "@heroicons/vue/20/solid"
import BasicTransition from "@/components/transitions/BasicTransition.vue"
import DataTransition from "@/components/transitions/DataTransition.vue"

import { computed } from "vue"

const $props = defineProps<{
    errors: object
}>()

const count = computed(() => Object.keys($props.errors).length)
</script>
