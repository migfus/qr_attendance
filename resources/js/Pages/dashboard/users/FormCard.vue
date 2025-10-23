<template>
    <div>
        <BasicCard title="Edit User" description="Edit user's data" :icon="UserIcon">
            <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-2 items-center">
                    <img :src="form.avatar_url" alt="User Avatar" class="size-36 rounded-full" />
                    <AppButton
                        @click="open_modal = true"
                        :disabled="!checkPermissions(['Users/Update/All'], $page.props.auth?.permissions)"
                        class="w-full sm:w-auto"
                    >
                        Change Avatar
                    </AppButton>
                </div>

                <form @submit.prevent="updateUser()" class="flex flex-col gap-2">
                    <AppInput name="Name" v-model="form.name" :error="$page.props.errors.name" />
                    <AppInput name="Email" type="email" v-model="form.email" :error="$page.props.errors.name" />
                    <AppInput name="Password (blank no change)" placeholder="No Change" v-model="form.password" :error="$page.props.errors.password" />
                    <ComboBox name="Department" v-model="form.department" :data="departments" />
                    <ComboBox name="Role" v-model="form.role" :data="roles" />

                    <div class="flex flex-col gap-2 mt-4 sm:flex-row justify-end">
                        <AppButton
                            color="danger"
                            :icon="TrashIcon"
                            type="button"
                            @click="deleteUser()"
                            :disabled="!checkPermissions(['Users/Delete/All'], $page.props.auth?.permissions)"
                        >
                            Delete User
                        </AppButton>
                        <AppButton color="brand" :icon="CircleStackIcon" :disabled="!checkPermissions(['Users/Update/All'], $page.props.auth?.permissions)">
                            Update
                        </AppButton>
                        <AppButton :icon="ArrowLeftIcon" :href="route('dashboard.users.index')" type="button"> Back </AppButton>
                    </div>
                </form>
            </div>
        </BasicCard>

        <UploadAvatarModal v-model="form.avatar_url" v-model:show="open_modal" name="Change Avatar" :ratio="1" :size="[300, 300]" />
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowLeftIcon, CircleStackIcon, TrashIcon, UserIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import ComboBox from '@/components/form/ComboBox.vue'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'

import { Select, User } from '@/globalTypes'
import { Role } from '../roles-permissions/rolesPermissionsInt'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { base64ToBlob, checkPermissions, defaultRouterState } from '@/utils'

const { user, departments, roles } = defineProps<{
    user: User
    departments: Select[]
    roles: Role[]
}>()

const form = reactive<{
    name: string
    email: string
    password: string
    department: Select
    role: Role
    avatar_url: string
}>({
    name: user.name,
    email: user.email,
    password: '',
    department: departments.find((department) => department.id === user.department.id)!,
    role: roles.find((role) => role.id === user.roles[0].id)!,
    avatar_url: user.avatar_url
})

const open_modal = ref(false)

function updateUser() {
    const form_data = new FormData()

    const cropped_blob = base64ToBlob(form.avatar_url, 'image/jpg', 'avatar-image.jpg')

    form_data.append('name', form.name)
    form_data.append('email', form.email)
    form_data.append('password', form.password)
    form_data.append('department_id', form.department.id.toString())
    form_data.append('role_id', form.role.id.toString())
    form_data.append('avatar_file', cropped_blob)
    form_data.append('_method', 'PUT')

    router.post(
        route('dashboard.users.update', {
            user: user.id
        }),
        form_data,
        {
            headers: { 'Content-Type': 'multipart/form-data' },
            ...defaultRouterState()
        }
    )
}

function deleteUser() {
    router.delete(
        route('dashboard.users.destroy', {
            user: user.id
        })
    )
}
</script>
