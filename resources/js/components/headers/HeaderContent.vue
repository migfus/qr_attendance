<template>
    <div class="border-b border-gray-200 pb-5 mb-4 flex-inline sm:flex justify-between">
        <div class="">
            <h1 class="text-semibold font-semibold text-gray-900 text-xl">{{ title }}</h1>
            <p class="mt-2 max-w-4xl text-sm text-gray-500">{{ desc }}</p>
        </div>

        <div v-if="allowSearch" class="mt-3 flex">
            <AppButton @click="add()" color="brand" :icon="PlusIcon" class="mb-[8px] h-[34px] rounded-r-sm">Add</AppButton>
            <AppInput v-model="$model" :error="undefined" name="search" placeholder="Search" noLabel size="sm" injectCSS="rounded-l-sm" class="flex-grow" />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'

const $model = defineModel<string>({ required: true })
const $emit = defineEmits(['add'])

defineProps<{
    title: string
    desc: string
    allowSearch?: boolean
}>()

function add() {
    $emit('add', true)
}
</script>
