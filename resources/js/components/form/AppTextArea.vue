<template>
    <div class="col-span-full">
        <label v-if="!noLabel" :for="name" class="block text-sm font-medium leading-6 text-brand-700 dark:text-neutral-200">{{ name }}</label>
        <BasicTransition class="mt-1">
            <textarea
                v-model="$model"
                :id="name"
                :placeholder
                :name="name"
                :rows="lines ?? 3"
                @input="$emit('input')"
                ref="textarea"
                class="px-3 -object-shadow border border-brand-200 block w-full rounded-xl py-1.5 text-gray-900 dark:text-neutral-300 placeholder:text-gray-400 sm:text-sm sm:leading-6 dark:bg-dark-001 dark:border-dark-001"
            />
        </BasicTransition>

        <label class="text-red-500 font-semibold text-sm">{{ error }}</label>
    </div>
</template>

<script setup lang="ts">
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import { useTemplateRef, watch } from 'vue'

const $model = defineModel<string>()

defineProps<{
    name: string
    noLabel?: boolean
    lines?: string
    placeholder?: string
    error?: string
}>()

const textAreaSize = useTemplateRef('textarea')

const $emit = defineEmits(['input'])

watch($model, () => {
    if (textAreaSize.value) {
        textAreaSize.value.style.height = 'auto'
        textAreaSize.value.style = textAreaSize.value.scrollHeight + 'px'
    }
})
</script>
