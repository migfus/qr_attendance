<template>
    <div class="mb-2">
        <!-- NOTE: LABEL -->
        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ name }}</dd>

        <BasicTransition>
            <!-- NOTE: TEXT AREA -->
            <form @submit.prevent="submit()" v-if="active">
                <AppTextArea v-model="$model" size="sm" :name :error="undefined" noLabel />
                <div class="flex justify-end gap-2 mt-2">
                    <AppButton type="button" @click="cancel()" size="sm">Cancel</AppButton>
                    <AppButton type="submit" size="sm" color="brand">Update</AppButton>
                </div>
            </form>

            <!-- NOTE: TOGGLE -->
            <dt v-else @click="active = true" class="group cursor-pointer text-sm font-medium leading-6 text-gray-900 bg-white rounded-xl shadow hover:shadow-md transition-all py-2 px-4">
                <div class="flex justify-between">
                    <div class="">
                        {{ $model }}
                    </div>
                    <div>
                        <PencilIcon class="text-sm text-brand-700 h-4 w-4 inline mb-[3px] align-middle group-hover:opacity-100 transition-all sm:opacity-0" />
                    </div>
                </div>
            </dt>
        </BasicTransition>
    </div>
</template>

<script setup lang="ts">
import BasicTransition from "@/components/transitions/BasicTransition.vue"
import AppButton from "./AppButton.vue"
import { PencilIcon } from "@heroicons/vue/24/solid"
import AppTextArea from "./AppTextArea.vue"

import { ref } from "vue"

const $props = defineProps<{
    name: string
    defaultValue: string
}>()
const default_value = $props.defaultValue
const $model = defineModel<string>()
const $emits = defineEmits(["submit"])
const active = ref<boolean>(false)

function cancel() {
    active.value = false
    $model.value = default_value
}

function submit() {
    active.value = false
    $emits("submit", $model.value)
}
</script>
