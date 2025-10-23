<template>
    <div class="bg-white dark:bg-dark-002 sm:rounded-xl p-6 object-shadow">
        <div class="font-semibold flex justify-between text-sm text-brand-700 dark:text-neutral-300">
            <label>{{ label }}</label>

            <component :is="icon" class="inline-block size-4 mr-2" />
        </div>
        <div class="font-bold text-brand-800 dark:text-neutral-200">{{ current_number }}</div>
        <div class="font-normal text-brand-700 text-sm dark:text-neutral-300">{{ sub_label }}</div>
    </div>
</template>

<script setup lang="ts">
import { FunctionalComponent } from 'vue'

defineProps<{
    label: string
    icon: FunctionalComponent
    current_number: number
    sub_label: string
}>()
</script>
