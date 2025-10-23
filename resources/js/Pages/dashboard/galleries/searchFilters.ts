import { SearchFilter } from '@/globalTypes'
import { MagnifyingGlassIcon, PhotoIcon, ScissorsIcon } from '@heroicons/vue/24/outline'

const search_filters: SearchFilter[] = [
    {
        display_name: 'All',
        value: '',
        icon: MagnifyingGlassIcon
    },
    {
        display_name: 'Cropped',
        value: 'Cropped',
        icon: ScissorsIcon
    },
    {
        display_name: 'Raw',
        value: 'Raw',
        icon: PhotoIcon
    },
    {
        display_name: 'Saved from Editor',
        value: 'Saved from Editor',
        icon: PhotoIcon
    },
    {
        display_name: 'Uploaded By Staff',
        value: 'Uploaded By Staff',
        icon: PhotoIcon
    }
]

export default search_filters
