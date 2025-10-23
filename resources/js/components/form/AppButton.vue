<template>
    <BasicTransition>
        <!-- NOTE: REDIRECT MODE -->
        <a
            v-if="externalLinkOnly"
            :href="disabled ? '#' : href"
            target="_blank"
            :type
            :disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                'inline-flex rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <ArrowPathIcon
                v-if="(loading && !noLoading) || forceLoading"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5 animate-spin', iconColor]"
                aria-hidden="true"
            />
            <component
                v-else-if="icon"
                :is="icon"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5', iconColor]"
                aria-hidden="true"
            />
            <slot></slot>
        </a>
        <!-- NOTE: LINK MODE -->
        <Link
            v-else-if="href"
            :href="disabled ? '#' : href"
            :type
            :disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                'inline-flex rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <ArrowPathIcon
                v-if="(loading && !noLoading) || forceLoading"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5 animate-spin', iconColor]"
                aria-hidden="true"
            />
            <component
                v-else-if="icon"
                :is="icon"
                :class="[$props.size == 'sm' && 'h-[15px] w-[15px] mt-[1px]', '-ml-1 mr-2 h-5 w-5', iconColor]"
                aria-hidden="true"
            />
            <slot></slot>
        </Link>

        <!-- NOTE: BUTTON MODE [default]-->
        <button
            v-else
            :type
            :disabled="loading || disabled || forceLoading"
            :class="[
                buttonColor,
                textAlignment,
                buttonSize,
                color == 'transparent' ? 'p-0 m-0 dark:hover:bg-neutral-900' : '',
                'rounded-2xl font-medium hover:shadow-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 transition-all flex items-center gap-2 cursor-pointer object-shadow'
            ]"
            @click="clicked = true"
        >
            <ArrowPathIcon
                v-if="(loading && !noLoading) || forceLoading"
                :class="[color == 'transparent' ? 'size-5' : iconOnly ? 'size-4' : 'size-4', 'animate-spin', iconColor]"
            />
            <component v-else-if="icon" :is="icon" :class="[color == 'transparent' ? 'size-5' : iconOnly ? 'size-4' : 'size-4', iconColor]" />
            <slot v-if="!iconOnly"></slot>
        </button>
    </BasicTransition>
</template>

<script setup lang="ts">
import { ArrowPathIcon } from '@heroicons/vue/24/solid'
import BasicTransition from '@/components/transitions/BasicTransition.vue'

import { router } from '@inertiajs/vue3'
import { FunctionalComponent, ref, computed } from 'vue'

const $props = defineProps<{
    icon?: FunctionalComponent
    color?: 'brand' | 'danger' | 'transparent'
    type?: 'button' | 'submit' | 'reset'
    alignment?: 'l' | 'c' | 'r'
    size?: 'sm' | 'md' | 'transparent'
    href?: string
    externalLinkOnly?: boolean
    noLoading?: boolean
    disabled?: boolean
    forceLoading?: boolean
    iconOnly?: boolean
}>()
const loading = ref(false)
const clicked = ref(false)

const buttonColor = computed(() => {
    if ($props.disabled) {
        return 'bg-gray-200 dark:bg-neutral-700 text-gray-400 dark:text-neutral-400 cursor-not-allowed'
    }
    switch ($props.color) {
        case 'brand':
            return 'bg-brand-600 hover:bg-brand-700 text-brand-50 dark:text-neutral-200 focus:ring-brand-500 dark:bg-brand-800'
        case 'danger':
            return 'bg-red-50 text-red-700 hover:bg-red-100 hover:dark:bg-red-900 focus:ring-red-500 dark:bg-red-950 dark:text-red-50'
        case 'transparent':
            return 'bg-inherit shadow-none hover:shadow-none focus:ring-none'
        default:
            return 'bg-light-003 hover:bg-gray-50 hover:dark:bg-neutral-700 text-brand-700 hover:bg-dark-003 focus:ring-brand-500 dark:bg-dark-001 dark:text-brand-100'
    }
})

const iconColor = computed(() => {
    if ($props.disabled) {
        return 'text-gray-400'
    }
    switch ($props.color) {
        case 'danger':
            return 'text-red-700 dark:text-red-50'
        case 'brand':
            return 'text-brand-50'
        default:
            return 'text-brand-700 dark:text-brand-100'
    }
})

const textAlignment = computed(() => {
    switch ($props.alignment) {
        case 'l':
            return 'justify-left'
        default:
            return 'justify-center'
    }
})
// NOTE: [sm/default]
const buttonSize = computed(() => {
    switch ($props.size) {
        case 'transparent':
            return 'text-sm p-0'
        case 'sm':
            return 'text-xs font px-3 py-2'
        default:
            return 'text-sm px-4 py-2'
    }
})

// NOTE: Loading Animation
router.on('start', () => {
    if (clicked.value) {
        loading.value = true
    }
})

router.on('finish', () => {
    loading.value = false
    clicked.value = false
})
</script>
