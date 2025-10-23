<template>
    <BasicTransition @click="toggle" class="cursor-pointer dark:bg-neutral-800 bg-brand-50 rounded-full p-1">
        <MoonIcon v-if="theme == 'dark'" class="size-7" />

        <SunIcon v-else class="size-7" />
    </BasicTransition>
</template>

<script setup lang="ts">
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline'
import { useLocalStorage } from '@vueuse/core'
import BasicTransition from '../transitions/BasicTransition.vue'

const theme = useLocalStorage('theme', 'Light')

function toggle() {
    // if (theme.value == 'Dark') {
    //     theme.value = 'Light'
    //     document.documentElement.classList.remove('dark')
    // } else {
    //     theme.value = 'Dark'
    //     document.documentElement.classList.add('dark')
    // }
}
</script>
