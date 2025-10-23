<template>
    <BasicCard title="Tools" description="Tools" :icon="WrenchIcon">
        <div class="flex flex-col justify-between gap-4 h-full w-full">
            <ToolContent title="Position" noBorder>
                <div class="flex justify-betnween gap-2 flex-wrap">
                    <ToolInput v-model="selected_x" @input="$emit('updateObjectSize')" pre_fix="X" />
                    <ToolInput v-model="selected_y" @input="$emit('updateObjectSize')" pre_fix="Y" />
                    <ToolInput v-model="selected_angle" @input="$emit('updateObjectSize')" pre_fix="A" />
                </div>
            </ToolContent>

            <ToolContent title="Layout">
                <div class="flex flex-wrap gap-2">
                    <ToolInput v-model="selected_height" @input="$emit('updateObjectSize')" pre_fix="H" />
                    <ToolInput v-model="selected_width" @input="$emit('updateObjectSize')" pre_fix="W" />
                </div>
            </ToolContent>

            <ToolContent title="Actions">
                <div class="flex flex-wrap gap-2">
                    <AppButton @click="$emit('deleteLayer')" :icon="TrashIcon" color="danger" size="sm">Delete Layer</AppButton>
                </div>
            </ToolContent>

            <ToolContent v-if="selected_layer && selected_layer.type == 'text'" title="Text ">
                <div class="flex flex-col gap-1">
                    <AppTextArea name="Text value" v-model="selected_text_value" @input="$emit('updateTextValue')" />

                    <div class="flex gap-1 mt-1">
                        <button
                            @click="$emit('toggleBold')"
                            :class="[selected_layer.fontWeight == 'bold' ? 'bg-brand-100 dark:bg-neutral-800' : 'bg-white dark:bg-neutral-700', 'rounded p-1']"
                        >
                            <BoldIcon class="size-4" />
                        </button>
                        <button
                            @click="$emit('toggleItalic')"
                            :class="[selected_layer.fontStyle == 'italic' ? 'bg-brand-100 dark:bg-neutral-800' : 'bg-white dark:bg-neutral-700', 'rounded p-1']"
                        >
                            <ItalicIcon class="size-4" />
                        </button>
                    </div>
                </div>
            </ToolContent>

            <ToolContent v-else-if="selected_layer && selected_layer.type == 'image'" title="Image">
                <div class="flex flex-col gap-1">
                    <div class="flex gap-1">
                        <AppButton @click="$emit('flipHorizontal')" :icon="ArrowsRightLeftIcon" iconOnly />
                        <AppButton @click="$emit('flipVertical')" :icon="ArrowsUpDownIcon" iconOnly />

                        <!-- <AppButton @click="startCrop()" size="sm" :icon="ScissorsIcon" iconOnly>Crop</AppButton> -->
                    </div>
                    <AppButton @click="$emit('removeBg')" :icon="SparklesIcon" size="sm" :forceLoading="removing_background">
                        {{ removing_background ? 'Removing... 😎' : 'Remove Background' }}
                    </AppButton>
                </div>
            </ToolContent>

            <div class="flex gap-2 mt-4 ml-auto flex-wrap">
                <AppButton :icon="ArrowLeftIcon" :href="route('dashboard.arta-id.show', { arta_id: id })" size="sm">Back</AppButton>
                <AppButton :icon="CircleStackIcon" @click="$emit('exportAsJson')" size="sm">Save</AppButton>
                <AppButton :icon="ArrowDownOnSquareIcon" @click="$emit('exportAsJpg')" size="sm">JPG</AppButton>
                <AppButton :icon="ArrowDownOnSquareIcon" @click="$emit('exportAsSvg')" size="sm">SVG</AppButton>
                <AppButton :icon="ArrowUpIcon" @click="$emit('saveToOnlineFile')" size="sm">Save to Online File</AppButton>
                <AppButton :icon="PhotoIcon" @click="$emit('openToPhotopea')" size="sm">Edit in Photopea</AppButton>
            </div>
        </div>
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import {
    ArrowDownOnSquareIcon,
    ArrowLeftIcon,
    ArrowsRightLeftIcon,
    ArrowsUpDownIcon,
    ArrowUpIcon,
    CircleStackIcon,
    PhotoIcon,
    SparklesIcon,
    TrashIcon,
    WrenchIcon,
    ItalicIcon,
    BoldIcon
} from '@heroicons/vue/24/outline'
import type { FabricObject } from 'fabric'
import AppButton from '@/components/form/AppButton.vue'
import ToolContent from './ToolContent.vue'
import ToolInput from './ToolInput.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'

interface Layer extends FabricObject {
    fontWeight: string
    fontStyle: string
}

defineProps<{
    selected_layer: Layer
    id: string
    removing_background: boolean
}>()

const selected_text_value = defineModel<string>('selected_text_value', { required: true })
const selected_x = defineModel<number>('selected_x', { required: true })
const selected_y = defineModel<number>('selected_y', { required: true })
const selected_angle = defineModel<number>('selected_angle', { required: true })
const selected_height = defineModel<number>('selected_height', { required: true })
const selected_width = defineModel<number>('selected_width', { required: true })

const $emit = defineEmits([
    'updateObjectSize',
    'deleteLayer',
    'updateTextValue',
    'toggleBold',
    'toggleItalic',
    'flipHorizontal',
    'flipVertical',
    'removeBg',
    'exportAsJson',
    'exportAsJpg',
    'exportAsSvg',
    'saveToOnlineFile',
    'openToPhotopea'
])
</script>
