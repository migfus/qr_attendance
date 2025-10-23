import { User } from '@/globalTypes'

export interface PermissionModule {
    name: string
    permissions: Permission[]
}

export interface Role {
    id: string
    name: string
    display_name: string
    description: string
    created_at: string
    permissions: Permission[]
    users: User[]
}

export interface Permission {
    name: string
    display_name: string
    description: string
    id: string
    permission_module: PermissionModule
    permission_type: PermissionType
}

export interface PermissionType {
    name: string
    permission: Permission[]
}
