import { FunctionalComponent } from 'vue'
import { Department } from './Pages/dashboard/departments/departmentInt'
import { Employee } from './Pages/dashboard/arta-id/artaIdInt'
import { Role } from '@/Pages/dashboard/roles-permissions/rolesPermissionsInt'
import { UserSettings } from './Pages/dashboard/account-settings/accountSettingsInt'

interface Flash {
    error: {
        title: string
        content: string
    }
    success: {
        title: string
        content: string
    }
}

interface SearchFilter {
    display_name: string
    value: string
    icon: FunctionalComponent
}

export interface HeroIcon {
    name: string
    content: string
}

export interface TopNavigation {
    name: string
    icon?: FunctionalComponent
    href: string
    active?: boolean
    components: string[]
    count: {
        number: number
        color: string
    }[]
    permissions: string[]
}

export interface Auth extends User {
    user_settings: UserSettings
}

export interface Props {
    auth?: Auth
    flash: {
        message: string[]
    }
    title: string
    page_title?: string
    sidebar: boolean
    system_settings?: {
        name: string
        config: string
        description: string
    }[]
    data?: object
    errors?: object
    logo: {
        lg: string
        sm: string
    }
}

export interface Pagination<T> {
    current_page: number
    next_page_url: string | null
    data: T[]
    total: number
    last_page: number
}

export interface User {
    id: string
    email: string
    avatar_url: string
    created_at: string
    name: string
    role: Role[]
    roles: Role[]
    user_settings: UserSettings
    notifications: Pagination<Notification>
    request_status_count: number[]
    active_users: Session[]
    department: Department
    permissions: string[]
}

interface Session {
    session_count: number
    last_seen: number
    user: User
}

export interface UserWithType extends User {
    type: 'head' | 'member'
    disabled: boolean
}

export interface Tab {
    display_name: string
    hero_icon: HeroIcon
}

// NOTE: $page intefaces/types
declare module '@inertiajs/core' {
    interface PageProps {
        // NOTE: Shared
        title: string
        sidebar: boolean
        logo: {
            sm: string
            lg: string
        }
        flash?: {
            error?: string
            success?: string
        }
        auth?: User

        // NOTE: Independent
        pageTitle: string
        pendind_task_count: number
    }
}

export interface Filters {
    search: string
    type: string
    page: number
}

export interface Select {
    name: string
    id: number | string
    hero_icon?: {
        content: string
    }
    short_name?: string
    image_url?: string
}

export interface AxiosErrors {
    response: {
        data: Object
    }
}

export interface File {
    id: number
    file_url: string
    created_at: string
    file_type: {
        name: string
    }
    size: number
    width: number
    height: number
    thumbnail_url: string
    fileable: Employee // Post | User | group etc
}

export interface SearchQuery {
    search: string
    end: string
    start: string
    search_filter: SearchFilter
    start_select: boolean
    select_data: string[]
}

export interface ContentCardData {
    image_url: string
    title: string
    sub_title: string
    ago: string
    sub_ago: string
    id: string
}

export interface SimpleDataTable {
    name: string
    id: string | number
    status: string
}
