<template>
    <Link
        :class="[
            components.some((row: string) => row === $page.component) ? 'inline-flex items-center border-b-2 border-brand-500 dark:border-yellow-500 px-1 pt-1 text-sm font-medium text-brand-900 dark:text-brand-200 ' :
            'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 dark:text-neutral-400 hover:border-gray-300 hover:text-gray-700 mouse-pointer hover:dark:text-neutral-300'
        ]"
        :href
    >
        <!-- NOTE: ICON -->
        <component v-if="icon" :is="icon" class="h-6 w-6 text-brand-700 dark:text-brand-200 mr-2" aria-hidden="true" />
        <!-- NOTE: NAME -->
        {{ name }}
    </Link>
</template>

<script setup lang="ts">
import { FunctionalComponent } from 'vue'

defineProps<{
    name: string
    href: string
    icon?: FunctionalComponent
    components: string[]
}>()
</script>
