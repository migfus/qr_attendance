export interface UserSession {
    id: string
    user_id: string
    ip_address: string
    user_agent: string
    payload: string
    last_activity: number
}

export interface UserSettings {
    themes: 'Dark' | 'Light'
    card: {
        show_description: boolean
    }
    notification: {
        push: NotificationUserSettings
        email: NotificationUserSettings
        sms: NotificationUserSettings
    }
    request_status_messages: {
        Unverified: string
        Processing: string
        Completed: string
        Claimed: string
        Rejected: string
        Removed: string
    }
}

export interface NotificationUserSettings {
    registered: boolean
}
