// eslint-disable-next-line no-secrets/no-secrets
// noinspection JSVoidFunctionReturnValueUsed

import '../scss/app.scss';
import './_material';
import '@mdi/font/scss/materialdesignicons.scss';
import 'flag-icon-css/sass/flag-icons.scss';

import {InertiaProgress} from '@inertiajs/progress';
import {createInertiaApp} from '@maicol07/inertia-mithril';
import {type SelectedEvent} from '@material/mwc-list';
import $ from 'cash-dom';
import m from 'mithril';
import MobileDetect from 'mobile-detect';
import redaxios from 'redaxios';

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

redaxios.defaults.transformResponse = [async function (data) {
  const response = await redaxios(window.route('csrf.renew'));
  const token = response.data;
  $('meta[name="csrf-token"]').attr('content', token);
  $('input[name="_token"]').val(token);
  redaxios.defaults.headers['X-CSRF-TOKEN'] = token;

  return data;
}];

// Lang Switcher
const langSwitcher = document.querySelector('#lang-switcher');
if (langSwitcher) {
  langSwitcher.addEventListener('action', (event: SelectedEvent) => {
    const selected = $(langSwitcher).children('mwc-list-item').eq(event.detail.index).val();
    const previous = $(langSwitcher).children('mwc-list-item[selected]').val();
    if (selected !== previous) {
      window.location.href = `/lang/${selected}`;
    }
  });
}

// Theme Switcher
const themeSwitcher = document.querySelector('#theme-switcher');
if (themeSwitcher) {
  themeSwitcher.addEventListener('action', async (event: SelectedEvent) => {
    const selected = $(themeSwitcher).children('mwc-list-item').eq(event.detail.index).val();
    const previous = $(themeSwitcher).children('mwc-list-item[selected]').val();

    if (selected !== previous) {
      $('body, footer').toggleClass('mdc-theme--black');

      await redaxios.patch(window.route('theme'), {
        method: 'PATCH',
        data: {
          theme: selected
        }
      });
      window.location.reload();
    }
  });
}
