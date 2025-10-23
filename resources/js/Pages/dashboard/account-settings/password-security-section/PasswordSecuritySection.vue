<template>
    <div class="grid grid-cols-3 gap-4">
        <UpdatePasswordCard class="col-span-3 lg:col-span-1" />
        <SessionCard :sessions class="col-span-3 lg:col-start-2" />
    </div>
</template>

<script setup lang="ts">
import UpdatePasswordCard from './UpdatePasswordCard.vue'
import SessionCard from './SessionCard.vue'

import { UserSession } from '../accountSettingsInt'

defineProps<{
    sessions: UserSession[]
}>()
</script>
