<template>
    <div class="flex flex-col gap-2 xl:grid grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <div class="flex flex-col gap-2 col-start-2">
            <BasicCard title="Create Department" description="Create Department" :icon="PlusIcon" class="">
                <form @submit.prevent="storeDepartment()" class="flex flex-col gap-2">
                    <img :src="form.image_url" class="size-12 mx-auto rounded-full" />
                    <AppButton @click="open_modal = true" :icon="ArrowUpOnSquareIcon" type="button" class="mx-auto">Upload Image</AppButton>

                    <AppInput v-model="form.name" name="Name" :error="$page.props.errors.name" />
                    <AppInput v-model="form.short_name" name="Short Name" :error="$page.props.errors.short_name" />
                    <AppInput v-model="form.email" name="Email" type="email" :error="$page.props.errors.email" />
                    <AppInput v-model="form.phone_number" name="Phone Number" type="number" :error="$page.props.errors.phone_number" />

                    <div class="flex flex-col gap-2 mt-4 sm:flex-row justify-end">
                        <AppButton :icon="CircleStackIcon" color="brand"> Create </AppButton>
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
import { ArrowLeftIcon, ArrowUpOnSquareIcon, CircleStackIcon, PlusIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { base64ToBlob } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const $page = usePage()

function initForm() {
    return {
        name: '',
        image_url: `${$page.props.base_url}/images/cmu.png`,
        short_name: '',
        email: '',
        phone_number: ''
    }
}

const form = reactive(initForm())
const open_modal = ref(false)

function storeDepartment() {
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.image_url, 'image/jpg', 'cropped-image-department.jpg')

    formData.append('name', form.name)
    formData.append('image_url', cropped_blob)
    formData.append('short_name', form.short_name)
    formData.append('email', form.email)
    formData.append('phone_number', form.phone_number)

    router.post(route('dashboard.departments.store'), formData)
}
</script>
