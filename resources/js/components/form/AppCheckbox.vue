<template>
    <div class="flex items-center">
        <input
            v-model="$model"
            :id="`${name}-id`"
            :name
            type="checkbox"
            class="size-5 border-gray-300 text-brand-600 focus:ring-brand-500 accent-brand-500 dark:accent-brand-500 rounded-xl dark:bg-red-500"
            :checked
            @change="$emit('change')"
        />
        <label v-if="!no_label" :for="`${name}-id`" class="ml-2 block text-sm text-gray-900 dark:text-neutral-300 font-semibold">{{ name }}</label>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    name: string
    no_label?: boolean
    checked?: boolean
    intermediate?: boolean
}>()
const $model = defineModel<boolean>()
const $emit = defineEmits(['change'])
</script>
