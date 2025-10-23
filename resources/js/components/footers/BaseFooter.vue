<template>
    <footer class="bg-brand-50 dark:bg-dark-001 object-shadow">
        <div class="mx-auto max-w-7xl overflow-hidden py-8">
            <!-- NOTE: NAVIGATIONS -->
            <DataTransition class="-mb-6 columns-2 flex justify-center space-x-12" aria-label="Footer">
                <div v-for="item in CTopNavigation" :key="item.name" class="pb-6 flex items-center">
                    <component
                        :is="item.icon"
                        class="inline size-4 mr-2 text-brand-400 dark:text-neutral-100 hover:text-gray-800 dark:hover:text-neutral-200"
                    />
                    <a
                        :href="item.href"
                        class="text-sm leading-6 text-brand-600 dark:text-neutral-100 hover:text-gray-900 font-semibold dark:hover:text-neutral-200"
                    >
                        {{ item.name }}
                    </a>
                </div>
            </DataTransition>

            <!-- NOTE: SOCIALS -->
            <DataTransition class="mt-10 flex justify-center space-x-10 text-brand-400 hover:text-brand-500">
                <a
                    v-for="item in navigation.social"
                    :key="item.name"
                    :href="item.href"
                    target="_blank"
                    class="text-brand-400 dark:text-brand-100 hover:text-brand-500 flex flex-col gap-1 items-center hover:dark:text-neutral-100"
                >
                    <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                    <span class="text-xs font-semibold">{{ item.name }}</span>
                </a>
            </DataTransition>

            <!-- NOTE: COPYRIGHT -->
            <p class="mt-10 text-center text-xs leading-5 text-brand-500 dark:text-neutral-400">
                <Link href="/" target="_blank" class="decoration:underline">QR Attendance v1.5</Link><br />
                <a href="https://www.cmu.edu.ph/">Office of Human Resources Management, Central Mindanao University.</a> <br />
                &copy; {{ moment().format('YYYY') }} All rights reserved.
            </p>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { CTopNavigation } from '@/constants'
import DataTransition from '@/components/transitions/DataTransition.vue'
import moment from 'moment'

import { defineComponent, h } from 'vue'

const navigation = {
    social: [
        {
            name: 'OHRM FB',
            href: 'https://www.facebook.com/profile.php?id=61559712795587',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d: 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z',
                            'clip-rule': 'evenodd'
                        })
                    ])
            })
        },
        {
            name: 'Developer',
            href: 'https://github.com/migfus',
            icon: defineComponent({
                render: () =>
                    h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
                        h('path', {
                            'fill-rule': 'evenodd',
                            d: 'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
                            'clip-rule': 'evenodd'
                        })
                    ])
            })
        },
        {
            name: 'Updates',
            href: 'https://trello.com/b/RG1xcmyW/qr-attendance',
            icon: defineComponent({
                render: () =>
                    h(
                        'svg',
                        {
                            xmlns: 'http://www.w3.org/2000/svg',
                            fill: 'currentColor',
                            viewBox: '0 0 24 24'
                        },
                        [
                            h('path', {
                                d: 'M10.873 16.4069C10.9219 16.2903 10.947 16.1653 10.9469 16.039V6.95853C10.9469 6.42953 10.5115 6.00003 9.9728 6.00003H6.97153C6.43534 6.00003 6 6.43003 6 6.95853V16.039C6.00027 16.2934 6.10263 16.5373 6.2847 16.7174C6.46677 16.8975 6.71372 16.9991 6.97153 17H9.9728C10.1008 17.0002 10.2275 16.9754 10.3457 16.9271C10.464 16.8789 10.5714 16.8081 10.6619 16.7189C10.7523 16.6296 10.8241 16.5236 10.873 16.4069Z'
                            }),
                            h('path', {
                                d: 'M17.7155 12.7175C17.8975 12.5374 17.9999 12.2934 18 12.039V6.95853C18 6.42953 17.5642 6.00003 17.0285 6.00003H14.0272C13.4915 6.00003 13.0531 6.43003 13.0531 6.95853V12.039C13.0534 12.2938 13.1561 12.5381 13.3387 12.7183C13.5213 12.8984 13.7689 12.9998 14.0272 13H17.0285C17.2863 12.9992 17.5334 12.8977 17.7155 12.7175Z'
                            }),
                            h('path', {
                                'fill-rule': 'evenodd',
                                'clip-rule': 'evenodd',
                                d: 'M17.2583 2.83306C13.7917 2.44562 10.2083 2.44562 6.74177 2.83306C4.72971 3.05794 3.10538 4.64295 2.86883 6.66548C2.45429 10.2098 2.45429 13.7903 2.86883 17.3346C3.10538 19.3571 4.72971 20.9422 6.74177 21.167C10.2083 21.5545 13.7917 21.5545 17.2583 21.167C19.2703 20.9422 20.8946 19.3571 21.1312 17.3346C21.5457 13.7903 21.5457 10.2098 21.1312 6.66548C20.8946 4.64295 19.2703 3.05794 17.2583 2.83306ZM6.90838 4.32378C10.2642 3.94871 13.7358 3.94871 17.0916 4.32378C18.4218 4.47244 19.4872 5.52205 19.6414 6.83973C20.0424 10.2683 20.0424 13.7318 19.6414 17.1604C19.4872 18.478 18.4218 19.5277 17.0916 19.6763C13.7358 20.0514 10.2642 20.0514 6.90838 19.6763C5.57827 19.5277 4.51278 18.478 4.35867 17.1604C3.95767 13.7318 3.95767 10.2683 4.35867 6.83973C4.51278 5.52205 5.57827 4.47244 6.90838 4.32378Z'
                            })
                        ]
                    )
            })
        }
    ]
}
</script>
