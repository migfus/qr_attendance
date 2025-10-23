<template>
    <div class="flex flex-col gap-4 xl:grid grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <div class="flex flex-col gap-2 col-start-2">
            <BasicCard title="Generate QR Code" description="Edit the information" :icon="PencilIcon" class="">
                <form @submit.prevent="createQRCode()" class="flex flex-col gap-2">
                    <AppInput v-model="form.name" name="Name" placeholder="Surname, Given Name Mi. Ext." upperCase :error="$page.props.errors.name" />
                    <ComboBox name="Department" :data="departments" v-model="form.selected_office" />
                    <ComboBox name="Statuses" :data="employee_statuses" v-model="form.selected_status" />

                    <AppInput v-model="form.id" name="ID (random on blank)" placeholder="123-xxxxx" :error="$page.props.errors.id" />

                    <div class="flex gap-2 flex-wrap">
                        <AppButton
                            v-for="item in recent_templates"
                            :icon="
                                form.selected_office.id == item.department.id && form.selected_status.id == item.employee_status.id
                                    ? CheckIcon
                                    : Square2StackIcon
                            "
                            type="button"
                            @click="applyTemplate(item.department, item.employee_status)"
                            :color="form.selected_office.id == item.department.id && form.selected_status.id == item.employee_status.id ? 'brand' : undefined"
                        >
                            <div class="flex flex-col items-start">
                                <span class="text-xs dark:text-neutral-300">{{ item.department.name }}</span>
                                <span class="text-xs dark:text-neutral-400">{{ item.employee_status.name }}</span>
                            </div>
                        </AppButton>
                    </div>

                    <div class="flex justify-end sm:flex-row gap-2 mt-4 flex-col">
                        <AppButton :icon="CircleStackIcon" color="brand">Create</AppButton>
                        <AppButton :href="route('dashboard.guest-qr.index')" :icon="ArrowLeftIcon" type="button">Back</AppButton>
                    </div>
                </form>
            </BasicCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import { ArrowLeftIcon, CheckIcon, CircleStackIcon, PencilIcon, Square2StackIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import ComboBox from '@/components/form/ComboBox.vue'

import { Select } from '@/globalTypes'

import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { useLocalStorage } from '@vueuse/core'
import Unedited from '@/components/test/Unedited.vue'

interface ReccentTemplate {
    department: Select
    employee_status: Select
}

const { departments, employee_statuses } = defineProps<{
    departments: Select[]
    employee_statuses: Select[]
}>()

const form = reactive(initForm())
const recent_templates = useLocalStorage<ReccentTemplate[]>('recent_templates', [])

function initForm() {
    return {
        id: '',
        name: '',
        selected_office: departments[0],
        selected_status: employee_statuses[0]
    }
}

function createQRCode() {
    addToRecentTemplates(form.selected_office, form.selected_status)

    router.post(route('dashboard.guest-qr.store'), {
        id: form.id,
        name: form.name,
        office_id: form.selected_office.id,
        status_id: form.selected_status.id
    })
}

function applyTemplate(department: Select, employee_status: Select) {
    form.selected_office = department
    form.selected_status = employee_status
}

function addToRecentTemplates(department: Select, employee_status: Select) {
    const exists = recent_templates.value.some((template) => template.department.id === department.id && template.employee_status.id === employee_status.id)

    if (!exists) {
        recent_templates.value.push({
            department: department,
            employee_status: employee_status
        })

        if (recent_templates.value.length > 4) {
            recent_templates.value.shift() // Remove the oldest row
        }
    }
}
</script>
