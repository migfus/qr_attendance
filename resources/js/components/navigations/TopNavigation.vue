<template>
    <!-- <TopBanner /> -->
    <Disclosure as="nav" v-slot="{ open }" class="bg-white dark:bg-dark-002 shadow z-50 border-b border-brand-100 dark:border-neutral-700">
        <!-- NOTE: DESKTOP VIEW -->
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton
                        class="inline-flex items-center justify-center rounded-2xl p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800 hover:text-gray-500 dark:hover:text-neutral-200 focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-brand-500"
                    >
                        <BasicTransition>
                            <Bars3Icon v-if="!open" class="block h-6 w-6 dark:text-neutral-200" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6 dark:text-neutral-200" aria-hidden="true" />
                        </BasicTransition>
                    </DisclosureButton>
                </div>
                <div class="flex flex-1 items-center sm:items-stretch justify-start ml-12 sm:ml-0">
                    <Link :href="route('index')" class="flex flex-shrink-0 items-center">
                        <TopNavigationLogo />
                    </Link>
                    <DataTransition class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <TopNavigationsDesktop
                            v-for="row in CTopNavigation"
                            :key="row.name"
                            :href="row.href"
                            :active="row.active"
                            :icon="row.icon"
                            :name="row.name"
                            :components="row.components"
                        />
                    </DataTransition>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 space-x-4">
                    <!-- <SearchBar/> -->
                    <!-- <ThemeToggle v-if="!$page.props.auth" /> -->
                    <!-- Profile dropdown -->
                    <TopNavigationProfileDropdown />
                </div>
            </div>
        </div>
        <!-- NOTE: MOBILE VIEW -->
        <DisclosurePanel class="sm:hidden transition-all">
            <div class="space-y-1 pt-2 pb-4">
                <TopNavigationsMobile
                    v-for="row in CTopNavigation"
                    :key="row.name"
                    :href="row.href"
                    :active="row.active"
                    :name="row.name"
                    :icon="row.icon"
                    :components="row.components"
                />
            </div>
        </DisclosurePanel>
    </Disclosure>

    <div class="flex flex-col justify-between ">
        <slot></slot>
        <slot name="footer"></slot>
    </div>
</template>

<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
import TopNavigationsDesktop from './TopNavigationsDesktop.vue'
import TopNavigationsMobile from './TopNavigationsMobile.vue'
import TopNavigationLogo from './TopNavigationLogo.vue'
import TopNavigationProfileDropdown from './TopNavigationProfileDropdown.vue'
import ThemeToggle from './ThemeToggle.vue'
import { CTopNavigation } from '@/constants'
import BasicTransition from '../transitions/BasicTransition.vue'
import DataTransition from '../transitions/DataTransition.vue'
</script>
