<template>
    <div class="flex flex-col gap-2 xl:grid grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <div class="flex flex-col gap-2 col-start-2">
            <BasicCard title="Update Department" description="Update Department" :icon="PlusIcon" class="">
                <form @submit.prevent="updateDepartment()" class="flex flex-col gap-2">
                    <img :src="form.image_url" class="size-12 mx-auto rounded-full" />
                    <AppButton @click="open_modal = true" :icon="ArrowUpOnSquareIcon" type="button" class="w-full sm:w-auto mx-auto">Upload Image</AppButton>

                    <AppInput v-model="form.name" name="Name" :error="$page.props.errors.name" />
                    <AppInput v-model="form.short_name" name="Short Name" :error="$page.props.errors.short_name" />
                    <AppInput v-model="form.email" name="Email" type="email" :error="$page.props.errors.email" />
                    <AppInput v-model="form.phone_number" name="Phone Number" type="number" :error="$page.props.errors.phone_number" />

                    <div class="flex flex-col gap-2 mt-4 sm:flex-row justify-end">
                        <AppButton
                            :icon="TrashIcon"
                            color="danger"
                            type="button"
                            @click="destroy()"
                            :disabled="!checkPermissions(['Department/Delete/All'], $page.props.auth?.permissions)"
                        >
                            Delete
                        </AppButton>
                        <AppButton
                            :icon="CircleStackIcon"
                            color="brand"
                            :disabled="!checkPermissions(['Department/Update/All'], $page.props.auth?.permissions)"
                        >
                            Update
                        </AppButton>
                        <AppButton :href="route('dashboard.departments.index')" :icon="ArrowLeftIcon" type="button">Back</AppButton>
                    </div>
                </form>
            </BasicCard>

            <UploadAvatarModal v-model="form.image_url" v-model:show="open_modal" name="Upload Image" :ratio="1" :size="[100, 100]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import { ArrowLeftIcon, ArrowUpOnSquareIcon, CircleStackIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'

import { Department } from './departmentInt'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { base64ToBlob, checkPermissions } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const { edit_data } = defineProps<{
    edit_data: Department
}>()

const $page = usePage()

function initForm() {
    return {
        name: edit_data.name,
        image_url: edit_data.image_url,
        short_name: edit_data.short_name,
        email: edit_data.email ?? '',
        phone_number: edit_data.phone_number?.toString() ?? ''
    }
}

const form = reactive(initForm())
const open_modal = ref(false)

function updateDepartment() {
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.image_url ?? '', 'image/jpg', 'cropped-image-department.jpg')

    formData.append('name', form.name)
    formData.append('image_url', cropped_blob)
    formData.append('short_name', form.short_name)
    formData.append('email', form.email)
    formData.append('phone_number', form.phone_number)
    formData.append('_method', 'PUT')

    router.post(route('dashboard.departments.update', { department: edit_data.id }), formData)
}

function destroy() {
    router.delete(route('dashboard.departments.destroy', { department: edit_data.id }))
}
</script>
