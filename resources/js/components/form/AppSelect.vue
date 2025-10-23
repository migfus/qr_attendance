<template>
    <div>
        <label v-if="!noLabel" class="font-medium text-sm dark:text-light-001">{{ name }}</label>
        <select
            :id="name"
            :name="name"
            :autocomplete="name"
            v-model="$model"
            :class="[
                'block w-full rounded-xl bg-white dark:bg-dark-001 py-2 text-brand-700 dark:text-neutral-300 sm:text-sm font-medium border px-2',
                color == 'danger' ? 'border-red-400 dark:border-red-800' : 'border-brand-200 dark:border-neutral-600'
            ]"
            :disabled
        >
            <option v-for="item in suggestions" :value="item" class="font-medium" :selected="item.name == $model!.name">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script setup lang="ts">
import { Select } from '@/globalTypes'

defineProps<{
    suggestions: Select[]
    name: string
    noLabel?: boolean
    color: '' | 'danger'
    disabled?: boolean
}>()

const $model = defineModel<Select>()
</script>
