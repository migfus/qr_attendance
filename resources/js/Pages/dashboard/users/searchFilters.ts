import { SearchFilter } from '@/globalTypes'
import { ClockIcon, QrCodeIcon, StarIcon, UserCircleIcon, UserIcon } from '@heroicons/vue/24/outline'

const search_filters: SearchFilter[] = [
    {
        display_name: 'All',
        value: '',
        icon: ClockIcon
    },
    {
        display_name: 'Administrator',
        value: 'Administrator',
        icon: StarIcon
    },
    {
        display_name: 'Manager',
        value: 'Manager',
        icon: UserCircleIcon
    },
    {
        display_name: 'Scanner',
        value: 'Scanner',
        icon: QrCodeIcon
    },
    {
        display_name: 'Member',
        value: 'Member',
        icon: UserIcon
    }
]

export default search_filters
