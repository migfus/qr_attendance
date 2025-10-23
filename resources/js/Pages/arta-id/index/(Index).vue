a
<template>
    <div>
        <Unedited />

        <LoadingSection v-if="loading" class="my-4" />

        <div v-else class="my-4 flex flex-col md:grid grid-cols-6 gap-4">
            <BasicTransition :class="[step <= 1 ? 'lg:col-start-3 col-start-2 col-span-4' : 'lg:col-start-2', 'col-span-3 lg:col-span-2 ']">
                <FirstStep
                    v-if="step == 1"
                    v-model:last_name="form.last_name"
                    v-model:first_name="form.first_name"
                    v-model:mid_name="form.mid_name"
                    v-model:ext_name="form.ext_name"
                    v-model:email="form.email"
                    v-model:step="step"
                    v-model:agree="form.agree"
                    :extra_names
                    @reset="form = init_form"
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
                    @reset="form = init_form"
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
                    :claim_types
                    :email="form.email"
                />
            </BasicTransition>

            <BasicTransition>
                <div v-if="step > 1" class="flex flex-col col-span-3 lg:col-span-2">
                    <SummaryCard :form />
                </div>
            </BasicTransition>
        </div>
    </div>
</template>

<script setup lang="ts">
import FirstStep from './FirstStep.vue'
import SecondStep from './SecondStep.vue'
import ThirdStep from './ThirdStep.vue'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import SummaryCard from './SummaryCard.vue'
import FourthStep from './FourthStep.vue'
import LoadingSection from './LoadingSection.vue'

import { router, usePage } from '@inertiajs/vue3'
import { ClaimType, Select } from '@/globalTypes'
import { useStorage } from '@vueuse/core'
import { ref } from 'vue'
import { base64ToBlob } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const $page = usePage()

const { extra_names, departments, positions, claim_types, statuses } = defineProps<{
    extra_names: Select[]
    departments: Select[]
    positions: Select[]
    statuses: Select[]
    claim_types: ClaimType[]
}>()

const init_form = ref(
    $page.props.base_url == 'http://127.0.0.1:8000'
        ? {
              agree: true,
              last_name: 'Belonio',
              first_name: 'Schwarzenegger',
              mid_name: 'Reposposa',
              ext_name: extra_names[0],
              email: 'migfu20@gmail.com',
              department: 'Accounting Unit',
              position: 'Accountant II',
              status: statuses[0],
              claim: claim_types[0],
              cropped_image: '',
              raw_image: ''
          }
        : {
              agree: false,
              last_name: '',
              first_name: '',
              mid_name: '',
              ext_name: extra_names[0],
              email: '',
              department: '',
              position: '',
              status: {
                  id: 0,
                  name: ''
              },
              claim: claim_types[0],
              cropped_image: '',
              raw_image: ''
          }
)

const form = useStorage<{
    agree: boolean
    last_name: string
    first_name: string
    mid_name: string
    ext_name: Select
    email: string
    department: string
    position: string
    status: Select
    claim: ClaimType
    cropped_image: string
    raw_image: string
}>('arta-id/request-form', init_form.value, sessionStorage)

const step = useStorage<number>('arta-id/step', 1, sessionStorage)
const loading = ref<boolean>(false)

function confirm() {
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.value.cropped_image, 'image/jpg', 'cropped-image.jpg')
    const raw_blob = base64ToBlob(form.value.raw_image, 'image/jpg', 'raw_image.jpg')

    // Append the avatar file (Blob data)
    formData.append('last_name', form.value.last_name)
    formData.append('first_name', form.value.first_name)
    formData.append('mid_name', form.value.mid_name)
    formData.append('ext_name_id', form.value.ext_name.id.toString())
    formData.append('email', form.value.email)
    formData.append('department', form.value.department)
    formData.append('position', form.value.position)
    formData.append('status_id', form.value.status.id.toString())
    formData.append('claim_id', form.value.claim.id.toString() ?? '')
    formData.append('cropped_image', cropped_blob)
    formData.append('raw_image', raw_blob)

    step.value = 1
    form.value = init_form.value
    loading.value = true
    window.scrollTo({ top: 0, behavior: 'smooth' })

    router.post(route('arta-id.store'), formData, {
        preserveState: true,
        headers: { 'Content-Type': 'multipart/form-data' } // Ensure correct content type,
    })
}
</script>
