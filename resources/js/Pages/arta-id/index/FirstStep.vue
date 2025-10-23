<template>
    <div>
        <BasicCard title="Request ARTA ID" description="Online Request ARTA ID" :icon="IdentificationIcon">
            <div class="flex">
                <label class="my-2">Fill up your name.</label>
            </div>
            <form @submit.prevent="verify()">
                <div class="flex flex-col gap-3">
                    <AppInput v-model="last_name" name="Surname (last name)" :upperCase="true" :error="errors?.last_name" />
                    <AppInput v-model="first_name" name="Given Name (first name)" :upperCase="true" :error="errors?.first_name" />
                    <AppInput v-model="mid_name" name="Middle Initial (if available)" :upperCase="true" />
                    <ComboBox v-model="ext_name" :data="extra_names" name="Extra Name (if available)" :upperCase="true" :error="errors?.ext_name_id" />
                    <AppInput v-model="email" name="Email (for updates)" type="email" :error="errors?.email" />

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                @click="modal_agree = true"
                                :checked="agree"
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 accent-light-003"
                            />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-neutral-300">Agree Terms & Conditions</label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="ArrowPathIcon" type="button" @click="$emit('reset')">Reset</AppButton>
                    <AppButton color="brand" :icon="ArrowRightIcon" :forceLoading="loading" :disabled="!agree">Next</AppButton>
                </div>
            </form>
        </BasicCard>

        <TermsConditionModal v-model="modal_agree" @confirm="agree = true" @disagree="agree = false" />
    </div>
</template>

<script setup lang="ts">
import { IdentificationIcon, ArrowPathIcon, ArrowRightIcon, CheckIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import ComboBox from '@/components/form/ComboBox.vue'
import AppButton from '@/components/form/AppButton.vue'
import TermsConditionModal from '@/components/modals/TermsConditionModal.vue'

import { ref } from 'vue'
import axios from 'axios'
import { Select } from '@/globalTypes'

const last_name = defineModel<string>('last_name', { required: true })
const first_name = defineModel<string>('first_name', { required: true })
const mid_name = defineModel<string>('mid_name', { required: true })
const ext_name = defineModel<Select>('ext_name', { required: true })
const email = defineModel<string>('email', { required: true })
const agree = defineModel<boolean>('agree', { required: true })
const step = defineModel<number>('step')

const modal_agree = ref<boolean>(false)

const $emit = defineEmits(['reset'])

defineProps<{
    extra_names: Select[]
}>()

const errors = ref<{
    last_name: string
    first_name: string
    mid_name: string
    email: string
    ext_name_id: string
} | null>(null)

const loading = ref(false)

async function verify() {
    loading.value = true
    try {
        const { data } = await axios.post('/api/arta-id-verify', {
            step: 1,
            last_name: last_name.value,
            first_name: first_name.value,
            mid_name: mid_name.value,
            ext_name_id: ext_name.value.id,
            email: email.value
        })
        if (data) {
            step.value = 2
        }
    } catch (err: any) {
        errors.value = err.response.data.errors
    }
    loading.value = false
}
</script>
