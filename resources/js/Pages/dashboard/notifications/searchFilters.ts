import { SearchFilter } from '@/globalTypes'
import { CheckCircleIcon, ExclamationCircleIcon, MagnifyingGlassCircleIcon } from '@heroicons/vue/24/outline'

const search_filters: SearchFilter[] = [
    {
        display_name: 'All',
        value: '',
        icon: MagnifyingGlassCircleIcon
    },
    {
        display_name: 'Read',
        value: 'Read',
        icon: CheckCircleIcon
    },
    {
        display_name: 'Unread',
        value: 'Unread',
        icon: ExclamationCircleIcon
    }
]

export default search_filters
