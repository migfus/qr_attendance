<template>
    <div class="group mb-2">
        <!-- NOTE: LABEL -->
        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ name }}</dd>

        <BasicTransition>
            <!-- NOTE: SELECT -->
            <form @submit.prevent="submit()" v-if="active">
                <AppSelect :suggestions name="Role" v-model="$model" />
                <div class="flex justify-end gap-2 mt-2">
                    <AppButton type="button" @click="cancel()" size="sm">Cancel</AppButton>
                    <AppButton type="submit" size="sm" color="brand">Update</AppButton>
                </div>
            </form>

            <!-- NOTE: TOGGLE -->
            <dt v-else @click="active = true" class="cursor-pointer text-sm font-medium leading-6 text-gray-900 bg-white rounded-xl shadow py-2 px-4">
                <div class="flex justify-between">
                    <div class="">
                        {{ $model?.name }}
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
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import AppButton from '@/components/form/AppButton.vue'
import { PencilIcon } from '@heroicons/vue/24/solid'
import AppSelect from '@/components/form/AppSelect.vue'

import { ref } from 'vue'
import { Select } from '@/globalTypes'

const $props = defineProps<{
    name: string
    defaultValue: Select
    enablePasswordGenerator?: boolean
    suggestions: Select[]
}>()
const defaultValue = $props.defaultValue
const $model = defineModel<Select>()
const $emits = defineEmits(['submit'])
const active = ref<boolean>(false)

function cancel() {
    active.value = false
    $model.value = defaultValue
}

function submit() {
    active.value = false
    $emits('submit', $model.value)
}
</script>
