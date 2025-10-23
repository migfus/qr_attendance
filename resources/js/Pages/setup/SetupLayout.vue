<template>
    <div class="sm:p-4 flex flex-col lg:grid grid-cols-3 bg-light-002 dark:bg-dark-001 gap-4">
        <ProgressSection class="lg:col-span-3 mx-auto my-8 mb-10" :current_step />

        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import ProgressSection from './ProgressSection.vue'

defineProps<{
    current_step: number
}>()
</script>
