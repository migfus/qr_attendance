<template>
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <Unedited />

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img :src="`${$page.props.base_url}/images/ohrm.png`" class="mx-auto size-32 w-auto rounded-2xl p-6 bg-white dark:bg-dark-002" alt="OHRM Logo" />
            <h2 class="mt-2 text-center text-lg font-semibold tracking-tight text-brand-600 dark:text-neutral-300">QR Attendance | OHRM CMU</h2>
            <h2 class="mt-4 text-center text-3xl font-bold tracking-tight text-brand-600 dark:text-neutral-300">Sign In</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-dark-002 py-8 px-4 sm:rounded-xl sm:px-10">
                <!-- NOTE: FORM -->
                <form class="space-y-6" @submit.prevent="submit()">
                    <AppInput v-model="form.email" :error="$page.props.errors.email" name="Email" type="email" placeholder="Enter a email" />

                    <AppInput v-model="form.password" :error="$page.props.errors.password" type="password" name="Password" placeholder="Enter a password" />

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                v-model="form.remember"
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                            />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-neutral-300">Remember me</label>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <AppButton color="brand"> Sign in </AppButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'

import { router, useForm } from '@inertiajs/vue3'
import { onErrorCaptured } from 'vue'
import { errorAlert } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const form = useForm({
    email: '',
    password: '',
    remember: false
})
form.email = route().params.email

function submit() {
    router.post(route('login.submit'), {
        email: form.email,
        password: form.password,
        remember: form.remember
    })
}

onErrorCaptured((e) => errorAlert('/login/Login', e))
</script>
