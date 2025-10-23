<template>
    <div>
        <BasicCard title="Request ARTA ID" description="Online Request ARTA ID" :icon="IdentificationIcon">
            <div class="flex">
                <label class="my-2">For your additional ARTA ID information.</label>
            </div>
            <form @submit.prevent="verify()">
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <AppSuggestInput
                            v-model="department"
                            name="Office/College/Unit"
                            ref_name="departmentRef"
                            :suggestions="departments"
                            :errors="errors?.department"
                            :key="1"
                        />
                    </div>

                    <div class="flex flex-col">
                        <AppSuggestInput
                            ref_name="positionRef"
                            @search="searchPositions"
                            v-model="position"
                            name="Position"
                            :suggestions="positions"
                            :errors="errors?.position"
                            :key="2"
                        />
                    </div>

                    <ComboBox v-model="status" :data="statuses" name="Employment Status" :errors="errors?.status_id" />
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="ArrowLeftIcon" type="button" @click="$emit('back')">Back</AppButton>
                    <AppButton color="brand" :icon="ArrowRightIcon" :forceLoading="loading">Next</AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import { Select } from '@/globalTypes'
import { IdentificationIcon, ArrowRightIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'
import ComboBox from '@/components/form/ComboBox.vue'
import AppButton from '@/components/form/AppButton.vue'
import AppSuggestInput from '@/components/form/AppSuggestInput.vue'

import { ref } from 'vue'
import axios from 'axios'

const department = defineModel<string>('department')
const position = defineModel<string>('position')
const status = defineModel<Select>('status')
const step = defineModel<number>('step')

const $emit = defineEmits(['back'])

const { positions, departments } = defineProps<{
    positions: Select[]
    departments: Select[]
    statuses: Select[]
}>()

const errors = ref<{
    department: string[]
    position: string[]
    status_id: string[]
} | null>(null)
const loading = ref(false)

async function verify() {
    loading.value = true
    try {
        const { data } = await axios.post('/api/arta-id-verify', {
            step: 2,
            position: position.value,
            department: department.value,
            status_id: status.value?.id
        })
        if (data) {
            step.value = 3
        }
    } catch (err: any) {
        errors.value = err.response.data.errors
    }
    loading.value = false
}

// For suggestions
const departmentSuggestions = ref<String[]>([])
const positionSuggestions = ref<String[]>([])

function searchDepartments(event: { query: string }) {
    departmentSuggestions.value = departments
        .filter((d) => {
            const q = event.query.toLowerCase()
            if (d.short_name) {
                return d.name.toLowerCase().includes(q) || d.short_name.toLowerCase().includes(q)
            } else {
                return d.name.toLowerCase().includes(q)
            }
        })
        .map((item) => item.name)
}
function searchPositions(event: { query: string }) {
    positionSuggestions.value = positions.filter((p) => p.name.toLowerCase().includes(event.query.toLowerCase())).map((item) => item.name)
}
</script>

<style>
.p-autocomplete-input {
    background-color: #262626 !important;
    border-radius: 12px 0px 0px 12px !important;
}

.p-autocomplete-dropdown {
    border-radius: 0px 12px 12px 0px !important;
}

.p-autocomplete-input {
    background-color: white;
    border-radius: 15px;
    height: 2.3rem;
}

.p-autocomplete-dropdown {
    background-color: white;
}
</style>
