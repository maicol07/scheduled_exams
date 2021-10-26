import '../scss/app.scss';
import './_material';
import '@mdi/font/scss/materialdesignicons.scss';

import {InertiaProgress} from '@inertiajs/progress';
import {createInertiaApp} from '@maicol07/inertia-mithril';
import $ from 'cash-dom';
import m from 'mithril';

// Fix Mithril JSX durante la compilazione
m.Fragment = '[';

// Variabili globali
window.$ = $;
window.m = m;

InertiaProgress.init();

// noinspection JSIgnoredPromiseFromCall
createInertiaApp({
    title: title => `${title} - OpenSTAManager`,
    resolve: (name: string) => import(`./Views/${name}.jsx`),
    setup({
              el,
              app
          }) {
        m.mount(el, app);
    }
});
