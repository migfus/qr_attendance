<template>
    <SetupLayout :current_step="3">
        <BasicCard title="Check for Requirements" class="lg:col-start-2" :icon="CheckCircleIcon">
            <form @submit.prevent="submit()" class="flex flex-col gap-2">
                <div class="lg:grid grid-cols-2 gap-2 flex flex-col">
                    <div class="flex justify-between gap-2 object-shadow p-4 bg-light-002 dark:bg-dark-001 rounded-xl col-span-2">
                        <div class="text-dark-003 dark:text-light-003 font-semibold">PHP</div>
                        <div class="flex gap-2 items-center">
                            <p class="text-green-700 dark:text-green-400 font-semibold">
                                {{ php_requirements.min_php_version }} < {{ php_requirements.php_version }}
                            </p>
                            <CheckCircleIcon v-if="php_requirements.php_version_ok" class="size-5 text-green-700 dark:text-green-400" />
                            <XCircleIcon v-else class="size-5 text-red-400" />
                        </div>
                    </div>

                    <div
                        v-for="item in php_requirements.extensions"
                        :key="item.name"
                        class="flex justify-between gap-2 object-shadow p-4 bg-light-002 dark:bg-dark-001 rounded-xl items-center"
                    >
                        <div class="text-green-700 dark:text-brand-200 font-semibold">{{ item.name }}</div>

                        <CheckCircleIcon v-if="item.loaded" class="size-5 text-green-700 dark:text-green-400" />
                        <div v-else class="flex gap-2">
                            <AppButton
                                :icon="XCircleIcon"
                                color="danger"
                                size="sm"
                                type="button"
                                :href="`https://www.google.com/search?q=php+install+${item.name}+extension`"
                                externalLinkOnly
                            >
                                Resolve
                            </AppButton>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row-reverse gap-2 mt-4">
                    <AppButton :icon="ArrowRightIcon">Next</AppButton>
                    <AppButton :icon="ArrowPathIcon" type="button" :href="route('setup.show', { setup: 3 })">Check Again</AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('setup.show', { setup: 2 })" type="button">Back</AppButton>
                </div>
            </form>
        </BasicCard>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, ArrowPathIcon, ArrowRightIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { PhpRequirements } from './setupInterfaces'
import SetupLayout from './SetupLayout.vue'

defineProps<{
    php_requirements: PhpRequirements
}>()

function submit() {}
</script>
