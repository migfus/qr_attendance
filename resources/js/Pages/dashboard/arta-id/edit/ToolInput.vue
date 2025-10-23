<template>
    <div class="flex item-center gap-1">
        <span class="text-sm text-neutral-400 w-4 font-semibold pt-[5x]">{{ pre_fix }}</span>

        <input
            v-model="$model"
            @input="$emit('input')"
            type="number"
            class="h-6 dark:bg-neutral-800 rounded-lg text-sm border-brand-500 ring ring-brand-200 dark:ring-neutral-600 dark:border-neutral-800 w-[100px] px-2"
        />
    </div>
</template>

<script setup lang="ts">
defineProps<{
    pre_fix: string
}>()

const $model = defineModel()
const $emit = defineEmits(['input'])
</script>
