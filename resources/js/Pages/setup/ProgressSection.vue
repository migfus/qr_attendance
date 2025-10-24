<template>
    <nav aria-label="Progress">
        <ol role="list" class="flex items-center">
            <li v-for="(step, idx) in steps" :key="step.name" :class="[idx !== steps.length - 1 ? 'pr-8 sm:pr-20' : '', 'relative']">
                <template v-if="current_step > idx + 1">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-brand-300 dark:bg-brand-300" />
                    </div>
                    <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-brand-500 dark:bg-brand-400 hover:bg-brand-400">
                        <CheckIcon class="h-5 w-5 text-brand-100 dark:text-brand-800" aria-hidden="true" />
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
                <template v-else-if="current_step === idx + 1">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-light-003 dark:bg-dark-003" />
                    </div>
                    <a
                        href="#"
                        class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-brand-300 bg-light-003 dark:bg-dark-002"
                        aria-current="step"
                    >
                        <span class="h-2.5 w-2.5 rounded-full bg-brand-300" aria-hidden="true" />
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
                <template v-else>
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-0.5 w-full bg-light-003 dark:bg-dark-003" />
                    </div>
                    <a
                        href="#"
                        class="group relative flex h-8 w-8 items-center justify-center rounded-full border-2 border-light-003 dark:border-dark-003 bg-brand-100 dark:bg-dark-002 hover:border-brand-800"
                    >
                        <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300" aria-hidden="true" />
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
            </li>
        </ol>
    </nav>
</template>

<script setup lang="ts">
import { CheckIcon } from '@heroicons/vue/20/solid'

defineProps<{
    current_step: number
}>()

const steps = [
    { name: 'Step 1', href: '#', status: 'complete' },
    { name: 'Step 2', href: '#', status: 'complete' },
    { name: 'Step 3', href: '#', status: 'current' },
    { name: 'Step 4', href: '#', status: 'upcoming' },
    { name: 'Step 5', href: '#', status: 'upcoming' },
    { name: 'Step 6', href: '#', status: 'upcoming' },
    { name: 'Step 7', href: '#', status: 'upcoming' }
]
</script>
