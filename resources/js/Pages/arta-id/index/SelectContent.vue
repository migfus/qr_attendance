<template>
    <RadioGroup v-model="claim">
        <RadioGroupLabel class="text-base leading-6 text-gray-900 dark:text-neutral-200">Please select for payment.</RadioGroupLabel>
        <div class="my-2 bg-yellow-200 rounded dark:bg-yellow-700 px-4 py-2">
            <b>NOTICE:</b> <br />
            <ul>
                <li>Payment will be thru upon receiving the ID from CMUEAI.</li>
            </ul>
        </div>

        <div class="mt-4 flex flex-col gap-4">
            <RadioGroupOption as="template" v-for="item in claim_types" :key="item.id" :value="item" v-slot="{ active, checked }">
                <div
                    :class="[
                        active || checked
                            ? 'border-brand-600 ring-2 ring-brand-600  dark:bg-brand-800 dark:border-brand-400 bg-brand-100'
                            : 'border-gray-300 dark:bg-dark-001 dark:border-neutral-800',
                        'relative flex cursor-pointer rounded-2xl border bg-white p-4 shadow-sm focus:outline-hidden'
                    ]"
                >
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <RadioGroupLabel as="span" class="block text-sm font-medium text-gray-900 dark:text-neutral-200">
                                {{ item.name }}
                            </RadioGroupLabel>
                            <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-gray-500 dark:text-neutral-300">
                                {{ `${item.price} pesos` }}
                            </RadioGroupDescription>
                            <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-gray-500 justify-center">
                                <img :src="item.image_url" class="object-cover mx-auto" />
                            </RadioGroupDescription>
                            <!-- <RadioGroupDescription as="span" class="mt-6 text-sm font-medium text-gray-900 dark:text-neutral-300">
                                {{ item.users }}
                            </RadioGroupDescription> -->
                        </span>
                    </span>
                    <CheckCircleIcon :class="[!checked ? 'invisible' : '', 'h-5 w-5 text-brand-600 dark:text-neutral-400']" aria-hidden="true" />
                </div>
            </RadioGroupOption>
        </div>
    </RadioGroup>
</template>

<script setup lang="ts">
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckCircleIcon } from '@heroicons/vue/20/solid'

import { ClaimType } from '@/globalTypes'

const claim = defineModel<ClaimType | undefined>()
defineProps<{
    claim_types: ClaimType[]
}>()
</script>
