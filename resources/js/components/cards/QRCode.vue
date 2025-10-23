<template>
    <div class="relative group w-full">
        <div ref="qr_to_png" :class="['flex flex-col bg-white rounded-md truncate', noLabel ? 'h-min-[270px]' : 'h-auto']">
            <span class="text-center text-sm pt-1 -mb-1 dark:text-neutral-800 font-bold">{{ id }}</span>
            <div class="flex justify-center">
                <QrcodeVue :value="text_qr_(id)" :size="qr_size + 10" :margin="2" level="H" background="#ffffff" />
            </div>
            <div v-if="!noLabel" class="flex justify-start text-black font-medium line-clamp-1 max-w-[250px] text-wrap text-center mx-auto">
                {{ employee_name }}
            </div>
            <div v-if="!noLabel" class="flex justify-center text-gray-700 dark:font-normal text-wrap text-center max-w-[250px] mx-auto">
                {{ selected_office.name }}
            </div>
        </div>
        <div class="absolute group-hover:opacity-100 opacity-0 flex gap-2 transition-all bottom-2 right-2">
            <AppButton :icon="copyStatus.qr ? CheckIcon : Square2StackIcon" @click="copyQR()" class="shadow-lg" />
            <AppButton color="brand" :icon="ArrowDownOnSquareIcon" @click="print()" class="shadow-lg" />
            <AppButton v-if="!noRemove" color="danger" :icon="TrashIcon" @click="$emit('remove')" class="shadow-lg" iconOnly />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ArrowDownOnSquareIcon, TrashIcon, Square2StackIcon, CheckIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import QrcodeVue from 'qrcode.vue'

import { onMounted, reactive, useTemplateRef } from 'vue'
import { toPng } from 'html-to-image'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const $props = defineProps<{
    id: string
    employee_name: string
    selected_office: { name: string }
    status: { name: string }
    noLabel?: boolean
    noImage?: boolean
    noRemove?: boolean
}>()
const $emit = defineEmits(['remove'])
const $page = usePage()
const qr_to_png = useTemplateRef('qr_to_png')
const copyStatus = reactive({
    qr: false
})
const qr_size = ref(200)

// const image = `https://qr.cmuohrm.site/images/ohrm.png`
const image = `${$page.props.base_url}/images/ohrm.png`
// const image = `${import.meta.env.VITE_URL}images/ohrm.png`

function text_qr_(id: string) {
    return `${$page.props.base_url}/l/${id}`
}

async function print() {
    if (qr_to_png.value) {
        const dataUrl = await toPng(qr_to_png.value, { cacheBust: true })

        const link = document.createElement('a')
        link.download = `QR ${$props.employee_name} - ${$props.selected_office.name}.png`
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

function updateQRSize() {
    qr_size.value = qr_to_png.value?.clientWidth ?? 200
}

onMounted(() => {
    updateQRSize()

    window.addEventListener('resize', updateQRSize)
})
</script>
