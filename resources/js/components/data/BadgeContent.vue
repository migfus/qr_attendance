<template>
    <span class="text-nowrap inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-md font-medium text-gray-900 ring-1 bg-white shadow-sm ring-inset ring-gray-200">
        <img :src="avatar" class="h-4 w-4 rounded-full" />
        {{ name }}
        <XMarkIcon @click="$emit('remove')" class="h-5 w-5 p-0.5 cursor-pointer hover:bg-red-50 text-red-600 rounded-full" />
    </span>
</template>

<script setup lang="ts">
import { XMarkIcon } from "@heroicons/vue/24/solid"

defineProps<{
    avatar: string
    name: string
}>()

const $emit = defineEmits(["remove"])
</script>
