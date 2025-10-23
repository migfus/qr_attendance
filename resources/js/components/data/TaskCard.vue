<template>
    <div>
        <AppButton
            :href="route('dashboard.manage-template-tasks.edit', { manage_template_task: task.task_template_id ?? 0 })"
            class="flex flex-col gap-1 w-full cursor-pointer"
            noLoading
        >
            <div class="flex justify-between">
                <div class="text-brand-700">
                    <span class="text-sm text-brand-400">#{{ task.id.toString().substring(0, 6) }}</span
                    >-{{ task.id.toString().substring(6) }}
                </div>
                <EllipsisVerticalIcon class="h-4 w-4" />
            </div>

            <div class="flex justify-between">
                <div>
                    <div v-html="task.task_status.hero_icon.content" class="w-4 h-4 inline-block pt-[3px] mr-1 text-brand-600"></div>
                    <span class="inline">{{ task.task_status.past_name }}</span>
                </div>
                <div class="justify-end text-sm truncate">{{ dateTimeFormatted(task.created_at) }}</div>
            </div>

            <div class="flex justify-between">
                <span class="truncate">{{ task.task_template.name }}</span>
                <div class="text-sm text-brand-400 flex justify-start">
                    <div v-html="task.task_priority.hero_icon.content" class="w-4 h-4 inline mr-1 mt-[2px] rounded-full text-brand-600"></div>
                    {{ task.task_priority.name }}
                </div>
            </div>

            <div class="flex justify-end">
                <div v-if="task.task_user_access" class="text-sm flex justify-end">
                    <img :src="task.task_user_access.user.avatar_url" class="w-3 h-3 inline mr-2 mt-[4px] rounded-full text-green-800" />
                    <span class="truncate">{{ task.task_user_access.user.name }}</span>
                </div>
                <div v-else class="text-sm flex sm:justify-end">
                    <QuestionMarkCircleIcon class="w-4 h-4 inline mr-2 mt-[2px] rounded-full text-green-800" />
                    <span class="truncate">Unassigned</span>
                </div>
            </div>

            <!-- NOTE: ROW FINAL -->
            <div v-if="task.message" class="flex bg-brand-50 px-4 py-2 rounded-xl col-span-3 text-sm">
                {{ task.message }}
            </div>
        </AppButton>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import { EllipsisVerticalIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/solid'

import { Task } from '@/globalTypes'
import { dateTimeFormatted } from '@/utils'

defineProps<{
    task: Task
}>()
</script>
