<template>
    <div class="flex flex-col gap-4 sm:grid grid-cols-1 lg:grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <div class="flex flex-col col-span-2 order-2 lg:order-1">
            <BasicCard title="Edit Information" description="Edit the information" :icon="PencilIcon">
                <form @submit.prevent="updateQRCode()" class="flex flex-col gap-2">
                    <AppInput name="Name" v-model="form.name" />
                    <ComboBox :data="departments" v-model="form.selected_office" name="Office" />
                    <ComboBox :data="employee_statuses" v-model="form.selected_status" name="Status" />

                    <div class="flex mt-4 flex-col gap-2 sm:flex-row justify-end">
                        <AppButton
                            @click="show_modal = true"
                            :icon="TrashIcon"
                            color="danger"
                            type="button"
                            :disabled="!checkPermissions(['Guest QR/Delete/All'], $page.props.auth?.permissions)"
                        >
                            Delete
                        </AppButton>
                        <AppButton @click="Object.assign(form, initForm())" :icon="ArrowPathIcon" type="button">Reset</AppButton>
                        <AppButton color="brand" :icon="CircleStackIcon" :disabled="!checkPermissions(['Guest QR/Update/All'], $page.props.auth?.permissions)">
                            Update
                        </AppButton>
                        <AppButton :href="route('dashboard.guest-qr.index')" :icon="ArrowLeftIcon" type="button">Back</AppButton>
                    </div>
                </form>
            </BasicCard>
        </div>

        <div class="flex order-1 justify-center flex-col gap-2 items-center w-64 mx-auto lg:mx-0">
            <QRCode
                :id="show_data.id"
                :employee_name="form.name"
                :selected_office="form.selected_office"
                :status="form.selected_status"
                noLabel
                noImage
                noRemove
            />
            <QRCode :id="show_data.id" :employee_name="form.name" :selected_office="form.selected_office" :status="form.selected_status" noRemove />
        </div>

        <RemovalPrompt title="Delete this QR?" :confirmIcon="TrashIcon" confirmMessage="Yes, Delete!" v-model="show_modal" @confirm="deleteQrCode()">
            <li>This QR will be removed permanently</li>
            <li>Scanning will no longer work.</li>
            <li>The action cannot be undone.</li>
        </RemovalPrompt>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import { ArrowLeftIcon, ArrowPathIcon, CircleStackIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import ComboBox from '@/components/form/ComboBox.vue'
import Unedited from '@/components/test/Unedited.vue'

import AppButton from '@/components/form/AppButton.vue'
import QRCode from '@/components/cards/QRCode.vue'
import RemovalPrompt from '@/components/modals/RemovalPrompt.vue'

import { Select } from '@/globalTypes'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { checkPermissions } from '@/utils'

interface EmployeeData {
    name: string
    department: Select
    status: Select
    id: string
}

const $props = defineProps<{
    employee_statuses: Select[]
    departments: Select[]
    show_data: EmployeeData
}>()

const form = reactive(initForm())
const show_modal = ref(false)

function initForm() {
    return {
        name: $props.show_data.name,
        selected_office: $props.show_data.department,
        selected_status: $props.show_data.status
    }
}

function updateQRCode() {
    router.put(route('dashboard.guest-qr.update', { guest_qr: $props.show_data.id }), {
        name: form.name,
        office_id: form.selected_office.id,
        status_id: form.selected_status.id
    })
}

function deleteQrCode() {
    router.delete(route('dashboard.guest-qr.destroy', { guest_qr: $props.show_data.id }))
}
</script>
