<template>
    <div :class="[noBorder ? '' : 'border-t border-brand-100 dark:border-dark-003 ', '']">
        <p v-if="!no_title" class="text-sm font-bold my-2">{{ title }}</p>
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title: string
    noBorder?: boolean
    no_title?: boolean
}>()
</script>
