<template>
    <div>
        <AppSelect
            :name="title"
            :suggestions
            v-model="select"
            :color="select.name == 'Not Allowed' ? 'danger' : ''"
            :disabled="!checkPermissions(['Roles & Permissions/Update/All'], $page.props.auth?.permissions)"
        />
    </div>
</template>

<script setup lang="ts">
import AppSelect from '@/components/form/AppSelect.vue'

import { Select } from '@/globalTypes'
import { Permission } from './rolesPermissionsInt'

import { checkPermissions } from '@/utils'
import { ref, watch } from 'vue'

const { permissions, selected_permissions, role_id } = defineProps<{
    permissions: Permission[]
    title: string
    selected_permissions: Permission[]
    role_id: string
}>()

const $emit = defineEmits(['update'])

const suggestions = ref<Select[]>([
    {
        id: '',
        name: 'Not Allowed'
    },
    ...permissions.map((row) => {
        return {
            id: row.id,
            name: row.description
        }
    })
])

const select = ref<Select>(selected_permissions[0] ? { id: selected_permissions[0].id, name: selected_permissions[0].description } : suggestions.value[0])

watch(select, () => {
    // if it had a permission ofrom
    if (selected_permissions.length > 0) {
        $emit('update', selected_permissions[0].id, select.value.id, role_id)
    }
    // if blank
    else {
        $emit('update', '', select.value.id, role_id)
    }
})
</script>
