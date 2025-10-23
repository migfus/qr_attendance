<template>
    <div>
        <BasicCard title="Instructions." description="Instructions" :icon="InformationCircleIcon">
            <div class="flex flex-col gap-2 text-sm font-semibold text-brand-600 dark:text-neutral-400">
                <ul>
                    <li>Please check your email for any updates.</li>
                    <li>In [Summary] card, you can check your info, if there's any typo please update using "Edit" button.</li>
                </ul>

                <!-- <li>[Queue Status] card will represents that someone is waiting to process.</li> -->
            </div>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'

import { InformationCircleIcon } from '@heroicons/vue/24/outline'
</script>
