<template>
    <div class="flex flex-col gap-2 lg:grid grid-cols-3 dark:bg-dark-001">
        <Unedited class="col-span-3" />

        <BasicCard title="Create Role" description="Create custom role." :icon="StarIcon" class="lg:col-start-2">
            <form @submit.prevent="createRole()" class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <AppInput v-model="form.name" name="Name" :error="$page.props.errors.name" />
                    <AppTextArea v-model="form.description" name="Description" :error="$page.props.errors.description" />
                </div>

                <div class="flex flex-col gap-2 sm:flex-row justify-end">
                    <AppButton :icon="CircleStackIcon" color="brand">Create</AppButton>
                    <AppButton type="button" :icon="XMarkIcon" :href="route('dashboard.roles-permissions.index')">Cancel</AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/cards/BasicCard.vue'
import { CircleStackIcon, StarIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'

import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import { defaultRouterState } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

const form = reactive({
    name: '',
    description: ''
})

function createRole() {
    router.post(route('dashboard.roles-permissions.store'), form, defaultRouterState())
}
</script>
