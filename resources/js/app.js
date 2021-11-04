import '../scss/app.scss';
import './_material';
import '@mdi/font/scss/materialdesignicons.scss';

import {InertiaProgress} from '@inertiajs/progress';
import {createInertiaApp} from '@maicol07/inertia-mithril';
import $ from 'cash-dom';
import m from 'mithril';
import MobileDetect from 'mobile-detect';

import {AppInfoDialog} from './Components/InfoDialogs.jsx';
import {__} from './utils';

// Fix Mithril JSX during building
m.Fragment = '[';

// Global variables
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

window.__ = __;
window.md = new MobileDetect(window.navigator.userAgent);

// Global app info dialog
const appInfoContainer = document.querySelector('#app-info-container');
if (appInfoContainer) {
  m.mount(appInfoContainer, AppInfoDialog);
}

// Fix button links
$('a').has('mwc-button').css('text-decoration', 'none');
