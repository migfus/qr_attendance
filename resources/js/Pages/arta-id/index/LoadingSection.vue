<template>
    <div class="flex flex-col gap-4 lg:grid grid-cols-3">
        <div class="lg:col-span-2 flex flex-col gap-4">
            <LoadingCard />
            <LoadingCard />
        </div>

        <div>
            <LoadingCard />
        </div>
    </div>
</template>

<script setup lang="ts">
import LoadingCard from './LoadingCard.vue'
</script>
