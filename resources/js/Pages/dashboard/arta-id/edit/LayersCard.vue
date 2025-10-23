<template>
    <BasicCard title="Layers" description="Layers" :icon="Bars3Icon">
        <div class="flex flex-col justify-between gap-2 h-full w-full">
            <ToolContent title="Layers" noBorder no_title>
                <div class="flex justify-betnween flex-col gap-1 rounded-xl">
                    <div
                        v-if="!removing_background && layers.length > 0"
                        v-for="item in layers"
                        :class="[
                            selected_layer === item ? 'bg-light-003 dark:bg-brand-800' : 'bg-white dark:bg-dark-002 hover:bg-light-002 dark:hover:bg-dark-003',
                            'rounded-md p-2 flex justify-between gap-1 items-center cursor-pointer first:rounded-t-xl last:rounded-b-xl'
                        ]"
                        @click="$emit('selectLayer', toRaw(item))"
                    >
                        <div class="flex gap-2">
                            <PhotoIcon v-if="item.type == 'image'" class="size-4 my-auto" />
                            <p v-else-if="item.type == 'text'" class="size-4 pl-1 mb-1 mx-auto italic">T</p>
                            <StopIcon v-else class="size-4 my-auto" />
                            <p class="font-semibold text-sm line-clamp-1">{{ item.layerName }}</p>
                        </div>
                        <div :class="[selected_layer === item ? 'opacity-100' : 'opacity-0', 'flex cursor-pointer transition-all gap-1']">
                            <ArrowDownIcon @click="$emit('moveBackward', item)" class="size-4" />
                            <ArrowUpIcon @click="$emit('moveForward', item)" class="size-4" />
                            <!-- <EyeIcon class="size-4" /> -->
                        </div>
                    </div>
                    <!-- Add spacing between rows -->
                    <BasicLoading v-else="layers.length == 0" />
                </div>
            </ToolContent>

            <div class="flex flex-wrap justify-end gap-2 mt-4">
                <AppButton :icon="ArrowUpIcon" size="sm" @click="$emit('showImportModal')">Import</AppButton>
            </div>
        </div>
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { ArrowUpIcon, Bars3Icon, PhotoIcon, StopIcon, ArrowDownIcon } from '@heroicons/vue/24/outline'
import { toRaw } from 'vue'
import ToolContent from './ToolContent.vue'
import BasicLoading from '@/components/loading/BasicLoading.vue'
import AppButton from '@/components/form/AppButton.vue'
import * as fabric from 'fabric'

interface Layer extends fabric.FabricObject {
    layerName: string
    type: string
}

defineProps<{
    removing_background: boolean
    layers: Layer[]
    selected_layer?: Layer
}>()

const $emit = defineEmits(['selectLayer', 'moveBackward', 'moveForward', 'showImportModal'])
</script>
