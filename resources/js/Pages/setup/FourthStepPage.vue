<template>
    <SetupLayout :current_step="4">
        <BasicCard title="Select Version" class="lg:col-start-2" :icon="SquaresPlusIcon">
            <form @submit.prevent="submit()" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <RadioGroup v-model="selected_version">
                        <div class="flex flex-col lg:grid grid-cols-2 gap-2">
                            <RadioGroupOption as="template" v-for="item in select_version" :key="item.name" :value="item.name" v-slot="{ active, checked }">
                                <div
                                    :class="[
                                        active ? '' : 'bg-dark-001',
                                        'relative flex cursor-pointer rounded-lg bg-light-002 dark:bg-dark-001 p-4 object-shadow focus:outline-none '
                                    ]"
                                >
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <RadioGroupLabel as="span" class="block text-sm font-medium text-light-002">{{ item.name }}</RadioGroupLabel>
                                            <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-brand-300">
                                                {{ item.description }}
                                            </RadioGroupDescription>
                                            <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-brand-300">
                                                {{ item.description }}
                                            </RadioGroupDescription>
                                        </span>
                                    </span>
                                    <CheckCircleIcon :class="[!checked ? 'invisible' : '', 'h-5 w-5 text-brand-400']" aria-hidden="true" />
                                    <span
                                        :class="[
                                            active ? 'border' : 'border-2',
                                            checked ? 'border-brand-700' : 'border-transparent',
                                            'pointer-events-none absolute -inset-px rounded-lg'
                                        ]"
                                        aria-hidden="true"
                                    />
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>

                    <div class="flex flex-col gap-2 mt-4">
                        <p class="text-brand-200 font-semibold text-sm flex align-middles gap-2">
                            <InformationCircleIcon class="size-5" />
                            You can change version after the setup.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row-reverse gap-2">
                    <AppButton :icon="ArrowRightIcon">Next</AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('setup.show', { setup: 3 })" type="button">Back</AppButton>
                </div>
            </form>
        </BasicCard>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, ArrowRightIcon, InformationCircleIcon, SquaresPlusIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckCircleIcon } from '@heroicons/vue/20/solid'

import { reactive, ref } from 'vue'
import SetupLayout from './SetupLayout.vue'

const form = reactive(initForm())
const select_version = [
    {
        name: 'Professional',
        description: 'Recommended for Single Team.'
    },
    {
        name: 'Community',
        description: 'Recommended for Multiple Teams.'
    }
]

const selected_version = ref(select_version[0].name)

function initForm() {
    return {
        host: 'localhost',
        port: '3306',
        database: 'db_name',
        username: 'root',
        password: ''
    }
}

function submit() {}
</script>
