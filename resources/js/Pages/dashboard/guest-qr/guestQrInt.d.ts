import { Department } from '../departments/departmentInt'
import { Select } from '@/globalTypes'

export interface GuestQR {
    name: string
    guest_id: string
    status_id: number
    department_id: number
    department: Department
    status: Select
    id: string
    deleted_at: string
    created_at: string
}
