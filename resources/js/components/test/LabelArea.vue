<template>
    <div class="bg-yellow-800 text-yellow-200 p-4 rounded-xl flex gap-2 items-center font-semibold">
        <InformationCircleIcon class="size-5" />
        <slot />
    </div>
</template>

<script setup lang="ts">
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
</script>
