// import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { helper } from './helper';
import { createI18n } from 'vue-i18n'
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import Quill from 'quill';
import ImageResize from 'quill-image-resize-module';
import { ImageDrop } from 'quill-image-drop-module';
Quill.register('modules/imageDrop', ImageDrop)
Quill.register('modules/imageResize', ImageResize);
import engFile from '../js/translate/english';
import khFile from '../js/translate/khmer';

const vuetify = createVuetify({
    components,
    directives,
});

const messages = {
    en: engFile.en,
    kh: khFile.kh,
};

const storedLocale = localStorage.getItem('locale') || 'en';
const i18n = createI18n({
    locale: storedLocale,
    messages,
    missingWarn: false,
    fallbackWarn: false
});

// axios.defaults.baseURL = 'http://localhost:8000/';
createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        // Register the helper globally
        app.config.globalProperties.$helper = helper;
        app.component('QuillEditor', QuillEditor);
        return app
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(createPinia())
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
