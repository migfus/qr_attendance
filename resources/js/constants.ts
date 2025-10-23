import { TopNavigation } from './globalTypes'
import { StopIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'
import {
    AdjustmentsVerticalIcon,
    BellIcon,
    BuildingLibraryIcon,
    ClockIcon,
    Cog6ToothIcon,
    CreditCardIcon,
    IdentificationIcon,
    PhotoIcon,
    QrCodeIcon,
    UsersIcon
} from '@heroicons/vue/24/outline'
import { usePage } from '@inertiajs/vue3'
import { checkPermissions } from './utils'

export const CTopNavigation: TopNavigation[] = [
    {
        name: 'ARTA ID',
        icon: IdentificationIcon,
        href: route('arta-id.index'),
        components: ['arta-id/index/(Index)'],
        count: [],
        permissions: []
    }
    // {
    //     name: 'QR Code',
    //     icon: QrCodeIcon,\
    //     href: route('guest-qr-codes.index'),
    //     components: ['guest-qr/(Index)'],
    //     count: []
    // }
    // {
    //     name: 'Feedback',
    //     icon: InboxStackIcon,
    //     href: route('feedbacks.index'),
    //     components: ['feedback/index/(Index)'],
    //     count: []
    // },
    // {
    //     name: 'Updates',
    //     icon: MegaphoneIcon,
    //     href: route('updates.index'),
    //     components: ['updates/index/(Index)'],
    //     count: []
    // }
]

export const CSidebarNavigation = function (): TopNavigation[] {
    const $page = usePage()

    const contents = ref<TopNavigation[]>([
        {
            name: 'Dashboard',
            icon: StopIcon,
            href: route('dashboard.index'),
            components: ['dashboard/(Index)'],
            count: [],
            permissions: ['Dashboard/View/All', 'Dashboard/View/Arta Only']
        },

        {
            name: 'Request Status',
            icon: ClockIcon,
            href: route('dashboard.request-statuses.index'),
            components: ['dashboard/request-statuses/(Index)'],
            count: [
                {
                    color: '#ef4444',
                    number: $page.props.auth?.request_status_count[0] ?? 0
                },
                {
                    color: '#5b715e',
                    number: $page.props.auth?.request_status_count[1] ?? 0
                }
            ],
            permissions: ['Request Status/View/All']
        },
        {
            name: 'Arta ID',
            icon: CreditCardIcon,
            href: route('dashboard.arta-id.index'),
            components: ['dashboard/arta-id/index/(Index)', 'dashboard/arta-id/show/(Show)', 'dashboard/arta-id/edit/(Edit)'],
            count: [],
            permissions: ['ARTA ID/View/All']
        },

        {
            name: 'Users',
            icon: UsersIcon,
            href: route('dashboard.users.index'),
            components: ['dashboard/users/(Index)', 'dashboard/users/(Edit)', 'dashboard/users/(Create)'],
            count: [],
            permissions: ['Users/View/All']
        },
        // {
        //     name: 'Inventory',
        //     icon: InboxStackIcon,
        //     href: route('dashboard.galleries.index'),
        //     components: ['dashboard/users/index/(Index)'],
        //     count: []
        // },

        {
            name: 'Guest QR',
            icon: QrCodeIcon,
            href: route('dashboard.guest-qr.index'),
            components: ['dashboard/guest-qr/(Index)', 'dashboard/guest-qr/(Edit)', 'dashboard/guest-qr/(Create)'],
            count: [],
            permissions: ['Guest QR/View/All']
        },
        {
            name: 'Departments',
            icon: BuildingLibraryIcon,
            href: route('dashboard.departments.index'),
            components: ['dashboard/departments/(Index)', 'dashboard/departments/(Edit)', 'dashboard/departments/(Create)'],
            count: [],
            permissions: ['Department/View/All']
        },
        {
            name: 'Data Configuration',
            icon: AdjustmentsVerticalIcon,
            href: route('dashboard.data-configurations.index'),
            components: ['dashboard/data-configurations/(Index)', 'dashboard/data-configurations/(Show)'],
            count: [],
            permissions: ['Data Configuration/View/All']
        },

        {
            name: 'Gallery',
            icon: PhotoIcon,
            href: route('dashboard.galleries.index'),
            components: ['dashboard/galleries/(Index)'],
            count: [],
            permissions: ['Gallery/View/All']
        },

        {
            name: 'Notifications',
            icon: BellIcon,
            href: route('dashboard.notifications.index'),
            components: ['dashboard/notifications/(Index)'],
            count: [
                {
                    color: '#ef4444',
                    number: $page.props.auth?.notifications.total ?? 0
                }
            ],
            permissions: ['Notifications/View/All']
        },
        {
            name: 'Roles & Permissions',
            icon: UsersIcon,
            href: route('dashboard.roles-permissions.index'),
            components: ['dashboard/roles-permissions/(Index)', 'dashboard/roles-permissions/(Edit)', 'dashboard/roles-permissions/(Create)'],
            count: [],
            permissions: ['Roles & Permissions/View/All']
        },

        // {
        //     name: 'Feedbacks',
        //     icon: InboxStackIcon,
        //     href: route('dashboard.feedbacks.index'),
        //     components: ['dashboard/feedbacks/(Index)'],
        //     count: []
        // },
        // {
        //     name: 'Updates',
        //     icon: MegaphoneIcon,
        //     href: route('dashboard.updates.index'),
        //     components: ['dashboard/updates/(Index)'],
        //     count: []
        // },
        {
            name: 'Account Settings',
            icon: Cog6ToothIcon,
            href: route('dashboard.account-settings.show', { account_setting: 0 }),
            components: ['dashboard/account-settings/(Index)'],
            count: [],
            permissions: ['Account Settings/View/All']
        }
    ])

    const filtered_contents = contents.value.filter((page) => checkPermissions(page.permissions, $page.props.auth?.permissions))

    return filtered_contents
}
