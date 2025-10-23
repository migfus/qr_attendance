<template>
    <div>
        <BasicCard :icon="LockClosedIcon" title="Update Password" description="Change the password if necessary.">
            <form class="flex flex-col gap-2" @submit.prevent="changePassword()">
                <AppInput v-model="form.current_password" name="Current Password" type="password" :error="$page.props.errors.current_password" />
                <AppInput v-model="form.new_password" name="New Password" type="password" :error="$page.props.errors.password" />
                <AppInput v-model="form.confirm_password" name="Confirm Password" type="password" :error="$page.props.errors.password_confirmation" />

                <div class="mt-4 flex flex-col sm:flex-row justify-end gap-2">
                    <AppButton
                        :icon="CheckIcon"
                        type="submit"
                        color="brand"
                        :disabled="!checkPermissions(['Account Settings/Update/All'], $page.props.auth?.permissions)"
                    >
                        Update
                    </AppButton>
                    <AppButton @click="form.reset()" :icon="XMarkIcon" type="button">Reset</AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { LockClosedIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/20/solid'
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'

import { router, usePage } from '@inertiajs/vue3'
import { defaultRouterState, checkPermissions } from '@/utils'

const form = router.form({
    current_password: '',
    new_password: '',
    confirm_password: ''
})
const page = usePage()

function changePassword() {
    router.post(
        route('dashboard.account-settings.store'),
        {
            type: 'update-password',
            current_password: form.current_password,
            password: form.new_password,
            password_confirmation: form.confirm_password,
            route_id: 2
        },
        defaultRouterState([])
    )
}

router.on('finish', () => {
    form.reset()
})
</script>
