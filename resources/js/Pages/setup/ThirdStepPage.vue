<template>
    <SetupLayout :current_step="3">
        <BasicCard title="Change the Look" class="lg:col-start-2" :icon="ArrowRightIcon">
            <form @submit.prevent="submit()" class="flex flex-col gap-4">
                <div class="mx-auto flex flex-col gap-2">
                    <img :src="form.logo" class="size-18 mx-auto" />
                    <AppButton type="button" :icon="ArrowUpIcon" @click="show_modal = true">Change Logo</AppButton>
                </div>

                <div class="flex flex-col gap-4">
                    <RadioGroup v-model="selected_theme">
                        <div class="flex flex-col lg:grid grid-cols-3 gap-4">
                            <RadioGroupOption as="template" v-for="item in themes" :key="item.name" :value="item.name" v-slot="{ active, checked }">
                                <div
                                    :class="['relative flex flex-col cursor-pointer rounded-lg px-4 pt-4 object-shadow focus:outline-none ']"
                                    :style="{ backgroundColor: item.light.default }"
                                >
                                    <div class="flex gap-2">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <RadioGroupLabel as="span" class="block text-sm font-medium mb-2" :style="{ color: item.dark.brand }">
                                                    {{ item.name }}
                                                </RadioGroupLabel>
                                            </span>
                                        </span>
                                        <CheckCircleIcon
                                            :class="[!checked ? 'invisible' : '', 'h-5 w-5']"
                                            :style="{ color: item.dark.brand }"
                                            aria-hidden="true"
                                        />
                                        <span
                                            :class="[checked ? 'object-shadow-md' : 'object-shadow', 'pointer-events-none absolute -inset-px rounded-lg']"
                                            aria-hidden="true"
                                        />
                                    </div>
                                    <div :style="{ backgroundColor: item.light.brand }" class="-mx-4 p-4"></div>
                                    <div :style="{ backgroundColor: item.dark.brand }" class="-mx-4 p-4"></div>
                                    <div :style="{ backgroundColor: item.dark.default }" class="-mx-4 p-4 rounded-b-lg"></div>
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>

                <AppTextArea v-model="form.description" name="Website Description (for search engine)" />

                <div class="flex flex-col gap-2 mt-4">
                    <InformationLabel> You can get more colors after the setup. </InformationLabel>
                </div>

                <div class="flex flex-col lg:flex-row-reverse gap-2">
                    <AppButton :icon="ArrowRightIcon">Next</AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('setup.show', { setup: 2 })" type="button">Back</AppButton>
                </div>
            </form>

            <UploadAvatarModal v-model="form.logo" v-model:show="show_modal" :size="[200, 200]" name="Upload Logo" :ratio="1" />
        </BasicCard>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, ArrowRightIcon, ArrowUpIcon, CheckCircleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppButton from '@/components/form/AppButton.vue'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'
import SetupLayout from './SetupLayout.vue'
import { RadioGroup, RadioGroupOption, RadioGroupLabel } from '@headlessui/vue'

import { reactive, ref } from 'vue'
import { SelectTheme } from './setupInterfaces'
import InformationLabel from '@/components/info/InformationLabel.vue'

const form = reactive(initForm())
const show_modal = ref(false)
const selected_theme = ref('Green Theme')
const themes: SelectTheme[] = [
    {
        name: 'Blue Theme',
        dark: {
            brand: '#3d378b',
            default: '#191a1b'
        },
        light: {
            brand: '#aeb9f3',
            default: '#eff2ef'
        }
    },
    {
        name: 'Green Theme',
        dark: {
            brand: '#323d34',
            default: '#191a1b'
        },
        light: {
            brand: '#768c78',
            default: '#eff2ef'
        }
    },
    {
        name: 'Red Theme',
        dark: {
            brand: '#8b3737',
            default: '#191a1b'
        },
        light: {
            brand: '#ebb6b6',
            default: '#eff2ef'
        }
    }
]

function initForm() {
    return {
        description: 'QR Scanner, Attendance Management System, Employee Check-in System, Easy to use, Fast, Reliable',
        logo: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSwCxCwnT2kkNxp9BRdEmk0xMYBb-bbqvHWRA&s'
    }
}

function submit() {}
</script>
