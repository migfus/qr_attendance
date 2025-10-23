<template>
    <div>
        <!-- NOTE: MOBILE VIEW -->
        <TransitionRoot as="template" :show="sidebar_open">
            <Dialog as="div" class="relative z-40 md:hidden" @close="sidebar_open = false">
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 backdrop-blur-sm" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-dark-002 pt-5 pb-4">
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        type="button"
                                        class="cursor-pointer ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebar_open = false"
                                    >
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex flex-shrink-0 items-center h-14 px-4 mx-4 text-brand-700 rounded-xl bg-brand-100 dark:bg-dark-001 object-shadow">
                                <TopNavigationLogo />
                            </div>
                            <div class="mt-5 h-0 flex-1 overflow-y-auto">
                                <nav class="space-y-1 px-2">
                                    <div class="flex flex-col">
                                        <SideNavigationContent title="Dashboard" :data="CSidebarNavigation()" v-model="sidebar_open" class="mb-4" />
                                    </div>
                                    <div class="flex flex-col">
                                        <SideNavigationContent title="Pages" :data="CTopNavigation" v-model="sidebar_open" />
                                    </div>

                                    <div class="flex flex-col mt-4">
                                        <SideNavigationActiveUsers />
                                    </div>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- NOTE: DESKTOP VIEW-->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex min-h-0 flex-1 flex-col bg-white dark:bg-dark-002">
                <Link
                    href="/"
                    class="flex h-12 flex-shrink-0 items-center bg-brand px-4 cursor-pointer bg-brand-50 dark:bg-dark-001 rounded-xl m-2 object-shadow"
                >
                    <TopNavigationLogo />
                </Link>
                <div class="flex flex-1 flex-col overflow-y-auto">
                    <nav class="flex-1 space-y-1 px-2 py-4">
                        <div class="flex flex-col">
                            <SideNavigationContent title="Dashboard" :data="CSidebarNavigation()" v-model="sidebar_open" class="mb-4" />
                        </div>
                        <div class="flex flex-col">
                            <SideNavigationContent title="Pages" :data="CTopNavigation" v-model="sidebar_open" />
                        </div>
                        <div class="flex flex-col mt-4">
                            <SideNavigationActiveUsers />
                        </div>
                        <div v-if="$page.props.base_url == 'http://127.0.0.1:8000'" class="flex flex-col mt-4">
                            <div class="bg-yellow-400 dark:bg-yellow-700 px-2 py-1 rounded-xl text-center">Development Mode</div>
                        </div>
                        <!-- <SideNavigationContent v-if="c_admin_navigation.length" title="Admin" :data="c_admin_navigation" v-model="sidebar_open" /> -->
                    </nav>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:pl-64">
            <!-- NOTE: TOP NAVIGATION -->
            <div class="top-0 z-20 flex h-16 flex-shrink-0 bg-white dark:bg-dark-002">
                <button
                    type="button"
                    class="cursor-pointer border-r border-gray-200 dark:border-neutral-600 px-4 text-gray-500 dark:text-neutral-200 focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-brand-500 md:hidden"
                    @click="sidebar_open = true"
                >
                    <Bars3BottomLeftIcon class="h-6 w-6" aria-hidden="true" />
                </button>
                <div class="flex flex-1 justify-between px-4 max-w-7xl mx-auto">
                    <div class="flex-grow max-w-xs">
                        <form class="mt-3 sm:mt-4" action="#" method="GET">
                            <ComboBox name="Search Settings" :data="search_settings" noLabel placeholder="Search Settings" v-model="form.search" />
                        </form>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6 gap-2">
                        <TopNavigationProfileDropdown />
                    </div>
                </div>
            </div>

            <!-- NOTE: CONTENTS -->
            <main class="flex-1 bg-brand-50 dark:bg-dark-001">
                <div class="py-6 sm:mx-4">
                    <div class="max-w-7xl mx-auto">
                        <slot></slot>
                    </div>
                </div>

                <slot name="footer"></slot>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Bars3BottomLeftIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import TopNavigationProfileDropdown from './TopNavigationProfileDropdown.vue'
import TopNavigationLogo from './TopNavigationLogo.vue'
import SideNavigationContent from '@/components/navigations/SideNavigationContent.vue'
import SideNavigationActiveUsers from './SideNavigationActiveUsers.vue'

import { reactive, ref, watch } from 'vue'
import { CTopNavigation, CSidebarNavigation } from '@/constants'
import ComboBox from '@/components/form/ComboBox.vue'
import { Select } from '@/globalTypes'
import { router } from '@inertiajs/vue3'

const sidebar_open = ref(false)

const form = reactive({
    search: {
        name: '',
        id: ''
    }
})

const search_settings: Select[] = [
    {
        name: 'Dashboard',
        id: route('dashboard.index')
    },
    {
        name: 'Guest QR',
        id: route('dashboard.guest-qr.index')
    },
    {
        name: 'Notifications',
        id: route('dashboard.notifications.index')
    },
    {
        name: 'Account Settings',
        id: route('dashboard.account-settings.show', { account_setting: 0 })
    },
    {
        name: 'Create QR Code',
        id: route('dashboard.guest-qr.create')
    },
    {
        name: 'Removed QR Code',
        id: route('dashboard.guest-qr.index', { search_filter: 'Removed' })
    },
    {
        name: 'Change Password',
        id: route('dashboard.account-settings.show', { account_setting: 2 })
    },
    {
        name: 'Change Email',
        id: route('dashboard.account-settings.show', { account_setting: 1 })
    },
    {
        name: 'Change Avatar',
        id: route('dashboard.account-settings.show', { account_setting: 1 })
    },
    {
        name: 'Dark Mode',
        id: route('dashboard.account-settings.show', { account_setting: 3 })
    },
    {
        name: 'Show Description',
        id: route('dashboard.account-settings.show', { account_setting: 3 })
    }
]

watch(form, () => {
    router.visit(form.search.id)
})
</script>
