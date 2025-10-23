<template>
    <!-- NOTE: AUTH -->
    <div v-if="$page.props.auth" class="flex">
        <!-- NOTE NOTIFICATIONS -->
        <Menu as="div" class="relative mr-3 mt-1">
            <div>
                <MenuButton
                    class="p-1 mr-2 flex rounded-full bg-white dark:bg-dark-002 text-sm focus:outline-hidden focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 relative cursor-pointer"
                >
                    <span class="sr-only">View notifications</span>
                    <BellIcon class="h-6 w-6 text-brand-600 dark:text-brand-100 bg-white dark:bg-dark-002 rounded-full" aria-hidden="true" />
                    <span
                        v-if="$page.props.auth.notifications.total > 0"
                        class="bg-red-500 shadow rounded-full px-1.5 absolute right-0 bottom-0 -mb-1 -mr-1 text-xs font-medium text-red-100"
                    >
                        {{ $page.props.auth.notifications.total }}
                    </span>
                </MenuButton>
            </div>
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <MenuItems
                    class="p-4 absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-xl bg-white dark:bg-dark-002 shadow-lg border border-light-003 focus:outline-hidden"
                >
                    <Link :href="route('dashboard.notifications.index')" class="text-brand-600 dark:text-brand-100 my-2 text-sm truncate"> Notifications </Link>

                    <MenuItem v-if="$page.props.auth.notifications.total == 0" v-slot="{ active, close }" class="flex flex-col items-center">
                        <div class="px-4 py-2 text-sm text-brand-700 dark:text-brand-200 line-clamp-1">
                            <img src="https://qr.cmuohrm.site/images/notif.png" class="text-sm font-semibold text-brand-500 px-8" />
                        </div>
                    </MenuItem>

                    <MenuItem v-for="item in $page.props.auth.notifications.data" v-slot="{ active, close }" class="truncate">
                        <div
                            :class="[
                                active ? 'bg-brand-50 dark:bg-neutral-800 ' : '',
                                'px-4 py-2 text-sm text-brand-700 dark:text-brand-200  line-clamp-1 flex truncate group'
                            ]"
                        >
                            <PlusIcon class="text-brand-500h-5 w-4 flex-shrink-0 sm:-ml-1 mr-2 inline mb-1 flex-none" />
                            <Link @click="close" :href="item.data.url" class="flex-auto truncate font-semibold">
                                {{ item.data.message }}
                            </Link>
                            <span class="flex-none pl-2 group-hover:hidden transition-all inline">
                                {{ shortTimeAgo(item.created_at) }}
                            </span>
                            <XCircleIcon
                                @click="readNotification(item.id)"
                                class="absolute right-4 flex-none group-hover:opacity-100 opacity-0 transition-all size-6 shadow bg-white dark:bg-neutral-700 rounded-full text-red-500 cursor-pointer"
                            />
                        </div>
                    </MenuItem>

                    <MenuItem v-slot="{ active, close }" class="flex flex-col items-center rounded-xl">
                        <div
                            :class="[
                                active ? 'bg-brand-50 dark:bg-neutral-800 ' : '',
                                'px-4 py-2 text-sm text-brand-700 dark:text-brand-200  line-clamp-1 flex truncate group hover:bg-dark-003'
                            ]"
                        >
                            <Link :href="route('dashboard.notifications.index')" class="text-sm font-semibold text-brand-700 dark:text-neutral-300">
                                More Notifications...
                            </Link>
                        </div>
                    </MenuItem>
                </MenuItems>
            </transition>
        </Menu>

        <!-- NOTE PROFILE -->
        <Menu as="div" class="relative">
            <MenuButton
                class="cursor-pointer flex rounded-full bg-white dark:bg-dark-001 text-sm focus:outline-hidden focus:ring-2 focus:ring-neutral-600 focus:ring-offset-2 w-8 border border-dark-003"
            >
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full" :src="$page.props.auth.avatar_url" alt="Avatar" />
                <span
                    v-if="maxDigit(($page.props.auth?.request_status_count[0] ?? 0) + ($page.props.auth?.request_status_count[1] ?? 0)) != '0'"
                    class="bg-brand-500 rounded-full px-1 py-0.5 my-auto text-xs text-white absolute bottom-0 -right-2"
                >
                    {{ maxDigit(($page.props.auth?.request_status_count[0] ?? 0) + ($page.props.auth?.request_status_count[1] ?? 0)) }}
                </span>
            </MenuButton>
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <MenuItems
                    class="p-2 space-y-0.5 absolute right-0 z-10 mt-2 w-54 origin-top-right rounded-xl bg-white dark:bg-dark-002 shadow-lg ring-1 ring-brand-200 dark:ring-neutral-600 ring-opacity-5 focus:outline-hidden"
                >
                    <div class="flex flex-col gap-0.5 mb-4 dark:bg-dark-002 bg-light-002 p-2 rounded-xl">
                        <div class="text-brand-800 dark:text-neutral-300 ml-3 text-sm truncate font-semibold">{{ $page.props.auth.name }}</div>
                        <div class="text-brand-600 dark:text-neutral-300 ml-3 text-xs truncate">{{ $page.props.auth.email }}</div>
                    </div>

                    <MenuItem
                        v-for="item in profile_dropdown_links"
                        v-slot="{ active, close }"
                        class="rounded-md [&:nth-child(2)]:rounded-t-xl last:rounded-b-xl"
                    >
                        <Link
                            @mouseup="close()"
                            :href="item.href"
                            :method="item.name == 'Logout' ? 'post' : 'get'"
                            :class="[
                                active ? 'bg-brand-100 dark:bg-dark-003' : '',
                                'w-full block px-4 py-2 text-sm text-brand-700 dark:text-neutral-300 cursor-pointer dark:bg-dark-002'
                            ]"
                        >
                            <div class="flex justify-between">
                                <div class="flex justify-start font-semibold">
                                    <component :is="item.icon" class="text-brand-500h-5 w-4 flex-shrink-0 sm:-ml-1 mr-3 inline" />
                                    {{ item.name }}
                                </div>

                                <div class="flex gap-2">
                                    <span
                                        v-if="item.name == 'Request Status' && maxDigit((item.count[0].number ?? 0) + (item.count[1].number ?? 0)) != '0'"
                                        class="px-1.5 py-0.5 rounded-full text-white text-xs bg-brand-500"
                                    >
                                        {{ maxDigit((item.count[0].number ?? 0) + (item.count[1].number ?? 0)) }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </MenuItem>
                </MenuItems>
            </transition>
        </Menu>
    </div>

    <!-- NOTE: GUEST -->
    <div v-else>
        <Link :href="route('login')">
            <AppButton color="brand">Sign-in</AppButton>
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { PlusIcon, XCircleIcon, BellIcon, ArrowRightStartOnRectangleIcon } from '@heroicons/vue/24/outline'
import AppButton from '@/components/form/AppButton.vue'

import { shortTimeAgo, maxDigit } from '@/utils'
import { router, usePage } from '@inertiajs/vue3'
import { CSidebarNavigation } from '@/constants'

const $page = usePage()

const profile_dropdown_links = [
    ...CSidebarNavigation(),
    {
        name: 'Logout',
        icon: ArrowRightStartOnRectangleIcon,
        href: route('logout'),
        components: [],
        count: [],
        post: true
    }
]

function navigateTo(link: string, post?: boolean) {
    alert('yawa')
    // if (post) {
    //     router.visit(link, { method: 'post' })
    // } else {
    //     router.visit(link)
    // }
}

async function readNotification(id: string) {
    try {
        router.put(route('dashboard.notifications.update', { notification: id }))
    } catch (err) {
        alert(JSON.stringify(err))
    }
}
</script>
