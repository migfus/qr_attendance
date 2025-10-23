<template>
    <div>
        <div class="flex flex-col gap-2 items-center">
            <div class="flex flex-col gap-4 items-center bg-light-001 dark:bg-dark-002 rounded-t-xl rounded p-4 w-full">
                <img
                    :src="show_data.files.length > 0 ? show_data.files[show_data.files.length - 2].thumbnail_url : ''"
                    class="h-auto w-full mx-auto rounded-xl cursor-pointer border border-brand-200 dark:border-dark-003"
                    @click="showPreviewImage()"
                />
                <div class="flex justify-end gap-2 mx-auto w-full">
                    <AppButton
                        :icon="PhotoIcon"
                        class="transition-all"
                        :href="route('dashboard.arta-id.edit', { arta_id: show_data.id })"
                        :disabled="!checkPermissions(['ARTA ID/Update/All'], $page.props.auth?.permissions)"
                    >
                        Edit</AppButton
                    >
                    <AppButton
                        :icon="copyStatus.image ? CheckIcon : Square2StackIcon"
                        @click="
                            copyImage(
                                show_data.files.length > 0 ? show_data.files[0].file_url : '',
                                () => (copyStatus.image = true),
                                () => (copyStatus.image = false)
                            )
                        "
                        class="transition-all"
                    >
                        {{ copyStatus.image ? 'Copied' : 'Copy' }}
                    </AppButton>
                    <AppButton
                        :icon="ArrowDownOnSquareIcon"
                        color="brand"
                        @click="downloadImage(show_data.files.length > 0 ? show_data.files[0].file_url : '')"
                    >
                        Download
                    </AppButton>
                </div>
            </div>

            <div class="bg-light-001 dark:bg-dark-002 rounded p-4 w-full flex justify-between">
                <div>
                    <p class="font-semibold text-brand-800 dark:text-neutral-200">{{ employeeFullName(show_data, false) }}</p>
                    <p class="font-semibold text-brand-700 dark:text-neutral-300">{{ show_data.department.name }}</p>
                    <p class="font-semibold text-brand-600 dark:text-neutral-300">{{ show_data.position.name }}</p>
                </div>
                <div>
                    <p class="text-brand-600 dark:text-neutral-300 text-xs p-2 dark:bg-dark-001 rounded-xl font-semibold px-3">
                        {{ show_data.employee_status.name }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col bg-light-001 dark:bg-dark-002 rounded p-4 w-full gap-2 justify-center" ref="qrParentRef">
                <div ref="qr_code" class="w-full h-auto object-fit mx-auto mb-2">
                    <!-- <QRCodeVue3
                        :value="`${$page.props.base_url}/qr/${show_data.quick_response_codes[0].id}`"
                        :key="show_data.quick_response_codes[0].id"
                        :width="300"
                        :height="300"
                        :image-options="{
                            hideBackgroundDots: false,
                            imageSize: 0.4,
                            margin: 10
                        }"
                        :corners-square-options="{ type: 'square', color: '#000' }"
                        :dots-options="{
                            type: 'square',
                            color: '#000'
                        }"
                        :qrOptions="{ errorCorrectionLevel: 'H' }"
                    /> -->

                    <QrCodeVue
                        :value="`${$page.props.base_url}/qr/${show_data.quick_response_codes[0].id}`"
                        :key="show_data.quick_response_codes[0].id"
                        :size="qr_size"
                        :margin="2"
                        :image-options="{
                            hideBackgroundDots: false,
                            imageSize: 0.4,
                            margin: 10
                        }"
                        :corners-square-options="{ type: 'square', color: '#000' }"
                        :dots-options="{
                            type: 'square',
                            color: '#000'
                        }"
                        :qrOptions="{ errorCorrectionLevel: 'H' }"
                        level="H"
                        :image-settings="{
                            src: '/images/cmu.png',
                            width: qr_size / 7,
                            height: qr_size / 7,
                            excavate: true
                        }"
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <!-- <AppButton
                            :icon="copyStatus.qrid ? CheckIcon : Square2StackIcon"
                            @click="copyQRID_(show_data.quick_response_codes[0].id)"0
                            class="transition-all"
                        >
                            {{ copyStatus.qrid ? 'Copied' : 'Copy ID' }}
                        </AppButton> -->
                    <AppButton :icon="copyStatus.qr ? CheckIcon : Square2StackIcon" @click="copyQR()" class="transition-all">
                        {{ copyStatus.qr ? 'Copied' : 'Copy QR' }}
                    </AppButton>
                    <AppButton :icon="ArrowDownOnSquareIcon" color="brand" @click="downloadQR()">Download</AppButton>
                </div>
            </div>

            <div class="flex flex-col rounded p-4 bg-light-001 dark:bg-dark-002 dark:text-neutral-200 w-full">
                <p class="font-bold">{{ show_data.claim_type.name }}</p>
                <p>{{ `${show_data.claim_type.price} pesos` }}</p>
                <img :src="show_data.claim_type.image_url" />
            </div>

            <div class="flex flex-col mr-auto rounded rounded-b-xl bg-light-001 dark:bg-dark-002 w-full p-4">
                <div class="text-brand-700 dark:text-neutral-300 font-semibold mr-auto">
                    <LinkIcon class="size-4 inline mr-1" />
                    22 Lanyard left
                </div>
                <div class="text-brand-700 dark:text-neutral-300 font-semibold mr-auto">
                    <CreditCardIcon class="size-4 inline mr-1" /> 132 Printable Card left
                </div>
            </div>
        </div>

        <PhotoPreviewModal v-model:show="photo_preview_modal_show" v-model:photo="photo_preview_file" :employee_id="show_data.id" no_navigation no_details />
    </div>
</template>

<script setup lang="ts">
import { ArrowDownOnSquareIcon, CheckIcon, CreditCardIcon, LinkIcon, PhotoIcon, Square2StackIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import PhotoPreviewModal from '@/components/modals/PhotoPreviewModal.vue'
import QrCodeVue from 'qrcode.vue'

import { File } from '@/globalTypes'
import { Employee } from '../artaIdInt'

import { reactive, ref, useTemplateRef, onMounted } from 'vue'
import { toPng } from 'html-to-image'
import { employeeFullName, checkPermissions, copyImage } from '@/utils'
import { usePage } from '@inertiajs/vue3'

const { show_data } = defineProps<{
    show_data: Employee
}>()
const $page = usePage()
const qrParentRef = useTemplateRef<HTMLElement>('qrParentRef')
const qr_size = ref(300)

const qr_to_png = useTemplateRef('qr_code')
const copyStatus = reactive({
    image: false,
    qr: false,
    qrid: false
})
const photo_preview_modal_show = ref(false)
const photo_preview_file = ref<File | null>(null)

function downloadImage(file_url: string) {
    const link = document.createElement('a')
    link.href = file_url
    link.download = `${show_data.last_name}-${show_data.first_name}.jpg` // Suggested file name
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}

async function downloadQR() {
    if (qr_to_png.value) {
        const dataUrl = await toPng(qr_to_png.value, { cacheBust: true })

        const link = document.createElement('a')
        link.download = `QR ${show_data.last_name} - ${show_data.first_name}.png`
        link.href = dataUrl
        link.click()
    }
}

async function copyQR() {
    if (qr_to_png.value) {
        const dataUrl = await toPng(qr_to_png.value, { cacheBust: true })

        // Convert data URL to Blob
        const response = await fetch(dataUrl)
        const blob = await response.blob()

        // Create a clipboard item and copy the image
        const item = new ClipboardItem({ 'image/png': blob })
        await navigator.clipboard.write([item])
        copyStatus.qr = true
    }
}

function showPreviewImage() {
    photo_preview_modal_show.value = true
    photo_preview_file.value = show_data.files[show_data.files.length - 2]
}

onMounted(() => {
    updateQRSize()
    window.addEventListener('resize', updateQRSize)
})

function updateQRSize() {
    qr_size.value = (qrParentRef.value?.clientWidth ?? 300) - 35
}
</script>
