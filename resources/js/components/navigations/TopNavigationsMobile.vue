<template>
    <DisclosureButton
        as="a"
        :href
        :class="[
      components.some((row: string) => row === $page.component) ?
            ' border-brand-500 bg-brand-50  text-brand-700 dark:bg-neutral-800 dark:text-neutral-200' :
            ' border-transparent  text-gray-500 dark:text-neutral-300 dark:hover:text-neutral-200 hover:border-gray-300 dark:hover:border-netural-600 hover:bg-gray-50 dark:hover:bg-neutral-800 hover:text-gray-700 ' ,
        'border-l-4 py-2 pl-3 pr-4 text-base font-medium transition-all items-center flex'
    ]"
    >
        <!-- NOTE: ICON -->
        <component v-if="icon" :is="icon" class="h-6 w-6 text-brand-700 inline mr-2 dark:text-neutral-200" aria-hidden="true" />
        <!-- NOTE: NAME -->
        <span class="inline">{{ name }}</span>
    </DisclosureButton>
</template>

<script setup lang="ts">
import { DisclosureButton } from '@headlessui/vue'

import { FunctionalComponent } from 'vue'

defineProps<{
    name: string
    href: string
    components: string[]
    icon?: FunctionalComponent
}>()
</script>
