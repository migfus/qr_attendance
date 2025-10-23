<template>
    <div>
        <BasicCard title="Request Status" description="Summary" :icon="ClockIcon">
            <p>Summary</p>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import { ClockIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'
</script>
