import { FunctionalComponent } from 'vue'

export interface Extension {
    name: string
    loaded: boolean
    version: string
}

export interface PhpRequirements {
    php_version: string
    extensions: Extension[]
    php_version_ok: boolean
    min_php_version: string
}

export interface DatabaseFormError {
    app_name?: string
    app_url?: string

    mail_mailer?: string
    mail_host?: string
    mail_port?: string
    mail_username?: string
    mail_password?: string
    mail_from_address?: string

    host?: string
    port?: string
    database?: string
    username?: string
    password?: string
}

export interface VerifyButtonStatus {
    name: string
    icon: FunctionalComponent
    color?: 'brand' | 'danger' | 'transparent'
}

export interface SelectTheme {
    name: string
    dark: {
        brand: string
        default: string
    }
    light: {
        brand: string
        default: string
    }
}

export interface InputType {
    icon: FunctionalComponent
    name: string
}
