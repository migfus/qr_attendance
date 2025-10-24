<template>
    <SetupLayout :current_step="5">
        <BasicCard title="Create super admin account" :icon="UserIcon" class="flex flex-col gap-4 lg:col-start-2">
            <form @submit.prevent="submit()">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col gap-2">
                        <AppInput v-model="form.email" name="Email" />
                        <AppInput v-model="form.password" name="Password" type="password" />
                        <AppInput v-model="form.confirm_password" name="Confirm Password" type="password" />

                        <InformationLabel class="my-4"> This account will manage the entire website. </InformationLabel>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-2">
                        <AppButton :icon="ArrowRightIcon">Next</AppButton>
                        <AppButton :icon="ArrowLeftIcon" :href="route('setup.show', { setup: 4 })" type="button">Back</AppButton>
                    </div>
                </div>
            </form>
        </BasicCard>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, ArrowRightIcon, UserIcon } from '@heroicons/vue/24/outline'
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'
import SetupLayout from './SetupLayout.vue'

import { reactive } from 'vue'
import InformationLabel from '@/components/info/InformationLabel.vue'

const form = reactive(initForm())

function initForm() {
    return {
        email: '',
        password: '',
        confirm_password: ''
    }
}

function submit() {}
</script>
