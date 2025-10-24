<template>
    <BasicTransition>
        <p class="text-brand-200 font-semibold text-sm flex gap-2">
            <component v-if="icon" :is="icon" class="size-5" />
            <InformationCircleIcon v-else class="size-5" />
            <slot></slot>
        </p>
    </BasicTransition>
</template>

<script setup lang="ts">
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import { FunctionalComponent } from 'vue'
import BasicTransition from '../transitions/BasicTransition.vue'

defineProps<{
    icon?: FunctionalComponent
}>()
</script>
