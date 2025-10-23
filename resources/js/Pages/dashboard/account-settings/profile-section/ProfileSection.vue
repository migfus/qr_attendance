<template>
    <BasicCard title="Profile" :icon="PaintBrushIcon" description="Set your profile.">
        <form @submit.prevent="updateProfile()" class="flex flex-col gap-2">
            <div class="flex flex-col gap-2 col-start-2 lg:grid grid-cols-2">
                <div class="flex flex-col gap-2 items-center">
                    <img :src="form.avatar" class="rounded-full size-36" />
                    <AppButton
                        :icon="UserIcon"
                        @click="open_modal = true"
                        :disabled="!checkPermissions(['Account Settings/Update/All'], $page.props.auth?.permissions)"
                        class="w-full sm:w-auto"
                        type="button"
                    >
                        Change Profile
                    </AppButton>
                </div>

                <div class="flex flex-col gap-2 justify-end">
                    <AppInput name="Name" v-model="form.name" :error="$page.props.errors.name" />
                    <AppInput name="Email" type="email" v-model="form.email" :error="$page.props.errors.email" />
                    <div class="flex flex-col sm:flex-row justify-end mt-4">
                        <AppButton :icon="CircleStackIcon" :disabled="!checkPermissions(['Account Settings/Update/All'], $page.props.auth?.permissions)">
                            Update
                        </AppButton>
                    </div>
                </div>
            </div>
        </form>

        <UploadAvatarModal name="Upload Avatar" :ratio="1" :size="[300, 300]" v-model="form.avatar" v-model:show="open_modal" @upload="uploadAvatar()" />
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { PaintBrushIcon } from '@heroicons/vue/20/solid'
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'
import UploadAvatarModal from '@/components/modals/UploadAvatarModal.vue'

import { reactive, ref } from 'vue'
import { CircleStackIcon, UserIcon } from '@heroicons/vue/24/outline'
import { router, usePage } from '@inertiajs/vue3'
import { base64ToBlob, checkPermissions, defaultRouterState } from '@/utils'

const $page = usePage()

const form = reactive({
    name: $page.props.auth?.name ?? '',
    email: $page.props.auth?.email ?? '',
    avatar: $page.props.auth?.avatar_url ?? ''
})

const open_modal = ref(false)

function uploadAvatar() {
    const formData = new FormData()

    const cropped_blob = base64ToBlob(form.avatar, 'image/jpg', 'avatar-image.jpg')

    formData.append('type', 'upload-avatar')
    formData.append('avatar', cropped_blob)
    formData.append('route_id', '1')

    router.post(route('dashboard.account-settings.store'), formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        ...defaultRouterState([])
    })
}

function updateProfile() {
    router.post(route('dashboard.account-settings.store'), { type: 'update-profile', email: form.email, name: form.name, route_id: 1 }, defaultRouterState([]))
}
</script>
