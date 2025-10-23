<template>
    <div>
        <BasicCard title="Queue Status" description="Queue status" :icon="TicketIcon">
            <div>3 are still waiting...</div>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'

import { TicketIcon } from '@heroicons/vue/24/outline'
</script>
