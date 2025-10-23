<template>
    <div :class="['py-4 justify-center gap-4', guest_qrs.length > 0 ? 'grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3' : 'grid sm:flex grid-cols-1']">
        <Unedited />

        <div class="col-span-3 lg:col-span-1 xl:col-span-1">
            <BasicCard title="Generate QR Attendance" description="Generate QR Attendance" :icon="QrCodeIcon" :size="guest_qrs.length > 0 ? 'lg' : 'sm'">
                <form @submit.prevent="storeQrCode()">
                    <div class="flex flex-col font-medium text-brand-700">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="flex flex-col gap-4">
                                <AppInput name="Name" v-model="form.employee_name" placeholder="Last, First MI. Ext." uppercase />
                                <ComboBox :data="departments" v-model="form.selected_office" name="Office" />
                                <ComboBox :data="employee_statuses" v-model="form.status" name="Status" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <AppButton type="button" :icon="ArrowPathIcon" @click="Object.assign(form, initForm())">Reset</AppButton>
                        <AppButton color="brand" :icon="QrCodeIcon" :disabled="form.employee_name == '' || loading" :forceLoading="loading">Generate</AppButton>
                    </div>
                </form>
            </BasicCard>
        </div>

        <div v-if="guest_qrs.length > 0" class="col-span-3 lg:col-span-1 xl:col-span-2" ref="scrollTo">
            <BasicCard title="Saved QR" description="Generate QR Attendance" :icon="ClipboardIcon">
                <div class="flex flex-col justify-between">
                    <div class="flex flex-col font-medium text-brand-700">
                        <DataTransition class="flex gap-4 flex-wrap">
                            <QRCode
                                v-for="(row, idx) in guest_qrs"
                                :key="idx"
                                :employee_name="row.name"
                                :selected_office="row.department"
                                :status="row.status"
                                :id="row.id"
                                @remove="removeQrCode(row.id, idx)"
                                class="h-auto w-[253px]"
                            />
                        </DataTransition>
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <AppButton :icon="XMarkIcon" @click="resetGuestId()">Clear All</AppButton>
                    </div>
                </div>
            </BasicCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from '@/components/form/AppInput.vue'
import ComboBox from '@/components/form/ComboBox.vue'
import BasicCard from '@/components/cards/BasicCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import QRCode from '@/components/cards/QRCode.vue'

import { onMounted, reactive, ref } from 'vue'
import { Select } from '@/globalTypes'
import AppButton from '@/components/form/AppButton.vue'
import { ArrowPathIcon, ClipboardIcon, QrCodeIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'
import { ulid } from 'ulid'
import Unedited from '@/components/test/Unedited.vue'

interface EmployeeData {
    employee_name: string
    selected_office: Select
    status: Select
}

defineProps<{
    employee_statuses: Select[]
    departments: Select[]
}>()

interface GuestQr {
    id: string
    name: string
    department: {
        name: string
    }
    status: {
        name: string
    }
}

const guest_qrs = ref<GuestQr[]>([])
const scrollTo = ref<HTMLElement>()
const loading = ref<boolean>(false)

const form = reactive<EmployeeData>(initForm())

function resetGuestId() {
    localStorage.setItem('guest_id', ulid())
    getQRCode()
}

async function getQRCode() {
    try {
        const { data } = await axios.get('/api/guest-qr', {
            params: { guest_id: localStorage.getItem('guest_id') }
        })
        guest_qrs.value = data
    } catch (err) {
        // alert(JSON.stringify(err))
    }
}

async function storeQrCode() {
    loading.value = true
    try {
        const { data } = await axios.post('/api/guest-qr', {
            guest_id: localStorage.getItem('guest_id'),
            name: form.employee_name,
            department_id: form.selected_office.id,
            status_id: form.status.id
        })

        guest_qrs.value.unshift({
            id: data.id,
            name: data.name,
            department: data.department,
            status: data.status
        })
        Object.assign(form, initForm())
        scrollToNewQr()
    } catch (err) {
        // alert(JSON.stringify(err))
    }
    loading.value = false
}

async function removeQrCode(id: string, idx: number) {
    try {
        await axios.delete(`/api/guest-qr/${id}`)
        guest_qrs.value.splice(idx, 1)
    } catch (err) {
        guest_qrs.value.splice(idx, 1)
    }
}

function generateQR() {
    if (!localStorage.getItem('guest_id')) {
        localStorage.setItem('guest_id', ulid())
    }
}

function scrollToNewQr() {
    scrollTo.value!.scrollIntoView({
        behavior: 'smooth'
    })
}

function initForm() {
    return {
        employee_name: '',
        selected_office: {
            id: 1,
            name: 'College of Agriculture'
        },
        status: {
            id: 1,
            name: 'Permanent'
        }
    }
}

onMounted(() => {
    generateQR()
    getQRCode()
})
</script>
