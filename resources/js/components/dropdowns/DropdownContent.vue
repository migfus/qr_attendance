<template>
    <MenuItem v-slot="{ active }">
        <Link v-if="href" :href :class="[active ? 'bg-brand-400 text-white shadow' : 'text-gray-900', 'group flex w-full items-center rounded-xl px-2 py-2 text-sm font-medium']">
            <component v-if="icon" :is="icon" :class="[active ? 'text-white' : 'text-gray-500', 'mr-2 h-4 w-4']" aria-hidden="true" />
            <div v-if="icon_html" v-html="icon_html" :class="[active ? 'text-white' : 'text-gray-500', 'mr-2 h-4 w-4']" aria-hidden="true"></div>
            <slot></slot>
        </Link>

        <button
            v-else-if="danger"
            :class="[active ? 'bg-red-200 shadow' : '', 'text-red-700 group flex w-full items-center rounded-xl px-2 py-2 text-sm font-medium transition-all']"
            @click="$emit('selected')"
        >
            <component v-if="icon" :is="icon" :class="[active ? 'text-white' : 'text-gray-500', 'mr-2 h-4 w-4']" aria-hidden="true" />
            <div v-if="icon_html" v-html="icon_html" :class="[active ? 'text-red-500' : 'text-red-500', 'mr-2 h-4 w-4']" aria-hidden="true"></div>
            <slot></slot>
        </button>

        <button v-else :class="[active ? 'bg-brand-400 text-white shadow' : 'text-gray-900', 'group flex w-full items-center rounded-xl px-2 py-2 text-sm font-medium']" @click="$emit('selected')">
            <component v-if="icon" :is="icon" :class="[active ? 'text-white' : 'text-gray-500', 'mr-2 h-4 w-4']" aria-hidden="true" />
            <div v-if="icon_html" v-html="icon_html" :class="[active ? 'text-white' : 'text-gray-500', 'mr-2 h-4 w-4']" aria-hidden="true"></div>
            <slot></slot>
        </button>
    </MenuItem>
</template>

<script setup lang="ts">
import { MenuItem } from "@headlessui/vue"
import { FunctionalComponent } from "vue"

defineProps<{
    icon?: FunctionalComponent
    icon_html?: string
    danger?: boolean
    href?: string
}>()

const $emit = defineEmits(["selected"])
</script>
