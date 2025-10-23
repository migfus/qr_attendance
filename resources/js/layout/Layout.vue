<template>
    <Head>
        <link rel="icon" type="image/png" href="/images/ohrm.png" />
    </Head>

    <NotiWind />

    <div v-if="$page.props.setup">
        <slot></slot>
    </div>
    <!-- <TopBanner /> -->

    <SideNavigation v-else-if="$page.props.sidebar">
        <template #default>
            <div class="bg-brand-50 dark:bg-neutral-800">
                <div class="mx-auto max-w-7xl">
                    <slot></slot>
                </div>
            </div>
        </template>
        <template #footer>
            <BaseFooter class="bg-white dark:bg-dark-002" />
        </template>
    </SideNavigation>

    <TopNavigation v-else>
        <template #default>
            <div class="bg-brand-50 dark:bg-dark-001 mb-auto">
                <div class="mx-auto max-w-7xl sm:px-4">
                    <slot></slot>
                </div>
            </div>
        </template>
        <template #footer>
            <BaseFooter class="bg-white dark:bg-dark-002" />
        </template>
    </TopNavigation>
</template>

<script setup lang="ts">
import TopNavigation from '@/components/navigations/TopNavigation.vue'
import SideNavigation from '@/components/navigations/SideNavigation.vue'
import BaseFooter from '@/components/footers/BaseFooter.vue'
import NotiWind from '@/components/notifications/NotiWind.vue'
// import TopBanner from '@/components/navigations/TopBanner.vue'

import { notify } from 'notiwind'
import { useTitle } from '@vueuse/core'
import { Head } from '@inertiajs/vue3'
import { watch, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Flash } from '@/globalTypes'

const $page = usePage()

// NOTE: HEADER TITLE (Constantly change in every navigation)
const title = computed(() => $page.props.title)
const page_title = computed(() => $page.props.page_title)
const all_notification_count = computed(() => {
    const request_status_count = $page.props.auth?.request_status_count[0] ?? 0
    const request_status_count_ = $page.props.auth?.request_status_count[1] ?? 0
    const notification_count = $page.props.auth?.notifications.total ?? 0

    return notification_count + request_status_count + request_status_count_ > 0 ? `(${notification_count + request_status_count + request_status_count_})` : ''
})
const _title = useTitle(
    page_title.value ? `${all_notification_count.value} ${page_title.value} | ${title.value}` : `${all_notification_count.value} ${title.value}`
)
watch(page_title, () => {
    _title.value = `${all_notification_count.value} ${page_title.value} | ${title.value}`
})

// @ts-ignore
const flash = computed<Flash>(() => {
    return $page.props.flash
})

watch(flash, () => {
    if (flash.value.success) {
        notify(
            {
                group: 'success',
                title: flash.value.success.title,
                content: flash.value.success.content
            },
            4000
        )
    }
    if (flash.value.error) {
        notify(
            {
                group: 'error',
                title: flash.value.error.title,
                content: flash.value.error.content
            },
            4000
        )
    }
})
</script>
