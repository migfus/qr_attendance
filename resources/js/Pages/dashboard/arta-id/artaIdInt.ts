import { Department } from '../departments/departmentInt'
import { File, User, HeroIcon } from '@/globalTypes'

export interface Employee {
    id: string
    last_name: string
    first_name: string
    mid_name: string
    extra_name: {
        id: number
        name: string
    }
    email: string
    position: {
        id: number
        name: string
    }
    department: Department
    employee_status: {
        id: number
        name: string
    }
    files: File[]
    claim_type: ClaimType
    request_statuses: RequestStatus[]
    latest_request_status: RequestStatus
    quick_response_codes: QuickResponseCode[]
    created_at: string
}

export interface RequestStatus {
    id: number
    request_status_type: RequestStatusType
    content: string
    created_at: string
    user: User
}

export interface RequestStatusType {
    id: number
    name: string
    hero_icon: HeroIcon
}

export interface ClaimType {
    id: number
    name: string
    price: number
    image_url: string
}

export interface QuickResponseCode {
    id: string
}

export interface EmployeeStatus {
    name: string
    created_at: string
}
