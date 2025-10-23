<template>
    <div class="">
        <BasicCard :title="$model_title" description="Hlleo" :icon="PencilIcon">
            <form @submit.prevent="addOrUpdate()" class="flex flex-col gap-4 py-4">
                <div class="flex flex-col gap-4">
                    <AppInput name="Data Name" v-model="$model_name" />
                </div>
                <div class="flex flex-col gap-2 mt-2 sm:flex-row justify-end">
                    <AppButton
                        color="brand"
                        :icon="CircleStackIcon"
                        :disabled="!checkPermissions(['Data Configuration/Update/All', 'Data Configuration/Create/All'], $page.props.auth?.permissions)"
                    >
                        {{ $model_title }}
                    </AppButton>
                    <AppButton
                        @click="
                            () => {
                                $model_title = ''
                                $emit('cancel')
                            }
                        "
                        :icon="XMarkIcon"
                        type="button"
                    >
                        Cancel
                    </AppButton>
                </div>
            </form>
        </BasicCard>
    </div>
</template>

<script setup lang="ts">
import AppButton from '@/components/form/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import BasicCard from '@/components/cards/BasicCard.vue'

import { router } from '@inertiajs/vue3'
import { checkPermissions } from '@/utils'
import { CircleStackIcon, PencilIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const $model_title = defineModel<string>('title', { required: true })
const $model_name = defineModel<string>('name', { required: true })
const $model_id = defineModel<string>('id', { required: true })
const $emit = defineEmits(['cancel'])

async function addOrUpdate() {
    if ($model_title.value == 'Update') {
        router.put(route('dashboard.data-configurations.update', { data_configuration: route().params.data_configuration }), {
            id: $model_id.value,
            name: $model_name.value
        })
    } else {
        router.post(route('dashboard.data-configurations.store'), {
            model_name: route().params.data_configuration,
            name: $model_name.value
        })
    }

    $model_title.value = ''
}
</script>
