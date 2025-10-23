<template>
    <div>
        <BasicCard title="Update Status" description="Update request status" :icon="PencilIcon">
            <form @submit.prevent="notify_confirm()" class="flex flex-col">
                <ComboBox name="Status Type" :data="request_status_types" v-model="form.request_status_type" />
                <div class="flex gap-8 mb-2">
                    <AppToggle :name="`Email to ${employee_email}`" v-model="form.notify_email" />
                </div>

                <div :class="theme == 'dark' ? 'quill-editor-dark' : ''">
                    <AppTextArea name="Content" v-model="form.content" :error="errors?.content" noLabel />
                </div>
                <span class="text-red-500 text-sm font-semibold">{{ errors?.content }}</span>

                <div class="flex flex-col xl:flex-row justify-between mt-4">
                    <div class="flex gap-2">
                        <AppCheckbox name="Auto apply from template" v-model="apply_from_template" />
                    </div>
                    <div class="flex flex-col xl:flex-row gap-2 mt-4 xl:mt-0">
                        <!-- <AppButton :icon="ArrowPathIcon" type="button" @click="Object.assign(form, initForm())">Reset</AppButton> -->
                        <AppButton :icon="SparklesIcon" type="button" @click="openGemini()" :forceLoading="gemini.loading"> Ask AI </AppButton>
                        <AppButton :icon="SparklesIcon" type="button" @click="rephraseGemini()" :forceLoading="gemini.loading"> Rephrase </AppButton>
                        <AppButton :icon="PaperAirplaneIcon" color="brand" :disabled="!checkPermissions(['ARTA ID/Update/All'], $page.props.auth?.permissions)">
                            Notify
                        </AppButton>
                    </div>
                </div>
            </form>
        </BasicCard>

        <ConfirmationPrompt
            v-model="confirm_modal"
            :confirmIcon="CheckIcon"
            confirmMessage="Yes, confirmed."
            title="Confirm?"
            id="confirm_update_status"
            @confirm="notify()"
        >
            <li>Confirm all are correct?</li>
        </ConfirmationPrompt>

        <FormModal title="Generate Entry" :icon="SparklesIcon" description="Powered by Google Gemini" v-model="gemini.open">
            <div class="flex flex-col gap-2">
                <div class="flex flex-col gap-2 h-[calc(70vh-5rem)] overflow-y-auto" ref="scrollGemini">
                    <div v-for="reply in gemini.replies" class="rounded-xl mx-2">
                        <div class="flex flex-col gap-2">
                            <div :class="[reply.role == 'user' ? 'justify-end' : 'justify-start', 'flex']">
                                <img v-if="reply.role == 'user'" :src="$page.props.auth?.avatar_url" class="size-8 rounded-full" />
                                <img
                                    v-else
                                    src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/google-gemini-icon.png"
                                    class="size-4 rounded-full"
                                />
                            </div>
                            <div
                                v-for="text in reply.parts"
                                :class="[
                                    reply.role == 'user' ? 'rounded-l-xl rounded-br-xl ml-2' : 'rounded-r-xl rounded-bl-xl mr-2',
                                    'bg-brand-50 dark:bg-dark-001 p-4 relative group'
                                ]"
                            >
                                <div
                                    v-if="reply.role == 'model'"
                                    class="flex justify-end absolute bottom-0 right-0 p-4 opacity-0 group-hover:opacity-80 transition-all"
                                >
                                    <AppButton
                                        :icon="Square2StackIcon"
                                        @click="
                                            () => {
                                                form.content = reply.parts[0].text
                                                gemini.open = false
                                            }
                                        "
                                        size="sm"
                                    >
                                        Insert
                                    </AppButton>
                                </div>
                                <div style="white-space: pre-line" class="dark:text-neutral-200">{{ text.text }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <AppTextArea name="Your Prompt" v-model="gemini.prompt" />

                <div class="flex justify-end gap-2">
                    <AppButton :icon="SparklesIcon" @click="generateGeminiPrompt()" :forceLoading="gemini.loading" color="brand">Ask</AppButton>
                    <AppButton :icon="ArrowPathIcon" @click="newEntry()" :forceLoading="gemini.loading"> New </AppButton>
                    <AppButton
                        :icon="XMarkIcon"
                        @click="
                            () => {
                                gemini.open = false
                                gemini.prompt = ''
                            }
                        "
                    >
                        Close
                    </AppButton>
                </div>
            </div>
        </FormModal>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowPathIcon, CheckIcon, PaperAirplaneIcon, PencilIcon, SparklesIcon, Square2StackIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import ComboBox from '@/components/form/ComboBox.vue'
import AppToggle from '@/components/form/AppToggle.vue'
import AppButton from '@/components/form/AppButton.vue'
import ConfirmationPrompt from '@/components/modals/ConfirmationPrompt.vue'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppCheckbox from '@/components/form/AppCheckbox.vue'

import { Select } from '@/globalTypes'

import { reactive, ref, useTemplateRef, watch, nextTick } from 'vue'
import { useLocalStorage } from '@vueuse/core'
import { router, usePage } from '@inertiajs/vue3'
import { usePreferredColorScheme } from '@vueuse/core'
import { checkPermissions } from '@/utils'
import FormModal from '@/components/modals/FormModal.vue'
import axios from 'axios'

interface Errors {
    request_status_type_id?: string[]
    content?: string
    notify_email?: string[]
}

interface Chat {
    role: 'user' | 'model'
    parts: {
        text: string
    }[]
}

const { request_status_types, employee_id, latest_request_status_id } = defineProps<{
    employee_email: string
    request_status_types: Select[]
    latest_request_status_id: number
    employee_id: string
}>()

const form = reactive(initForm())

const confirm_modal = ref(false)
const confirm_storage_name = useLocalStorage('confirm_update_status', false)
const errors = ref<Errors>()
const apply_from_template = useLocalStorage('apply_from_template', true)
const theme = usePreferredColorScheme() // Automatically detects the user's preferred color scheme (light or dark)
const $page = usePage()
const gemini = reactive<{
    open: boolean
    prompt: string
    replies: Chat[]
    loading: boolean
}>({
    open: false,
    prompt: '',
    replies: [],
    loading: false
})
// const theme = useLocalStorage('theme', 'Light')
const scrollGemini = useTemplateRef('scrollGemini')

function notify_confirm() {
    if (!confirm_storage_name.value) {
        confirm_modal.value = true
    } else {
        notify()
    }
}

function notify() {
    router.put(
        route('dashboard.arta-id.update', { arta_id: employee_id }),
        {
            request_status_type_id: form.request_status_type?.id,
            content: form.content,
            notify_email: form.notify_email,
            type: 'request-status'
        },
        {
            preserveState: true,
            onError: (err) => {
                errors.value = err
            },
            onSuccess: () => {
                Object.assign(form, initForm())
            }
        }
    )
}

function initForm() {
    return {
        content: '',
        notify_email: true,
        request_status_type: request_status_types[0]
    }
}

watch(
    () => form.request_status_type,
    () => {
        if (form.request_status_type?.name === undefined) {
            return
        }
        getFromTemplate(form.request_status_type?.name || '')
    }
)

function getFromTemplate(message_title: string) {
    let apply_from_template = localStorage.getItem('apply_from_template')

    if (apply_from_template === 'false') {
        form.content = ''
        return
    }
    // @ts-ignore
    if ($page.props.auth?.user_settings.request_status_messages[message_title] !== undefined) {
        // @ts-ignore
        form.content = $page.props.auth?.user_settings.request_status_messages[message_title]
    } else {
        form.content = ''
    }
}

async function generateGeminiPrompt() {
    gemini.loading = true
    const { data } = await axios.post(route('dashboard.gemini.store'), {
        prompt: gemini.prompt,
        request_status_type: form.request_status_type.name
    })

    // @ts-ignore
    gemini.replies = data.chat
    gemini.loading = false
    gemini.prompt = ''

    await nextTick()

    if (scrollGemini.value) {
        const el = scrollGemini.value as HTMLElement
        el.scrollTo({
            top: el.scrollHeight,
            behavior: 'smooth'
        })
    }
}

async function openGemini() {
    gemini.open = true
    gemini.loading = true

    const { data } = await axios.get(route('dashboard.gemini.index'))

    // @ts-ignore
    gemini.replies = data.chat
    gemini.loading = false

    await nextTick()

    if (scrollGemini.value) {
        const el = scrollGemini.value as HTMLElement
        el.scrollTo({
            top: el.scrollHeight,
            behavior: 'smooth'
        })
    }
}

async function rephraseGemini() {
    gemini.loading = true
    const { data } = await axios.post(route('dashboard.gemini.store'), {
        prompt: `Can you reprhase this? ${form.content}`,
        request_status_type: form.request_status_type.name
    })

    // @ts-ignore
    gemini.replies = data.chat
    gemini.loading = false
    gemini.prompt = ''
    gemini.open = true

    await nextTick()

    if (scrollGemini.value) {
        const el = scrollGemini.value as HTMLElement
        el.scrollTo({
            top: el.scrollHeight,
            behavior: 'smooth'
        })
    }
}

async function newEntry() {
    gemini.prompt = ''
    gemini.loading = true
    const { data } = await axios.delete(route('dashboard.gemini.destroy', { gemini: 1 }))
    gemini.replies = []
    gemini.loading = false
}
</script>

<style>
.ql-toolbar {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
.ql-container {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    font-size: medium;
}

.quill-editor-dark .ql-toolbar {
    background-color: #262626; /* dark gray */
    border-color: #484848;
    color: #f9fafb;
}

.quill-editor-dark .ql-container {
    background-color: #262626;
    border-color: #484848;
    color: #f9fafb;
}

.quill-editor-dark .ql-editor {
    background-color: #262626;
    color: #c6cac6;
    /* font-size: medium; */
}

/* NOTE: TOOLBAR */

.quill-editor-dark .ql-toolbar button {
    color: #c6cac6; /* Light text color for buttons */
}

.quill-editor-dark .ql-toolbar button:hover {
    background-color: #525252; /* Slightly lighter background on hover */
}

.quill-editor-dark .ql-toolbar .ql-picker {
    color: #c6cac6; /* Light text color for dropdowns */
}

.quill-editor-dark .ql-toolbar .ql-picker-label {
    color: #c6cac6; /* Light text color for picker labels */
}

.quill-editor-dark .ql-toolbar .ql-picker-options {
    background-color: #404040; /* Dark background for dropdown options */
    color: #c6cac6; /* Light text color for dropdown options */
    border-color: #525252; /* Border color for dropdown */
    /* border-radius: 20px; */
}

.quill-editor-dark .ql-toolbar .ql-formats :hover {
    background-color: #111 !important;
    color: #e0e7e0 !important;
}

.quill-editor-dark .ql-toolbar .ql-formats .ql-active {
    background-color: #323d34 !important;
    border-radius: 5px !important;
}

.quill-editor-dark .ql-toolbar .ql-formats > button > svg {
    color: red !important;
}
</style>
