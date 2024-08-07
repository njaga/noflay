import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import FontAwesomeIcon from './fontawesome';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { MotionPlugin } from '@vueuse/motion';
import Loader from '@/Components/Loader.vue'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy) // Ziggy devrait être défini globalement par Laravel
            .use(MotionPlugin)
            .component('Loader', Loader);
        vueApp.component('font-awesome-icon', FontAwesomeIcon);
        return vueApp.mount(el);
    },
    progress: {
        color: '#3F51B5',
        showSpinner: true,
    },
});

// Ajout du préchargement
document.addEventListener('inertia:before', (event) => {
    const { visit } = event.detail
    if (visit.url.pathname === '/privacy-policy') {
        window.Inertia.preload('/terms-of-service')
    } else if (visit.url.pathname === '/terms-of-service') {
        window.Inertia.preload('/privacy-policy')
    }
})
