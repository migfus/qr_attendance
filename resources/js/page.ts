import { User, Flash } from './globalTypes'

export interface InertiaPageProps {
    title: string
    sidebar: boolean
    logo: {
        lg: string
        sm: string
    }
    pageTitle: string
    flash?: Flash

    auth?: User
    pending_task_count: number
    guest_id: string

    [key: string]: any
    system_permissions: string[]

    base_url: string
}

declare module '@inertiajs/vue3' {
    interface PageProps extends InertiaPageProps {}
}
