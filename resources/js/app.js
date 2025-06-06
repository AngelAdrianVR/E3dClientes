import './bootstrap';
import '../css/app.css';
import 'flyonui/flyonui';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import locale from 'element-plus/dist/locale/es.mjs';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'E3D USA';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ElementPlus, { locale })   
            .mount(el);
    },
    progress: {
        color: '#D90537',
    },
});
