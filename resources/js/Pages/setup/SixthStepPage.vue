<template>
    <SetupLayout :current_step="5">
        <BasicCard title="Create form for new users" class="lg:col-start-2" :icon="SquaresPlusIcon">
            <form @submit.prevent="submit()" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <div v-for="(item, idx) in form" :key="item.id" class="bg-dark-003 object-shadow rounded-xl p-4">
                        <AppInput :name="`Input Name (${item.id}) - ${item.type}`" v-model="item.title" noLabel />
                        <div class="flex justify-end gap-2 mt-2">
                            <AppButton>Text</AppButton>
                            <AppButton v-if="form.length > 1" :icon="TrashIcon" type="button" icon_only @click="form.splice(idx, 1)" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <AppButton :icon="PlusIcon" type="button" icon_only @click="show_modal = true" />
                    </div>

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

        <FormModal :icon="PlusIcon" title="Input Type" description="Select input type" v-model="show_modal"> Select Shit </FormModal>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, ArrowRightIcon, InformationCircleIcon, PlusIcon, SquaresPlusIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { ref } from 'vue'
import SetupLayout from './SetupLayout.vue'
import AppInput from '@/components/form/AppInput.vue'
import FormModal from '@/components/modals/FormModal.vue'

const input_types = ['Short Answer', 'Long Answer', 'Single Choice', 'Multi Choice', 'File Upload', 'Date', 'Time', 'Date & Time']
const form = ref([
    {
        id: 1,
        sort: 1,
        title: 'Office / Department',
        type: 'text'
    },
    {
        id: 2,
        sort: 2,
        title: 'Office / Department',
        type: 'text'
    }
])
const show_modal = ref(false)

function submit() {}
</script>
