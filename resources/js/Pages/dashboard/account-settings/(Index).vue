<template>
    <div class="dark:bg-dark-001 gap-4 flex flex-col">
        <Unedited />

        <TabSection :selected="Number(route().params.account_setting)" :data="tabs" disableLoading />

        <NotificationSection v-if="Number(route().params.account_setting) === 0" :user_settings />
        <ProfileSection v-else-if="Number(route().params.account_setting) == 1" :user_settings />
        <PasswordSecuritySection v-else-if="Number(route().params.account_setting) == 2" :sessions />
        <RequestStatusTypes v-else-if="Number(route().params.account_setting) == 4" :user_settings />
        <ThemesBehaviorSection :user_settings v-else />
    </div>
</template>

<script setup lang="ts">
import TabSection from '@/components/headers/TabSection.vue'
import NotificationSection from './notification-section/NotificationSection.vue'
import PasswordSecuritySection from './password-security-section/PasswordSecuritySection.vue'
import ThemesBehaviorSection from './themes-behavior-section/ThemesBehaviorSection.vue'
import ProfileSection from './profile-section/ProfileSection.vue'
import RequestStatusTypes from './RequestStatusTypes.vue'

import { UserSession, UserSettings } from './accountSettingsInt'

import { ref, onMounted } from 'vue'
import { Tab } from '@/globalTypes'
import { route } from 'ziggy-js'
import Unedited from '@/components/test/Unedited.vue'

defineProps<{
    user_settings: UserSettings
    sessions: UserSession[]
}>()

const tabs = ref<Tab[]>([
    {
        display_name: 'Notifications',
        hero_icon: {
            content: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
  <path fill-rule="evenodd" d="M12 5a4 4 0 0 0-8 0v2.379a1.5 1.5 0 0 1-.44 1.06L2.294 9.707a1 1 0 0 0-.293.707V11a1 1 0 0 0 1 1h2a3 3 0 1 0 6 0h2a1 1 0 0 0 1-1v-.586a1 1 0 0 0-.293-.707L12.44 8.44A1.5 1.5 0 0 1 12 7.38V5Zm-5.5 7a1.5 1.5 0 0 0 3 0h-3Z" clip-rule="evenodd" />
</svg>
`,
            name: 'Padlock'
        }
    },
    {
        display_name: 'Profile',
        hero_icon: {
            content: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
</svg>
`,
            name: 'profile'
        }
    },
    {
        display_name: 'Password & Security',
        hero_icon: {
            content: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
  <path fill-rule="evenodd" d="M8.5 1.709a.75.75 0 0 0-1 0 8.963 8.963 0 0 1-4.84 2.217.75.75 0 0 0-.654.72 10.499 10.499 0 0 0 5.647 9.672.75.75 0 0 0 .694-.001 10.499 10.499 0 0 0 5.647-9.672.75.75 0 0 0-.654-.719A8.963 8.963 0 0 1 8.5 1.71Zm2.34 5.504a.75.75 0 0 0-1.18-.926L7.394 9.17l-1.156-.99a.75.75 0 1 0-.976 1.138l1.75 1.5a.75.75 0 0 0 1.078-.106l2.75-3.5Z" clip-rule="evenodd" />
</svg>
`,
            name: 'Padlock'
        }
    },
    {
        display_name: 'Themes & Others',
        hero_icon: {
            content: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
  <path d="M12.613 1.258a1.535 1.535 0 0 1 2.13 2.129l-1.905 2.856a8 8 0 0 1-3.56 2.939 4.011 4.011 0 0 0-2.46-2.46 8 8 0 0 1 2.94-3.56l2.855-1.904ZM5.5 8A2.5 2.5 0 0 0 3 10.5a.5.5 0 0 1-.7.459.75.75 0 0 0-.983 1A3.5 3.5 0 0 0 8 10.5 2.5 2.5 0 0 0 5.5 8Z" />
</svg>
`,
            name: ''
        }
    },
    {
        display_name: 'Request Status Messages',
        hero_icon: {
            content: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22" stroke-width="1.5" stroke="currentColor" class="size-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>
`,
            name: ''
        }
    }
])

onMounted(() => {
    // if ($page.props.auth) {
    //     localStorage.setItem('theme', $page.props.auth.user_settings.themes)
    // }
    // let theme = localStorage.getItem('theme') ?? 'Light'
    // if (theme == 'Dark') {
    //     document.documentElement.classList.add('dark')
    // } else if (theme == 'Light') {
    //     document.documentElement.classList.remove('dark')
    // } else {
    //     if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    //         document.documentElement.classList.add('dark')
    //     } else {
    //         document.documentElement.classList.remove('dark')
    //     }
    // }
})
</script>
