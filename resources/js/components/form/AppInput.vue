<template>
    <BasicTransition>
        <div class="relative">
            <label v-if="!$props.noLabel" class="block text-sm font-medium leading-6 text-brand-800 dark:text-neutral-200">{{ $props.name }}</label>
            <input
                v-model="$model"
                :id="name"
                :name
                :type="input_type ?? 'text'"
                :placeholder="placeholder ?? ''"
                :class="[
                    inputSize,
                    injectCSS,
                    error && 'border-red-500',
                    '-object-shadow dark:text-light-002 px-3 w-full rounded-2xl placeholder-brand-400 dark:placeholder-neutral-500 focus:outline-hidden focus:ring-brand-500 dark:bg-dark-001 border border-brand-200 dark:border-dark-001'
                ]"
                autocomplete="off"
                @input="onInput()"
            />
            <label v-if="$props.error" class="block text-sm font-medium text-red-600">
                {{ $props.error }}
            </label>

            <button
                v-if="show_pass && type == 'password'"
                class="absolute bg-dark-001 p-2 py-2.5 rounded-xl object-shadow text-sm font-semibold cursor-pointer right-0"
                type="button"
                @click="show_pass = !show_pass"
            >
                <EyeIcon class="size-5" />
            </button>
            <button
                v-else-if="type == 'password'"
                class="absolute bg-dark-001 p-2 py-2.5 rounded-xl object-shadow text-sm font-semibold cursor-pointer right-0"
                type="button"
                @click="show_pass = !show_pass"
            >
                <EyeSlashIcon class="size-5" />
            </button>
        </div>
    </BasicTransition>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

import { computed, ref, watch } from 'vue'

const $props = defineProps<{
    error?: string | undefined
    name: string
    type?: 'text' | 'email' | 'password' | 'date' | 'number'
    placeholder?: string
    size?: 'sm' | 'xs'
    noLabel?: true | false
    injectCSS?: string
    upperCase?: boolean
}>()
const $model = defineModel<string>({ required: true })

const show_pass = ref(false)
const input_type = ref($props.type)

function onInput() {
    if ($props.upperCase) {
        $model.value = $model.value.toUpperCase()
    }
}

const inputSize = computed(() => {
    switch ($props.size) {
        case 'sm':
            return 'text-sm h-[2.5rem]'
        case 'xs':
            return 'text-xs h-[2.5rem]'
        default:
            return 'h-[2.5rem]'
    }
})

watch(show_pass, () => {
    input_type.value = show_pass.value ? 'text' : 'password'
})
</script>
