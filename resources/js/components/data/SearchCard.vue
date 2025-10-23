<template>
    <div class="lg:flex lg:items-center lg:justify-between bg-brand-50 p-4 rounded-xl shadow mb-2">
        <div class="min-w-0 flex-1">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <a href="#" class="text-sm font-medium text-brand-700 hover:text-brand-900">HRIS</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-500" aria-hidden="true" />
                            <a href="#" class="ml-4 text-sm font-medium text-brand-700 hover:text-brand-900">CSC ID / Biometric</a>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="flex justify-start mt-2 font-semibold text-brand-600 truncate">
                <img
                    src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80"
                    class="h-12 w-12 rounded-full shadow mr-3"
                />
                <div class="flex flex-col">
                    <label>Schwarzenegger R. Belonio</label>
                    <label>Clerk</label>
                </div>
            </h2>
            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                <div class="mt-2 flex items-center text-sm text-brand-500">
                    <EnvelopeIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-brand-500" aria-hidden="true" />
                    migfus20@gmail.com
                </div>
                <div class="mt-2 flex items-center text-sm text-brand-500">
                    <PhoneIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-brand-500" aria-hidden="true" />
                    0997-866-3855
                </div>
                <div class="mt-2 flex items-center text-sm text-brand-500">
                    <CalendarIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-brand-500" aria-hidden="true" />
                    Joined on Jan 2, 2024
                </div>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4 justify-end">
            <span class="ml-3 hidden sm:block">
                <AppButton type="button" color="brand">
                    <PencilIcon class="-ml-1 mr-2 h-4 w-4 m-auto text-gray-300" aria-hidden="true" />
                    View
                </AppButton>
            </span>

            <!-- Dropdown -->
            <Menu as="div" class="relative ml-3 sm:hidden">
                <MenuButton
                    class="inline-flex items-center rounded-md border border-transparent bg-gray-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                >
                    More
                    <EllipsisVerticalIcon class="-mr-1 ml-2 h-5 w-5 text-gray-400" aria-hidden="true" />
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
                        class="absolute right-0 z-10 mt-2 -ml-1 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-hidden"
                    >
                        <MenuItem v-slot="{ active }">
                            <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">View</a>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>
</template>

<script setup lang="ts">
import { CalendarIcon, EllipsisVerticalIcon, ChevronRightIcon, PencilIcon, PhoneIcon, EnvelopeIcon } from '@heroicons/vue/20/solid'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import AppButton from '@/components/form/AppButton.vue'
</script>
