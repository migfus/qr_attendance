<template>
    <div class="flex flex-col gap-2">
        <div class="flex justify-center bg-light-001 dark:bg-dark-002 rounded-t-xl rounded p-4">
            <img :src="form.cropped_image ? form.cropped_image : '/images/avatar.webp'" class="w-62 h-auto rounded-2xl" />
        </div>

        <div class="flex flex-col items-start bg-light-001 dark:bg-dark-002 rounded p-4 dark:text-light-002">
            <p class="font-bold">
                {{ `${form.last_name}, ${form.first_name} ${form.mid_name} ${form.ext_name.name == 'N/A' ? '' : form.ext_name.name}` }}
            </p>
            <p class="line-clamp-2 text-center font-semibold">{{ `${form.department}` }}</p>
            <p class="font-semibold">{{ `${form.position}` }}</p>
            <p class="text-sm">{{ `${form.status.name}` }}</p>
        </div>

        <div v-if="form.claim" class="flex flex-col rounded rounded-b-xl p-4 bg-light-001 dark:bg-dark-002 dark:text-light-002">
            <p class="font-bold">{{ form.claim.name }}</p>
            <p>{{ `${form.claim.price} pesos` }}</p>
            <img :src="form.claim.image_url" />
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { Bars3BottomLeftIcon } from '@heroicons/vue/24/outline'

import { ClaimType, Select } from '@/globalTypes'

defineProps<{
    form: {
        last_name: string
        first_name: string

        mid_name: string
        ext_name: Select
        department: string
        position: string
        status: Select
        claim: ClaimType | undefined
        cropped_image: string
    }
}>()
</script>
