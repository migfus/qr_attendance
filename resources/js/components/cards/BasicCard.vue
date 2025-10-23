<template>
    <BasicTransition>
        <div :class="[size_, 'bg-white dark:bg-dark-002 p-6 sm:rounded-xl transition-all object-shadow']">
            <!-- NOTE: BASIC CARD HEADER -->
            <div>
                <div class="flex justify-between">
                    <h3 class="text-base font-semibold leading-7 text-brand-900 dark:text-light-001 truncate">
                        <component v-if="icon" :is="icon" class="text-sm text-brand-700 dark:text-brand-50 h-4 w-4 inline mr-3 mb-[3px] align-middle" />
                        <img v-else-if="iconImg" :src="iconImg" class="inline mr-2 w-6 h-6 rounded shadow" />
                        <div v-else class="inline-block h-4 w-4 pt-[2px] text-brand-700 mr-2" v-html="iconHtml"></div>
                        <span>{{ title }} </span>
                    </h3>
                    <!-- <div class="pt-1.5 px-1 cursor-pointer hover:bg-white dark:hover:bg-neutral-800 rounded-xl group/expand" @click="collapse = !collapse">
                        <MinusIcon v-if="!collapse" class="h-4 w-4 text-brand-800 dark:text-neutral-200" />
                        <div v-else class="flex">
                            <div v-if="count" class="bg-white group-hover/expand:bg-gray-100 rounded-full mr-2 text-brand-500 -mt-1 px-2 shadow">
                                {{ count }}
                            </div>
                            <StopIcon class="h-4 w-4 text-brand-800 dark:text-neutral-300" />
                        </div>
                    </div> -->
                </div>
                <!-- <p
                    v-if="description && !collapse && (page.props.auth?.user_settings.card.show_description ?? false)"
                    class="mt-1 mb-1 max-w-2xl text-sm leading-6 text-gray-500 dark:text-brand-200"
                >
                    {{ description }}
                </p> -->
            </div>
            <!-- NOTE: BASIC CARD CONTENTS -->
            <div v-if="!collapse" class="mt-2 transition-all dark:text-neutral-200">
                <slot></slot>
            </div>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { StopIcon, MinusIcon } from '@heroicons/vue/24/outline'

import { computed, FunctionalComponent, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const $props = defineProps<{
    icon?: FunctionalComponent
    iconHtml?: string
    iconImg?: string
    title: string
    // description: string
    size?: 'lg' | 'sm'
    enableSearch?: boolean
    count?: number
    collapse?: boolean
}>()

const collapse = ref($props.collapse ?? false)
const page = usePage()

const size_ = computed(() => {
    switch ($props.size) {
        case 'sm':
            return 'p-6 sm:w-96 w-full'
        default:
            return 'p-6 sm:min-w-full'
    }
})
</script>

<style scoped>
.loading-card {
    position: relative;
}

@property --angle {
    syntax: '<angle>';
    initial-value: 0deg;
    inherits: false;
}

.loading-card::after,
.card::before {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    top: 50%;
    left: 50%;
    translate: -50% -50%;
    z-index: -1;
    padding: 4px;
    border-radius: 15px;
    background-image: conic-gradient(from var(--angle), transparent 70%, #00ff99);
    animation: 3s spin linear infinite;
}
.loading-card::before {
    filter: blur(1.5rem);
    opacity: 0.5;
}

@keyframes spin {
    from {
        --angle: 0deg;
    }
    to {
        --angle: 360deg;
    }
}
</style>
