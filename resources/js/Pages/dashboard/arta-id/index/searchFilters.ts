import { SearchFilter } from '@/globalTypes'
import { ArrowDownOnSquareIcon, ArrowPathIcon, CheckCircleIcon, ClockIcon, MagnifyingGlassIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const search_filters: SearchFilter[] = [
    {
        display_name: 'All',
        value: '',
        icon: MagnifyingGlassIcon
    },
    {
        display_name: 'Unverified',
        value: 'Unverified',
        icon: ClockIcon
    },
    {
        display_name: 'Processing',
        value: 'Processing',
        icon: ArrowPathIcon
    },
    {
        display_name: 'Completed',
        value: 'Completed',
        icon: CheckCircleIcon
    },
    {
        display_name: 'Claimed',
        value: 'Claimed',
        icon: ArrowDownOnSquareIcon
    },
    {
        display_name: 'Rejected',
        value: 'Rejected',
        icon: XMarkIcon
    },
    {
        display_name: 'Removed',
        value: 'Removed',
        icon: TrashIcon
    }
]

export default search_filters
