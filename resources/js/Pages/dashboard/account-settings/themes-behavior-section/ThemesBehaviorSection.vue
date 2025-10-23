<template>
    <div class="grid grid-cols-2 gap-4">
        <ThemesCard :themes="user_settings.themes" class="col-span-2 lg:col-span-1" />
        <OtherCard :card="user_settings.card" class="col-span-2 lg:col-span-1" />
    </div>
</template>

<script setup lang="ts">
import ThemesCard from './ThemesCard.vue'
import OtherCard from './OtherCard.vue'

import { UserSettings } from '../accountSettingsInt'

defineProps<{
    user_settings: UserSettings
}>()
</script>
