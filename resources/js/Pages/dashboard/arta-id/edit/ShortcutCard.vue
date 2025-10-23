<template>
    <BasicCard title="Keyboard shortcuts" description="Tools" :icon="ComputerDesktopIcon">
        <div class="flex flex-col justify-between gap-1 h-full w-full text-sm ml-4">
            <li class="font-semibold">Del - Delete Layer</li>
            <li class="font-semibold">←↑→↓ - Move Layer</li>
            <li class="font-semibold">Ctrl + O - Import JSON</li>
        </div>
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ComputerDesktopIcon } from '@heroicons/vue/24/outline'
</script>
