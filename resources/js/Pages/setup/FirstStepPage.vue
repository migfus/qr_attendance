<template>
    <SetupLayout :current_step="1">
        <form @submit.prevent="storeEnvFile()" class="flex flex-col gap-4 lg:col-start-2">
            <BasicCard title="Website Setup" :icon="AdjustmentsHorizontalIcon">
                <div class="flex flex-col gap-2">
                    <AppInput v-model="form.app_name" name="Website Name" :error="errors.app_name" />
                    <AppInput v-model="form.app_url" name="App URL" :error="errors.app_url" class="mb-4" />
                </div>
            </BasicCard>

            <BasicCard title="Email Setup" class="lg:col-start-2" :icon="CircleStackIcon">
                <div class="flex flex-col gap-2">
                    <AppSelect v-model="form.mail_mailer" :suggestions="mailer_protocols" name="Mail Protocol" :error="errors.mail_mailer" color="" />
                    <AppInput v-model="form.mail_host" name="Mail Host" :error="errors.mail_host" />
                    <AppInput v-model="form.mail_port" name="Mail Port" :error="errors.mail_port" />
                    <AppInput v-model="form.mail_username" name="Mail Username" :error="errors.mail_username" />
                    <AppInput v-model="form.mail_password" name="Mail Password" type="password" :error="errors.mail_password" />
                    <AppInput v-model="form.mail_from_address" name="Mail From Address" :error="errors.mail_from_address" />
                    <div class="flex flex-col lg:flex-row-reverse gap-2">
                        <AppButton :icon="PaperAirplaneIcon" type="button" @click="testEmail()"> Test Email </AppButton>
                    </div>
                </div>
            </BasicCard>

            <BasicCard title="Database Setup" class="lg:col-start-2" :icon="CircleStackIcon">
                <div class="flex flex-col gap-2">
                    <AppInput v-model="form.host" name="Host" :error="errors.host" />
                    <AppInput v-model="form.port" name="Port" :error="errors.port" />
                    <AppInput v-model="form.database" name="Database" :error="errors.database" />
                    <AppInput v-model="form.username" name="Username" :error="errors.username" />
                    <AppInput v-model="form.password" name="Password" type="password" :error="errors.password" />

                    <div class="flex flex-col lg:flex-row-reverse gap-2">
                        <AppButton :icon="verify_button_status.icon" type="button" @click="verifyDatabase()" :color="verify_button_status.color">
                            {{ verify_button_status.name }}
                        </AppButton>
                    </div>
                </div>
            </BasicCard>

            <BasicCard title="Finalize" :icon="CheckIcon">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col gap-2">
                        <p class="text-brand-200 font-semibold text-sm flex align-middles gap-2">
                            <InformationCircleIcon class="size-5" />
                            You can modify the .env after setup.
                        </p>
                        <p class="text-brand-200 font-semibold text-sm flex align-middles gap-2">
                            <InformationCircleIcon class="size-5" />
                            Do not run on "php artisan serve", it will not run migration.
                        </p>
                        <p class="text-brand-200 font-semibold text-sm flex align-middles gap-2">
                            <InformationCircleIcon class="size-5" />
                            Double check everything before you proceed.
                        </p>
                    </div>

                    <div class="flex flex-col lg:flex-row-reverse gap-2">
                        <AppButton :icon="ArrowRightIcon">Next</AppButton>
                        <AppButton :icon="ArrowPathIcon" @click="reset()" type="button">Reset</AppButton>
                    </div>
                </div>
            </BasicCard>
        </form>
    </SetupLayout>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import {
    AdjustmentsHorizontalIcon,
    ArrowPathIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    CheckIcon,
    CircleStackIcon,
    InformationCircleIcon,
    PaperAirplaneIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'
import AppInput from '@/components/form/AppInput.vue'
import AppButton from '@/components/form/AppButton.vue'
import SetupLayout from './SetupLayout.vue'
import AppSelect from '@/components/form/AppSelect.vue'

import { router } from '@inertiajs/vue3'
import { reactive, defineEmits, watch } from 'vue'
import { DatabaseFormError, VerifyButtonStatus } from './setupInterfaces'
import { Select } from '@/globalTypes'

const form = reactive(initForm())
const verify_button_statuses: VerifyButtonStatus[] = [
    {
        name: 'Test Connection',
        icon: ArrowPathIcon
    },
    {
        name: 'Verifying...',
        icon: ArrowPathIcon
    },
    {
        name: 'Connection Successful. Check again?',
        icon: CheckCircleIcon,
        color: 'brand'
    },
    {
        name: 'Connection Failed. Try again.',
        icon: XMarkIcon,
        color: 'danger'
    }
]
const verify_button_status = reactive<VerifyButtonStatus>(verify_button_statuses[0])
const mailer_protocols: Select[] = [
    {
        name: 'SMTP',
        id: 'smtp'
    },
    {
        name: 'Sendmail',
        id: 'sendmail'
    },
    {
        name: 'Mailgun',
        id: 'mailgun'
    },
    {
        name: 'SES',
        id: 'ses'
    },
    {
        name: 'Postmark',
        id: 'postmark'
    }
]

const { flash } = defineProps<{
    errors: DatabaseFormError
    flash: {
        error?: string
    }
}>()

watch(
    () => flash,
    () => {
        if (flash.error) {
            changeVerifyButtonStatus(3)
        } else {
            changeVerifyButtonStatus(2)
        }
    }
)

function changeVerifyButtonStatus(status_index: number) {
    Object.assign(verify_button_status, verify_button_statuses[status_index])

    setTimeout(() => {
        Object.assign(verify_button_status, verify_button_statuses[0])
    }, 5000)
}

function initForm() {
    return {
        app_name: '[Test]',
        app_url: 'https://test.cmuohrms.site',

        mail_mailer: {
            name: 'SMTP',
            id: 'smtp'
        },
        mail_host: 'smtp.hostinger.com',
        mail_port: '587',
        mail_username: 'no-reply@example.com',
        mail_encryption: 'tls',
        mail_password: '',
        mail_from_address: 'noreply@example.com',

        host: 'localhost',
        port: '3306',
        database: 'u218070332_test',
        username: 'u218070332_test',
        password: 'a#pEIxPDP6'
    }
}

async function verifyDatabase() {
    changeVerifyButtonStatus(1)

    try {
        const res = await router.post(route('setup.store'), {
            req_type: 'verify_database',
            ...form
        })
    } catch (err) {
        console.error(err)
    }
}

async function storeEnvFile() {
    try {
        const res = await router.post(route('setup.store'), {
            req_type: 'store_env_file',
            ...form,
            mail_mailer: form.mail_mailer.id
        })
    } catch (err) {
        console.error(err)
    }
}

async function testEmail() {
    try {
        const res = await router.post(route('setup.store'), {
            req_type: 'test_email',
            ...form,
            mail_mailer: form.mail_mailer.id
        })
    } catch (err) {
        console.error(err)
    }
}

function reset() {
    Object.assign(form, initForm())
}
</script>
