<template>
    <div class="max-w-7xl mx-auto">
        <Unedited />

        <div :class="['py-4 justify-center gap-4', index_data.data.length > 0 ? 'grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3' : 'grid sm:flex grid-cols-1']">
            <div class="col-span-3 lg:col-span-1 xl:col-span-1">
                <BasicCard
                    title="Generate QR Attendance"
                    description="Generate QR Attendance"
                    :icon="QrCodeIcon"
                    :size="index_data.data.length > 0 ? 'lg' : 'sm'"
                >
                    <form @submit.prevent="storeNewQR()">
                        <div class="flex flex-col font-medium text-brand-700">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex flex-col gap-4">
                                    <AppInput
                                        name="Name"
                                        v-model="form.employee_name"
                                        placeholder="Surname, Given Name MI. Ext."
                                        upperCase
                                        :error="$page.props.errors.name"
                                    />
                                    <ComboBox :data="departments" v-model="form.selected_office" :is_department="true" name="Department" />
                                    <ComboBox :data="employee_statuses" v-model="form.status" name="Status" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4 gap-2">
                            <AppButton type="button" :icon="ArrowPathIcon" @click="Object.assign(form, initForm())">Reset</AppButton>
                            <AppButton color="brand" :icon="QrCodeIcon" :disabled="form.employee_name == ''">Generate</AppButton>
                        </div>
                    </form>
                </BasicCard>
            </div>

            <div v-if="index_data.data.length > 0" class="col-span-3 lg:col-span-1 xl:col-span-2">
                <BasicCard title="Saved QR" description="Generate QR Attendance" :icon="ClipboardIcon">
                    <div class="flex flex-col justify-between">
                        <div class="flex flex-col font-medium text-brand-700">
                            <DataTransition class="flex gap-4 flex-wrap">
                                <div v-for="row in index_data.data" :key="row.id" class="relative">
                                    <QRCode
                                        :employee_name="row.name"
                                        :selected_office="row.department"
                                        :status="row.status"
                                        :id="row.id"
                                        @remove="destroy(row.id)"
                                        class="h-auto w-[253px]"
                                    />
                                </div>
                            </DataTransition>
                        </div>
                        <div class="flex justify-end mt-4 gap-2">
                            <AppButton :icon="XMarkIcon" @click="clearAll()">Clear All</AppButton>
                        </div>
                    </div>
                </BasicCard>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from '@/components/form/AppInput.vue'
import ComboBox from '@/components/form/ComboBox.vue'
import BasicCard from '@/components/cards/BasicCard.vue'
import DataTransition from '@/components/transitions/DataTransition.vue'
import QRCode from '@/components/cards/QRCode.vue'

import { Pagination, Select } from '@/globalTypes'
import { GuestQR } from '@/Pages/dashboard/guest-qr/guestQrInt'

import AppButton from '@/components/form/AppButton.vue'
import { ArrowPathIcon, ClipboardIcon, QrCodeIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import Unedited from '@/components/test/Unedited.vue'

interface EmployeeData {
    employee_name: string
    selected_office: Select
    status: Select
}

defineProps<{
    employee_statuses: Select[]
    departments: Select[]
    index_data: Pagination<GuestQR>
}>()

const form = reactive<EmployeeData>(initForm())

function clearAll() {
    router.post(route('guest-qr-codes.store'), { type: 'clear-all' })
}

async function storeNewQR() {
    router.post(route('guest-qr-codes.store'), {
        name: form.employee_name,
        department_id: form.selected_office.id,
        status_id: form.status.id,
        type: 'new'
    })
    form.employee_name = ''
}

async function destroy(id: string) {
    router.delete(route('guest-qr-codes.destroy', { guest_qr_code: id }))
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
</script>
