import { SearchFilter } from '@/globalTypes'
import { MagnifyingGlassCircleIcon, TrashIcon } from '@heroicons/vue/24/outline'

const search_filters: SearchFilter[] = [
    {
        display_name: 'All',
        value: '',
        icon: MagnifyingGlassCircleIcon
    },
    {
        display_name: 'Removed',
        value: 'Removed',
        icon: TrashIcon
    }
]

export default search_filters
