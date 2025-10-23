import moment from 'moment'
import { SearchFilter, Select } from './globalTypes'
import { usePage } from '@inertiajs/vue3'

export function base64ToBlob(base64: string, content_type = 'image/jpg', name = 'cropped-image.jpg') {
    if (!/^data:image\/[a-z]+;base64,/.test(base64)) {
        base64 = base64DefaultImage()
    }

    const byteCharacters = atob(base64.split(',')[1])
    const byteArrays = []

    for (let offset = 0; offset < byteCharacters.length; offset += 512) {
        const slice = byteCharacters.slice(offset, offset + 512)
        const byteNumbers = Array.from(slice).map((char) => char.charCodeAt(0))
        byteArrays.push(new Uint8Array(byteNumbers))
    }

    return new File([new Blob(byteArrays, { type: content_type })], name, {
        type: content_type
    })
}

export const defaultRouterState = (only?: string[]) => {
    if (only)
        return {
            preserveScroll: true,
            preserveState: true,
            only: only,
            onError: (err: any) => {
                alert(JSON.stringify(err))
            }
        }

    return {
        preserveScroll: true,
        preserveState: true,
        onError: (err: any) => {
            alert(JSON.stringify(err))
        }
    }
}

export function checkPermissions(required_permission: string[], user_permissions?: string[]) {
    if (required_permission.length == 0 || user_permissions == null) {
        return true
    }
    return user_permissions.some((row: string) => required_permission.includes(row))
}

export function searchQuery(params: { search: string; start: string; end: string; search_filter: { value: string } }, page = 1) {
    const query: Record<string, any> = { page }

    if (params.search) query.search = params.search
    if (params.start) query.start = params.start
    if (params.end) query.end = params.end
    if (params.search_filter && params.search_filter.value) query.search_filter = params.search_filter.value

    return query
}

export function initSearchQuery(search_filter: SearchFilter) {
    return {
        search: '',
        start: '',
        end: '',
        search_filter,
        select_all: false
    }
}

export async function copyImage(file_url: string, setToCopied: () => void, resetCopied: () => void) {
    const $page = usePage()
    const full_url = `${$page.props.base_url}${file_url}`

    try {
        const img = new Image()
        img.crossOrigin = 'anonymous' // Avoid CORS issues
        img.src = full_url

        img.onload = async () => {
            const canvas = document.createElement('canvas')
            canvas.width = img.width
            canvas.height = img.height
            const ctx = canvas.getContext('2d')

            if (ctx) {
                ctx.drawImage(img, 0, 0) // Draw image onto canvas
            }

            canvas.toBlob(async (blob) => {
                if (!blob) return
                const item = new ClipboardItem({ 'image/png': blob }) // Convert to PNG
                await navigator.clipboard.write([item])
            }, 'image/png') // Convert JPEG to PNG

            setToCopied?.()
            const timeId = setTimeout(() => {
                resetCopied?.()
                clearTimeout(timeId)
            }, 2000) // Reset copied state after 2 seconds
        }
    } catch (error) {
        console.error('❌ Failed to copy image:', error)
    }
}

//

//

export const removeURLParameter = (url: string, parameter: string) => {
    let urlparts = url.split('?')
    if (urlparts.length >= 2) {
        let prefix = encodeURIComponent(parameter) + '='
        let pars = urlparts[1].split(/[&;]/g)

        for (let i = pars.length; i-- > 0; ) {
            if (pars[i].lastIndexOf(prefix, 0) !== -1) pars.splice(i, 1)
        }

        return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '')
    }
    return url
}

// LINK: https://stackoverflow.com/questions/1497481/javascript-password-generator
export const passwordGenerator = (prefix = '', length = 8) => {
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    let retVal = ''
    for (let i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n))
    }
    return retVal
}

export const dateTimeFormatted = (dateTime: Date | string) => {
    if (moment().format('YYYYMMDD') === moment(dateTime).format('YYYYMMDD')) {
        return moment(dateTime).format('hh:mm A')
    }
    return moment(dateTime).format('MM/DD/YYYY hh:mm A')
}

export const contentFormatter = (content: string, value: number) => {
    if (value < 1) return `No ${content}`
    if (value > 1) return `${value} ${content}s`
    return `${value} ${content}`
}

export const isVideo = (link: string) => {
    const links = link.split('.').pop()

    if (links) if (links == 'mp4') return true

    return false
}

export const taskIdSplitter = (value: number, mode: 'date_time' | 'incremental') => {
    if (mode === 'date_time') return value.toString().substring(0, 6)

    return value.toString().substring(6, 10)
}

export const upperCaseFirstChar = (value: string) => {
    if (value) return value.charAt(0).toUpperCase() + value.slice(1)

    return 'N/A'
}

export const errorAlert = (link = '/*', error: Error) => {
    alert(`Error occured on ${link} \n\n Error: ${error}`)
}

export const shortTimeAgo = (date: string) => {
    const diffInSeconds = moment().diff(moment(date), 'seconds')
    const diffInMinutes = moment().diff(moment(date), 'minutes')
    const diffInHours = moment().diff(moment(date), 'hours')
    const diffInDays = moment().diff(moment(date), 'days')
    const diffInMonths = moment().diff(moment(date), 'months')
    const diffInYears = moment().diff(moment(date), 'years')

    if (diffInSeconds < 5) return 'now'
    if (diffInSeconds < 60) return `${diffInSeconds}s`
    if (diffInMinutes < 60) return `${diffInMinutes}m`
    if (diffInHours < 24) return `${diffInHours}h`
    if (diffInDays < 30) return `${diffInDays}d`
    if (diffInMonths < 12) return `${diffInMonths}mo`
    return `${diffInYears}y`
}

export const shortTimeAgoUnix = (unixTimestamp: number) => {
    const now = moment()
    const time = moment.unix(unixTimestamp) // Convert UNIX timestamp to moment object
    const diffSeconds = now.diff(time, 'seconds')

    if (diffSeconds < 60) return `${diffSeconds}s` // Seconds
    if (diffSeconds < 3600) return `${Math.floor(diffSeconds / 60)}m` // Minutes
    if (diffSeconds < 86400) return `${Math.floor(diffSeconds / 3600)}h` // Hours
    if (diffSeconds < 2592000) return `${Math.floor(diffSeconds / 86400)}d` // Days
    if (diffSeconds < 31536000) return `${Math.floor(diffSeconds / 2592000)}mo` // Months
    return `${Math.floor(diffSeconds / 31536000)}y` // Years
}

export function getUrlHash() {
    const hash_url = window.location.hash.replace('#', '')
    return hash_url ? Number(hash_url) : 0
}

export function employeeFullName(data: { last_name: string; first_name: string; mid_name: string; extra_name: Select }, last_name_first?: boolean) {
    if (last_name_first) {
        return `${data.last_name}, ${data.first_name} ${data.mid_name ? data.mid_name.charAt(0) + '. ' : ''} ${
            data.extra_name.name == 'N/A' ? '' : data.extra_name.name + '.'
        }`
    }
    return `${data.first_name} ${data.mid_name ? data.mid_name.charAt(0) + '.' : ''} ${data.last_name}${
        data.extra_name.name == 'N/A' ? '' : ' ' + data.extra_name.name + '.'
    }`
}

export async function copyQRID(id: string) {
    // Create a clipboard item and copy the image
    const item = new ClipboardItem({ text: id })
    await navigator.clipboard.write([item])
}

export function youToName(words: string, name: string) {
    return words.replace(/\b(You|you|Your|your)\b/g, name)
}

// export function restrictRoute(route: string, roles: Role[], authRole: Role) {
//     roles.find(role => role === authRole)
//     return [route]
// }

export function formatBytes(bytes: number, decimals = 2): string {
    if (bytes === 0) return '0 Bytes'

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals

    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
}

export function phoneNumberFormatter(phone?: number): string {
    if (phone) {
        const phoneStr = phone.toString().padStart(10, '0') // Ensure it's 10 digits
        const part1 = phoneStr.slice(0, 3)
        const part2 = phoneStr.slice(3, 6)
        const part3 = phoneStr.slice(6)
        return `0${part1} ${part2} ${part3}`
    }
    return ''
}

export function maxDigit(value: number, max = 9): string {
    if (value > max) {
        return `${max}+`
    }
    return value.toString()
}

export function base64DefaultImage() {
    return 'data:image/jpg;base64,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'
}

export function messengerStyleTime(timestamp: string) {
    const now = moment()
    const date = moment(timestamp)

    if (now.isSame(date, 'day')) {
        // Today: show time like 3:15 PM
        return date.format('h:mm A')
    } else if (now.subtract(1, 'day').isSame(date, 'day')) {
        // Yesterday: show "Yesterday"
        return 'Yesterday'
    } else if (now.isSame(date, 'week')) {
        // Same week: show weekday name like "Monday"
        return date.format('dddd')
    } else if (now.isSame(date, 'year')) {
        // Same year: show like "Feb 20"
        return date.format('MMM D')
    } else {
        // Older: show like "2 years ago"
        return date.fromNow()
    }
}
