<template>
    <div class="flex flex-col xl:grid grid-cols-3 dark:bg-dark-001 gap-4">
        <Unedited class="col-span-3" />
        <FormCard :user="show_data" :departments :roles class="col-start-2" />
    </div>
</template>

<script setup lang="ts">
import FormCard from './FormCard.vue'

import { Select, User } from '@/globalTypes'
import { Role } from '../roles-permissions/rolesPermissionsInt'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    show_data: User
    departments: Select[]
    roles: Role[]
}>()
</script>
