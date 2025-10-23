<template>
    <div class="my-4 flex flex-col md:grid grid-cols-6 gap-4">
        <BasicTransition :class="['lg:col-start-2 col-start-2 col-span-4', 'col-span-3 lg:col-span-2 ']">
            <FirstStep
                v-if="step == 1"
                :extra_names
                v-model:last_name="form.last_name"
                v-model:first_name="form.first_name"
                v-model:mid_name="form.mid_name"
                v-model:ext_name="form.ext_name"
                v-model:email="form.email"
                v-model:step="step"
                v-model:agree="form.agree"
                @reset="Object.assign(form, toRaw(init_form))"
            />
            <SecondStep
                v-else-if="step == 2"
                v-model:department="form.department"
                v-model:position="form.position"
                v-model:status="form.status"
                v-model:step="step"
                :positions
                :departments
                :statuses
                @reset="Object.assign(form, toRaw(init_form))"
                @back="step = step - 1"
            />
            <ThirdStep
                v-else-if="step == 3"
                v-model:image="form.cropped_image"
                v-model:raw_image="form.raw_image"
                v-model:step="step"
                @back="step = step - 1"
            />
            <FourthStep
                v-else-if="step == 4"
                v-model:claim="form.claim"
                v-model:step="step"
                @confirm="confirm()"
                @back="step = step - 1"
                :claim_types="claim_types"
                :email="form.email"
            />
        </BasicTransition>

        <div class="flex flex-col col-span-4 lg:col-span-2 col-start-2">
            <SummaryCard :form />
        </div>
    </div>
</template>

<script setup lang="ts">
import SummaryCard from '../index/SummaryCard.vue'
import FirstStep from '../index/FirstStep.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import SecondStep from '../index/SecondStep.vue'
import ThirdStep from '../index/ThirdStep.vue'
import FourthStep from '../index/FourthStep.vue'

import { router } from '@inertiajs/vue3'
import { reactive, ref, toRaw } from 'vue'
import { Employee, Select, ClaimType } from '@/globalTypes'
import { base64ToBlob } from '@/utils'

const { edit_data, extra_names } = defineProps<{
    edit_data: Employee
    positions: Select[]
    departments: Select[]
    statuses: Select[]
    extra_names: Select[]
    claim_types: ClaimType[]
}>()

const step = ref(1)

const init_form = {
    id: edit_data.id,
    last_name: edit_data.last_name,
    first_name: edit_data.first_name,
    mid_name: edit_data.mid_name,
    ext_name: edit_data.extra_name,
    email: edit_data.email,
    department: edit_data.department.name,
    position: edit_data.position.name,
    status: edit_data.employee_status,
    claim: edit_data.claim_type,
    raw_image: edit_data.files[1].file_url ?? '',
    agree: false,
    cropped_image: edit_data.files[edit_data.files.length - 2].thumbnail_url ?? ''
}
const form = reactive<{
    id: string
    last_name: string
    first_name: string
    mid_name: string
    ext_name: Select
    email: string
    department: string
    position: string
    status: Select
    claim: ClaimType
    raw_image: string
    agree: boolean
    cropped_image: string
}>({ ...init_form })

function confirm() {
    const queryParams = new URLSearchParams(window.location.search)
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.cropped_image, 'image/jpg', 'cropped-image.jpg')
    const raw_blob = base64ToBlob(form.raw_image, 'image/jpg', 'raw-image.jpg')

    // Append the avatar file (Blob data)
    formData.append('last_name', form.last_name)
    formData.append('first_name', form.first_name)
    formData.append('mid_name', form.mid_name)
    formData.append('ext_name_id', form.ext_name.id.toString())
    formData.append('email', form.email)
    formData.append('department', form.department)
    formData.append('position', form.position)
    formData.append('status_id', form.status.id.toString())
    formData.append('claim_id', form.claim.id.toString() ?? '')
    if (form.cropped_image != edit_data.files[edit_data.files.length - 2].thumbnail_url) {
        formData.append('cropped_image', cropped_blob)
        formData.append('raw_image', raw_blob)
    }
    formData.append('reason', queryParams.get('reason') ?? '')
    formData.append('_method', 'PUT')

    router.post(route('arta-id.update', { arta_id: edit_data.id }), formData, {
        preserveState: true,
        headers: { 'Content-Type': 'multipart/form-data' } // Ensure correct content type,
    })
}
</script>
