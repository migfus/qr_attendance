<template>
    <div>
        <BasicCard title="Request ARTA ID" description="Online Request ARTA ID" :icon="IdentificationIcon">
            <div class="flex flex-col mb-4">
                <label class="my-2">Please upload your picture based on guidelines.</label>
                <ul class="ml-4">
                    <li>Must be in formal attire.</li>
                    <li>Must be taken in half body.</li>
                    <li>Recommended in a white background</li>
                </ul>

                <img :src="image ? image : `${$page.props.base_url}/images/formal-attire-sample.jpg`" class="object-cover my-2" />
            </div>
            <div>
                <div class="flex flex-col gap-3 mb-4">
                    <AppButton color="brand" :icon="ArrowUpOnSquareIcon" @click="show_modal = true">Upload</AppButton>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-4">
                    <AppButton :icon="ArrowLeftIcon" type="button" @click="$emit('back')">Back</AppButton>
                    <AppButton color="brand" :icon="ArrowRightIcon" @click="verify()" :disabled="!image">Next</AppButton>
                </div>
            </div>
        </BasicCard>
        <UploadARTAIDPicture :size="[800, 800]" name="Crop the picture" :ratio="1" v-model="image" v-model:raw="raw_image" v-model:show="show_modal" />
    </div>
</template>

<script setup lang="ts">
import { IdentificationIcon, ArrowRightIcon, ArrowLeftIcon, ArrowUpOnSquareIcon } from '@heroicons/vue/24/outline'
import BasicCard from '@/components/cards/BasicCard.vue'
import AppButton from '@/components/form/AppButton.vue'
import UploadARTAIDPicture from '@/components/modals/UploadARTAIDPicture.vue'

import { ref } from 'vue'

const image = defineModel<string>('image', { required: true })
const raw_image = defineModel<string>('raw_image', { required: true })
const step = defineModel<number>('step')

const $emit = defineEmits(['back', 'confirm'])

const show_modal = ref(false)

function verify() {
    step.value = 4
}
</script>
