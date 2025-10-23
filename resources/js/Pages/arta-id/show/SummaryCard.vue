<template>
    <div>
        <BasicCard title="Summary" description="Summary" :icon="InformationCircleIcon">
            <div class="flex flex-col gap-4">
                <div class="flex justify-center">
                    <img
                        :src="
                            show_data.files[show_data.files.length - 1].file_url
                                ? show_data.files[show_data.files.length - 1].thumbnail_url
                                : '/images/avatar.webp'
                        "
                        class="size-44 rounded-2xl"
                    />
                </div>

                <div class="flex flex-col items-start rounded-xl p-4 dark:bg-dark-001 bg-brand-50">
                    <p class="font-bold">
                        {{
                            `${show_data.last_name}, ${show_data.first_name} ${show_data.mid_name}
                            ${show_data.extra_name.name == 'N/A' ? '' : show_data.extra_name.name}`
                        }}
                    </p>
                    <p class="line-clamp-2 text-center font-semibold">{{ `${show_data.department.name}` }}</p>
                    <p class="font-semibold">{{ `${show_data.position.name}` }}</p>
                    <p class="text-sm">{{ `${show_data.employee_status.name}` }}</p>
                </div>
                <div v-if="show_data.claim_type" class="flex flex-col rounded-2xl p-4 bg-brand-50 dark:bg-dark-001">
                    <p class="font-bold">{{ show_data.claim_type.name }}</p>
                    <p>{{ `${show_data.claim_type.price} pesos` }}</p>
                    <img :src="show_data.claim_type.image_url" />
                </div>
                <div
                    v-if="
                        show_data.request_statuses[0].request_status_type.name == 'Unverified' ||
                        show_data.request_statuses[0].request_status_type.name == 'Rejected'
                    "
                    class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4"
                >
                    <AppButton :icon="LockClosedIcon" color="danger" @click="delete_modal = true">Delete</AppButton>
                    <AppButton color="brand" :icon="LockClosedIcon" @click="edit_modal = true">Edit</AppButton>
                </div>
                <div v-else class="flex justify-end gap-2 mt-4">
                    <AppButton :icon="LockClosedIcon">You can no longer edit/delete</AppButton>
                </div>
            </div>
        </BasicCard>

        <FormModal v-model="delete_modal" title="Delete this request?" :icon="TrashIcon" description="Delete this request" size="sm">
            <form @submit.prevent="deleteRequest()" class="flex flex-col gap-2 mt-4">
                <li class="text-md font-semibold text-brand-500 dark:text-neutral-300">You can find the pin code to your email.</li>
                <li class="text-md font-semibold text-red-500 dark:text-red-400">You cannot undo this action.</li>
                <AppInput v-model="formModal.password" name="PIN" />
                <AppTextArea name="Reason" v-model="formModal.reason" />

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="XMarkIcon" type="button" @click="delete_modal = false">Cancel</AppButton>
                    <AppButton color="danger" :icon="TrashIcon">Delete</AppButton>
                </div>
            </form>
        </FormModal>

        <FormModal v-model="edit_modal" title="Edit this request?" :icon="TrashIcon" description="Edit this request?">
            <form class="flex flex-col gap-2 mt-4">
                <ul>
                    <li class="text-md font-semibold text-brand-500 dark:text-neutral-300">You can find the pin code to your email.</li>
                </ul>
                <AppInput v-model="formModal.password" name="PIN" />
                <AppTextArea name="Reason" v-model="formModal.reason" />

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="XMarkIcon" type="button" @click="edit_modal = false">Cancel</AppButton>
                    <AppButton
                        color="brand"
                        :icon="PencilIcon"
                        :href="`${route('arta-id.edit', { arta_id: show_data.id })}?pin=${formModal.password}&reason=${formModal.reason}`"
                    >
                        Edit
                    </AppButton>
                </div>
            </form>
        </FormModal>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { InformationCircleIcon, LockClosedIcon, PencilIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import FormModal from '@/components/modals/FormModal.vue'

import { Employee } from '@/Pages/dashboard/arta-id/artaIdInt'

import { TrashIcon } from '@heroicons/vue/24/outline'
import { router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

const { show_data } = defineProps<{
    show_data: Employee
}>()

const formModal = reactive({
    password: '',
    reason: ''
})
const delete_modal = ref(false)
const edit_modal = ref(false)

function deleteRequest() {
    router.delete(route('arta-id.destroy', { arta_id: show_data.id }) + `?pin=${formModal.password}&reason=${formModal.reason}`)
}
</script>
