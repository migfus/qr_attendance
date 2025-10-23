<template>
    <div>
        <BasicCard title="Request ARTA ID" description="Online Request ARTA ID" :icon="IdentificationIcon">
            <form @submit.prevent="$emit('confirm')">
                <div class="flex flex-col gap-3">
                    <SelectContent v-model="claim" :claim_types />
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="ArrowLeftIcon" type="button" @click="$emit('back')">Back</AppButton>
                    <AppButton color="brand" :icon="CheckIcon" :disabled="claim == undefined">Confirm</AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import { IdentificationIcon, CheckIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import SelectContent from './SelectContent.vue'

import { ClaimType } from '@/globalTypes'

const claim = defineModel<ClaimType | undefined>('claim')

defineProps<{
    claim_types: ClaimType[]
    email: string
}>()
const $emit = defineEmits(['confirm', 'back'])
</script>
