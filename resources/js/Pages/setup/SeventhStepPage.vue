<template>
    <SetupLayout :current_step="5">
        <BasicCard title="Create form for invited users" class="lg:col-start-2" :icon="SquaresPlusIcon">
            <form @submit.prevent="submit()" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <draggableComponent
                        v-model="form"
                        itemKey="id"
                        @start="dragging = true"
                        @end="dragging = false"
                        ghost-class="ghost"
                        handle=".handle"
                        class="flex flex-col gap-2"
                    >
                        <template #item="{ element }">
                            <div class="bg-dark-003 object-shadow rounded-xl p-4">
                                <div class="mb-4 -mt-2 cursor-move max-w handle hover:bg-dark-001 hover:object-shadow rounded-xl transition-all">
                                    <EqualsIcon class="size-5 mx-auto" />
                                </div>
                                <AppInput :name="`Input Name (${element.id})`" v-model="element.title" noLabel placeholder="Please input label" />
                                <div class="flex justify-end gap-2 mt-2">
                                    <FormInputTypeDropdown :types="input_types" :current_type="element.type" :id="element.id" @changeType="changeType" />

                                    <AppButton v-if="form.length > 1" :icon="TrashIcon" type="button" icon_only @click="removeInput(element.id)">
                                        Remove
                                    </AppButton>
                                </div>

                                <div v-if="element.type.name == 'Multi Choice' || element.type.name == 'Checkbox'" class="mt-2 flex flex-col gap-1">
                                    <div v-for="(select_item, idx) in element.select" class="flex flex-col gap-2">
                                        <div class="flex gap-2">
                                            <AppInput v-model="select_item.name" name="" noLabel class="w-full" />

                                            <AppButton
                                                v-if="element.select.length > 1"
                                                iconOnly
                                                :icon="TrashIcon"
                                                @click="element.select = element.select.filter((item: { id: string }) => item.id !== select_item.id)"
                                                class="flex-none"
                                            />
                                        </div>
                                        <div class="flex">
                                            <AppButton v-if="element.select.length == idx + 1" :icon="PlusIcon" @click="addSelect(element.select)" iconOnly />
                                        </div>
                                    </div>
                                </div>
                                <div v-if="element.type.name == 'Upload' && element.upload_settings" class="flex gap-2">
                                    <AppInput v-model="element.upload_settings.max_size_mb" name="Maximum size (MiB)" />
                                    <AppInput v-model="element.upload_settings.allowed_types" name="File Types" />
                                </div>
                            </div>
                        </template>
                    </draggableComponent>

                    <!-- {{ form }} -->

                    <div class="flex justify-end gap-2">
                        <AppButton :icon="PlusIcon" type="button" icon_only @click="addInput()">Add Input</AppButton>
                    </div>

                    <div class="flex flex-col gap-2 mt-4">
                        <InformationLabel> You can update the form after the setup. </InformationLabel>
                        <InformationLabel> This form is for the single team only. </InformationLabel>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row-reverse gap-2">
                    <AppButton :icon="ArrowRightIcon">Next</AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('setup.show', { setup: 6 })" type="button">Back</AppButton>
                </div>
            </form>
        </BasicCard>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    ArrowUpOnSquareIcon,
    Bars2Icon,
    Bars3BottomLeftIcon,
    CalendarDateRangeIcon,
    CalendarIcon,
    CheckCircleIcon,
    ClockIcon,
    EllipsisVerticalIcon,
    EqualsIcon,
    PlusIcon,
    SquaresPlusIcon,
    StopCircleIcon,
    TrashIcon
} from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import SetupLayout from './SetupLayout.vue'
import AppInput from '@/components/form/AppInput.vue'
import InformationLabel from '@/components/info/InformationLabel.vue'
import FormInputTypeDropdown from './FormInputTypeDropdown.vue'

import { ref, watch } from 'vue'
import { passwordGenerator } from '@/utils'
import draggableComponent from 'vuedraggable'
import { InputType } from './setupInterfaces'

interface Form {
    id: string
    title: string
    type: InputType
    select: {
        id: string
        name: string
    }[]
    attachements: string[]
    upload_settings?: {
        max_size_mb: number
        allowed_types: string[]
    }
}

const input_types: InputType[] = [
    {
        icon: Bars2Icon,
        name: 'Short Answer'
    },
    {
        icon: Bars3BottomLeftIcon,
        name: 'Long Answer'
    },
    {
        icon: CheckCircleIcon,
        name: 'Multi Choice'
    },
    {
        icon: EllipsisVerticalIcon,
        name: 'Checkbox'
    },
    {
        icon: ArrowUpOnSquareIcon,
        name: 'Upload'
    },
    {
        icon: CalendarIcon,
        name: 'Date'
    },
    {
        icon: ClockIcon,
        name: 'Time'
    },
    {
        icon: CalendarDateRangeIcon,
        name: 'Date & Time'
    }
]

const form = ref<Form[]>([
    {
        id: '1',
        title: 'Office / Department',
        type: {
            icon: Bars2Icon,
            name: 'Short Answer'
        },
        select: [],
        attachements: []
    },
    {
        id: '2',
        title: 'Address',
        type: {
            icon: Bars3BottomLeftIcon,
            name: 'Long Answer'
        },
        select: [],
        attachements: []
    }
])
const dragging = ref(false)

function addInput() {
    form.value.push({
        id: passwordGenerator(),
        title: '',
        type: {
            icon: Bars2Icon,
            name: 'Short Answer'
        },
        select: [],
        attachements: []
    })
}

function removeInput(item_id: string) {
    form.value = form.value.filter((item) => item.id !== item_id)
}

function changeType(id: string, input_type: InputType) {
    const item = form.value.find((item) => item.id == id)
    if (item) {
        item.type = input_type
        if (item.type.name == 'Multi Choice' || item.type.name == 'Checkbox') {
            resetInputForm(item)
            item.select = [
                {
                    id: passwordGenerator(),
                    name: 'Others'
                }
            ]
        } else if (item.type.name == 'Upload') {
            resetInputForm(item)
            item.upload_settings = {
                allowed_types: ['JPG', 'PNG', 'JPEG', 'PDF'],
                max_size_mb: 5
            }
        } else {
            resetInputForm(item)
        }
    }
}

function resetInputForm(item: Form) {
    item.attachements = []
    item.select = []
    item.upload_settings = undefined
}

function addSelect(item: { name: string; id: string }[]) {
    item.push({
        id: passwordGenerator(),
        name: 'Others'
    })
}

function submit() {}

watch(dragging, () => {
    console.log('under dragging on drugs')
})
</script>
