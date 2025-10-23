<template>
    <div class="flex flex-col gap-2 xl:grid grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />
        <div class="flex flex-col gap-2 col-start-2">
            <BasicCard title="New User" description="Edit the information" :icon="PencilIcon" class="">
                <form @submit.prevent="createUser()" class="flex flex-col gap-2">
                    <img :src="form.avatar_url" class="size-24 mx-auto rounded-full" />
                    <AppButton @click="open_modal = true" :icon="ArrowUpOnSquareIcon" type="button" class="w-full sm:w-auto sm:mx-auto">
                        Upload Avatar
                    </AppButton>

                    <AppInput v-model="form.name" name="Name" :error="$page.props.errors.name" />
                    <AppInput v-model="form.email" name="Email" type="email" :error="$page.props.errors.email" />
                    <AppInput v-model="form.password" name="Password" :error="$page.props.errors.password" />
                    <ComboBox name="Department" :data="departments" v-model="form.department" />
                    <ComboBox name="Department" :data="roles" v-model="form.role" />

                    <div class="flex flex-col sm:flex-row justify-end gap-2 mt-2">
                        <AppButton :icon="CircleStackIcon" color="brand">Create</AppButton>

                        <AppButton :href="route('dashboard.users.index')" :icon="ArrowLeftIcon" type="button">Back</AppButton>
                    </div>
                </form>
            </BasicCard>

            <UploadAvatarModal v-model="form.avatar_url" v-model:show="open_modal" name="Upload Avatar" :ratio="1" :size="[300, 300]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import AppInput from '@/components/form/AppInput.vue'
import { ArrowLeftIcon, ArrowUpOnSquareIcon, CircleStackIcon, PencilIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import ComboBox from '@/components/form/ComboBox.vue'

import { Role } from '../roles-permissions/rolesPermissionsInt'
import { Select } from '@/globalTypes'

import { reactive, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'
import { base64ToBlob } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const { roles, departments } = defineProps<{
    roles: Role[]
    departments: Select[]
}>()

const $page = usePage()

function initForm() {
    return {
        name: '',
        email: '',
        password: '',
        role: roles[0],
        department: departments[0],
        avatar_url: `${$page.props.base_url}/images/cmu.png`
    }
}

const form = reactive(initForm())
const open_modal = ref(false)

function createUser() {
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.avatar_url, 'image/jpg', 'avatar-image.jpg')

    formData.append('name', form.name)
    formData.append('email', form.email)
    formData.append('password', form.password)
    formData.append('role_id', form.role.id.toString())
    formData.append('department_id', form.department.id.toString())
    formData.append('avatar_file', cropped_blob)

    router.post(route('dashboard.users.store'), formData, {
        onSuccess: () => {
            router.visit(route('dashboard.users.index'))
        },
        onError: (error) => {
            console.error(error)
        },
        headers: { 'Content-Type': 'multipart/form-data' }
    })
}
</script>
