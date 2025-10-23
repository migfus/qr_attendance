<template>
    <div class="flex flex-col gap-2 bg-white dark:bg-dark-002 p-4 rounded-xl">
        <div class="flex justify-between">
            <div class="flex flex-col items-start">
                <div class="flex gap-2 items-center">
                    <WrenchIcon class="size-4 inline" />
                    <label class="font-medium text-medium text-neutral-700 dark:text-neutral-200"> {{ role.display_name }}</label>
                </div>
                <div class="flex gap-2">
                    <div class="flex -space-x-1 overflow-hidden p-0.5">
                        <img
                            v-for="user in role.users"
                            :key="user.id"
                            :src="user.avatar_url"
                            :alt="user.name"
                            class="inline-block size-4 rounded-full ring-2 dark:ring-neutral-500 ring-neutral-300"
                        />
                    </div>
                </div>
            </div>
            <div class="flex gap-2 items-start">
                <AppButton :icon="UsersIcon" @click="open_sub = 'users'">
                    <label class="hidden sm:inline"> Users </label>
                    <span v-if="role.users.length > 0" class="bg-neutral-200 px-2 py-0.5 rounded-xl text-brand-700 text-xs">
                        {{ role.users.length }}
                    </span>
                </AppButton>
                <AppButton :icon="KeyIcon" color="brand" @click="open_sub = 'permissions'">
                    <label class="hidden sm:inline cursor-pointer"> Permissions </label>
                    <span v-if="role.permissions.length > 0" class="cursor-pointer bg-neutral-200 px-2 py-0.5 rounded-xl text-brand-700 text-xs">
                        {{ role.permissions.length }}
                    </span>
                </AppButton>
                <AppButton :icon="PencilIcon" :href="route('dashboard.roles-permissions.edit', { roles_permission: role.id })">
                    <label class="hidden sm:inline"> Edit </label>
                </AppButton>
            </div>
        </div>

        <BasicTransition>
            <UsersCardContent v-if="open_sub == 'users'" :users="role.users" @close="open_sub = ''" @transfer="openTransferModal" />

            <div v-else-if="open_sub == 'permissions'" class="flex flex-col dark:bg-dark-001 p-4 rounded-xl">
                <h4 class="font-semibold mb-4 dark:text-light-001">Permissions</h4>
                <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div v-for="item in permission_modules" class="flex flex-col gap-2 p-4 bg-brand-50 dark:bg-dark-002 rounded-xl">
                        <div class="flex font-semibold dark:text-light-001">{{ item.name }}</div>
                        <div v-for="type in permission_types" class="flex flex-col gap-4 dark:text-light-002">
                            <PermissionSelect
                                v-if="item.permissions.filter((row) => row.permission_type.name == type.name).length > 0"
                                :title="type.name"
                                :permissions="item.permissions.filter((row) => row.permission_type.name == type.name)"
                                :selected_permissions="
                                    role.permissions
                                        .filter((row) => row.permission_module.name == item.name)
                                        .filter((row) => row.permission_type.name == type.name)
                                "
                                :role_id="role.id"
                                @update="updateRole"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end mt-4">
                    <AppButton @click="open_sub = ''" :icon="XMarkIcon">Close </AppButton>
                </div>
            </div>
        </BasicTransition>

        <FormModal v-model="open_modal" title="Transfer To" :icon="UserIcon" size="lg" description="You will transfer the user's role.">
            <div class="flex flex-col gap-4">
                <div class="flex gap-2 items-center">
                    <img :src="transfer_user.avatar_url" class="size-4 inline rounded-full" />
                    <label class="font-semibold">{{ transfer_user.name }}</label>
                </div>

                <div class="flex flex-col gap-4 bg-brand-50 dark:bg-dark-001 p-4 rounded-xl">
                    <div v-for="item in roles.filter((row) => row.id != role.id)" class="flex justify-between">
                        <div class="font-semibold">{{ item.display_name }}</div>
                        <AppButton :icon="ArrowRightIcon" size="sm" color="brand" @click="transferUser(item.id)">Transfer</AppButton>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end">
                    <AppButton :icon="XMarkIcon" @click="open_modal = false">Cancel</AppButton>
                </div>
            </div>
        </FormModal>
    </div>
</template>

<script setup lang="ts">
import { ArrowRightIcon, KeyIcon, PencilIcon, UserIcon, UsersIcon, WrenchIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import BasicTransition from '@/components/transitions/BasicTransition.vue'
import AppButton from '@/components/form/AppButton.vue'
import UsersCardContent from './UsersCardContent.vue'
import PermissionSelect from './PermissionSelect.vue'
import FormModal from '@/components/modals/FormModal.vue'

import { PermissionModule, PermissionType, Role } from './rolesPermissionsInt'

import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { defaultRouterState } from '@/utils'

defineProps<{
    role: Role
    roles: Role[]
    permission_modules: PermissionModule[]
    permission_types: PermissionType[]
}>()

const open_sub = ref<'users' | 'permissions' | ''>(
    route().params.search_filter == 'Users' ? 'users' : route().params.search_filteer == 'Permissions' ? 'permissions' : ''
)
const transfer_user = reactive<{
    id: string
    name: string
    avatar_url: string
}>(initTranferUser())
const open_modal = ref<boolean>(false)

function initTranferUser() {
    return {
        id: '',
        name: '',
        avatar_url: ''
    }
}

function openTransferModal(user_id: string, user_name: string, avatar_url: string) {
    Object.assign(transfer_user, {
        id: user_id,
        name: user_name,
        avatar_url: avatar_url
    })
    open_modal.value = true
}

function updateRole(from_permission_id: string, to_permission_id: string, role_id: string) {
    router.put(
        route('dashboard.roles-permissions.update', { roles_permission: role_id }),
        {
            type: 'updateRole',
            from_permission_id,
            to_permission_id
        },
        defaultRouterState()
    )
}

function transferUser(role_id: string) {
    router.put(
        route('dashboard.roles-permissions.update', { roles_permission: transfer_user.id }),
        {
            type: 'transferUser',
            role_id
        },
        {
            onFinish: () => {
                Object.assign(transfer_user, initTranferUser())
                open_modal.value = false
            },
            ...defaultRouterState()
        }
    )
}
</script>
