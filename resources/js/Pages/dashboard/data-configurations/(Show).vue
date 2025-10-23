<template>
    <div :class="[form_title && 'xl:grid-cols-2 2xl:grid-cols-3', 'grid grid-cols-1 gap-4 dark:bg-dark-001']">
        <Unedited class="col-span-3" />

        <FormCard v-if="form_title" v-model:title="form_title" v-model:name="form.name" v-model:id="form.id" @cancel="Object.assign(form, initForm())" />

        <div class="px-4 sm:p-6 lg:p-8 py-8 bg-white dark:bg-dark-002 sm:rounded-2xl 2xl:col-span-2">
            <div class="flex flex-col sm:flex-row space-y-4 justify-between sm:items-center mb-4">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-200">{{ route().params.data_configuration }}</h1>
                </div>
                <div class="flex gap-2 justify-end w-full sm:w-auto">
                    <AppButton
                        :icon="PlusIcon"
                        color="brand"
                        @click="openForm('Add')"
                        :disabled="!checkPermissions(['Data Configuration/Create/All'], $page.props.auth?.permissions)"
                    >
                        Add Data
                    </AppButton>
                    <AppButton :icon="ArrowLeftIcon" :href="route('dashboard.data-configurations.index')">Back</AppButton>
                </div>
            </div>
            <div class="flex">
                <p class="text-sm text-brand-400 dark:text-neutral-300">You cannot delete this while the user is still using the data.</p>
            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-dark-003">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-neutral-200 sm:pl-0">
                                        Data Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-4 pr-3 text-start text-sm font-semibold text-gray-900 dark:text-neutral-200 sm:pl-0"
                                        width="150"
                                    >
                                        Used by users
                                    </th>

                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0" width="10">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-600">
                                <tr v-for="item in show_data" :key="item.name" class="group dark:text-neutral-200">
                                    <td class="pl-3">
                                        {{ item.name }}
                                    </td>
                                    <td class="text-start">
                                        {{ item.count }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                        <div class="flex gap-2 justify-end opacity-50 group-hover:opacity-100 transition-all">
                                            <AppButton
                                                size="sm"
                                                :icon="PencilIcon"
                                                @click="openForm('Update', item)"
                                                :disabled="!checkPermissions(['Data Configuration/Update/All'], $page.props.auth?.permissions)"
                                            >
                                                Edit
                                            </AppButton>
                                            <AppButton
                                                v-if="item.count == 0"
                                                size="sm"
                                                color="danger"
                                                :icon="XMarkIcon"
                                                @click="promptRemoveData(item.id)"
                                                :disabled="!checkPermissions(['Data Configuration/Delete/All'], $page.props.auth?.permissions)"
                                            >
                                                Delete
                                            </AppButton>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <RemovalPrompt title="Delete?" confirmMessage="Delete" :confirmIcon="TrashIcon" v-model="show_modal" type="error" @confirm="deleteItem()">
                <b class="text-red-500">Warning!</b>
                <li class="text-red-500 font-semibold">Make sure that NO "user" is using this data.</li>
                <li class="text-brand-500 font-semibold">You cannot undo this action.</li>
            </RemovalPrompt>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import { ArrowLeftIcon, PencilIcon, PlusIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import FormCard from './FormCard.vue'
import RemovalPrompt from '@/components/modals/RemovalPrompt.vue'

import { DataConfiguration } from './dataConfigurationsInt'

import { router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import { checkPermissions } from '@/utils'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    show_data: DataConfiguration[]
}>()

const show_modal = ref(false)
const delete_id = ref('')
const form_title = ref('')

const form = reactive<DataConfiguration>(initForm())

function initForm() {
    return {
        name: '',
        id: '',
        count: 0
    }
}

function promptRemoveData(id: string) {
    show_modal.value = true
    delete_id.value = id
}

function openForm(title: string, item?: DataConfiguration) {
    form_title.value = title
    if (item) {
        Object.assign(form, item)
    } else {
        Object.assign(form, initForm())
    }

    window.scrollTo({ top: 0, behavior: 'smooth' })
}

function deleteItem() {
    router.delete(route('dashboard.data-configurations.destroy', { data_configuration: route().params.data_configuration }), { data: { id: delete_id.value } })
}
</script>
