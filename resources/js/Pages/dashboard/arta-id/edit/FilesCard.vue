<template>
    <BasicCard title="Files" description="Files uploaded" :icon="Bars3Icon">
        <div class="flex flex-col justify-between gap-2 h-full w-full">
            <ToolContent title="Files" noBorder no_title>
                <div class="flex gap-2 flex-wrap rounded-xl">
                    <div
                        v-for="item in files"
                        :class="[
                            selected_file === item ? 'bg-brand-50 dark:bg-brand-800' : 'bg-white dark:bg-dark-002 hover:bg-light-002 dark:hover:bg-dark-003',
                            'rounded-lg p-2 flex flex-col gap-2 items-center cursor-pointer size-24'
                        ]"
                        @click="$emit('selectFile', item)"
                    >
                        <div class="flex flex-col gap-1 items-center">
                            <img :src="item.thumbnail_url" class="object-cover rounded size-14 mx-auto" />
                            <span class="text-sm text-brand-600 dark:text-neutral-300 line-clamp-1">{{ item.file_type.name }}</span>
                        </div>
                    </div>
                </div>
            </ToolContent>

            <div v-if="selected_file" class="flex justify-end gap-2 mt-4">
                <AppButton
                    v-if="is_copied"
                    :icon="CheckIcon"
                    size="sm"
                    @click="
                        copyImage(
                            selected_file.file_url,
                            () => (is_copied = true),
                            () => (is_copied = false)
                        )
                    "
                >
                    Copied
                </AppButton>
                <AppButton
                    v-else
                    :icon="Square2StackIcon"
                    size="sm"
                    @click="
                        copyImage(
                            selected_file.file_url,
                            () => (is_copied = true),
                            () => (is_copied = false)
                        )
                    "
                >
                    Copy
                </AppButton>
                <AppButton :icon="PlusIcon" size="sm" @click="$emit('importImage')">Add</AppButton>
            </div>
        </div>
    </BasicCard>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { Bars3Icon, CheckIcon, PlusIcon, Square2StackIcon } from '@heroicons/vue/24/outline'
import { copyImage } from '@/utils'
import { ref } from 'vue'
import { File } from '@/globalTypes'
import AppButton from '@/components/form/AppButton.vue'
import ToolContent from './ToolContent.vue'

defineProps<{
    files: File[]
    selected_file?: any
}>()

const is_copied = ref(false)

const $emit = defineEmits(['importImage', 'selectFile'])
</script>
