import './bootstrap'

import { createApp, h, DefineComponent } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import Layout from '@/layout/Layout.vue'
import Notifications from 'notiwind'
// import Echo from 'laravel-echo'

import { ZiggyVue } from 'ziggy-js'
// const echo = new Echo({
//   broadcaster: 'reverb',
//   key: import.meta.env.VITE_REVERB_APP_KEY,
//   wsHost: import.meta.env.VITE_REVERB_HOST,
//   wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//   wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//   forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//   enabledTransports: ['ws', 'wss'],
// })

const pinia = createPinia()

createInertiaApp({
    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        let page = (await pages[`./Pages/${name}.vue`]()).default
        page.layout = page.layout || Layout
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Notifications)
            .use(ZiggyVue)
            .use(pinia)
            //   .provide('echo', echo)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el)
    },
    progress: {
        color: '#CA8A05'
    }
})
