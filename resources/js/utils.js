// noinspection JSUnusedGlobalSymbols

import type {Cash} from 'cash-dom/dist/cash';
import type {Vnode} from 'mithril';
import {sync as render} from 'mithril-node-render';

type Animation = 'backInDown' | 'backInLeft' | 'backInRight' | 'backInUp' | 'backOutDown' | 'backOutLeft' | 'backOutRight' | 'backOutUp' | 'bounce' | 'bounceIn' | 'bounceInDown' | 'bounceInLeft' | 'bounceInRight' | 'bounceInUp' | 'bounceOut' | 'bounceOutDown' | 'bounceOutLeft' | 'bounceOutRight' | 'bounceOutUp' | 'fadeIn' | 'fadeInBottomLeft' | 'fadeInBottomRight' | 'fadeInDown' | 'fadeInDownBig' | 'fadeInLeft' | 'fadeInLeftBig' | 'fadeInRight' | 'fadeInRightBig' | 'fadeInTopLeft' | 'fadeInTopRight' | 'fadeInUp' | 'fadeInUpBig' | 'fadeOut' | 'fadeOutBottomLeft' | 'fadeOutBottomRight' | 'fadeOutDown' | 'fadeOutDownBig' | 'fadeOutLeft' | 'fadeOutLeftBig' | 'fadeOutRight' | 'fadeOutRightBig' | 'fadeOutTopLeft' | 'fadeOutTopRight' | 'fadeOutUp' | 'fadeOutUpBig' | 'flash' | 'flip' | 'flipInX' | 'flipInY' | 'flipOutX' | 'flipOutY' | 'headShake' | 'heartBeat' | 'hinge' | 'jackInTheBox' | 'jello' | 'lightSpeedInLeft' | 'lightSpeedInRight' | 'lightSpeedOutLeft' | 'lightSpeedOutRight' | 'pulse' | 'rollIn' | 'rollOut' | 'rotateIn' | 'rotateInDownLeft' | 'rotateInDownRight' | 'rotateInUpLeft' | 'rotateInUpRight' | 'rotateOut' | 'rotateOutDownLeft' | 'rotateOutDownRight' | 'rotateOutUpLeft' | 'rotateOutUpRight' | 'rubberBand' | 'seekers' | 'shakeX' | 'shakeY' | 'slideInDown' | 'slideInLeft' | 'slideInRight' | 'slideInUp' | 'slideOutDown' | 'slideOutLeft' | 'slideOutRight' | 'slideOutUp' | 'swing' | 'tada' | 'wobble' | 'zoomIn' | 'zoomInDown' | 'zoomInLeft' | 'zoomInRight' | 'zoomInUp' | 'zoomOut' | 'zoomOutDown' | 'zoomOutLeft' | 'zoomOutRight' | 'zoomOutUp';

/**
 * Check if class/object A is the same as or a subclass of class B.
 */
export function subclassOf(A: { ... }, B: { ... }): boolean {
  // noinspection JSUnresolvedVariable
  return A && (A === B || A.prototype instanceof B);
}

/**
 * Check if a string contains HTML code/tags
 */
export function containsHTML(string_: string): boolean {
  // eslint-disable-next-line unicorn/better-regex
  return /<[a-z][\s\S]*>/i.test(string_);
}

/**
 * Show a snackbar
 */
export async function showSnackbar(message: string, duration: number = 5000, acceptText = 'OK', cancelText: ?string): Promise<boolean> {
  const snackbar = document.createElement('mwc-snackbar');
  snackbar.labelText = message;
  snackbar.timeoutMs = duration;
  if (acceptText) {
    const button = document.createElement('mwc-button');
    button.label = acceptText;
    button.slot = 'action';
    snackbar.append(button);
  }
  if (cancelText) {
    const button = document.createElement('mwc-button');
    button.label = cancelText;
    button.slot = 'cancel';
    snackbar.append(button);
  }
  document.body.append(snackbar);

  // eslint-disable-next-line unicorn/consistent-function-scoping
  const response = (value?: boolean) => value;

  // noinspection JSUnusedLocalSymbols
  const reasonPromise = new Promise((resolve, reject) => {});
  snackbar.addEventListener('MDCSnackbar:closed', (event) => {
    response(event?.detail?.reason === 'action' ?? false);
  });
  snackbar.show();
  snackbar.addEventListener('MDCSnackbar:closed', () => {
    snackbar.remove();
  });
  return reasonPromise;
}

/**
 * Check if form is valid
 */
export function isFormValid(element: Cash | HTMLFontElement): boolean {
  let form = element;

  if (form instanceof HTMLFormElement) {
    form = $(form);
  }

  let isValid: boolean = true;

  form.find('text-field, text-area')
    .each((index: number, field: HTMLInputElement) => {
      if (!field.reportValidity()) {
        isValid = false;
      }
    });

  return isValid;
}

/**
 * Return a translation
 *
 * @param {string|Vnode} key Source string
 * @param {Object|boolean} replace Parameters to replace in translation
 * If `true` (boolean), the behaviour is the same as the `returnAsString` parameter
 * @param {boolean} returnAsString If `true` (boolean), the translation will be returned
 *                                 as string instead of a Mithril Vnode
 *
 * @returns {Vnode}
 *
 * @protected
 */
export function __(
  key: string | Vnode,
  replace: { [string]: string | Vnode | any } | boolean = {},
  returnAsString: boolean = false
): Vnode {
  let translation = key;
  // noinspection JSUnresolvedVariable
  if (window.translations && window.translations[key]) {
    translation = window.translations[key];
  }

  // Returns translation as string (no parameters replacement)
  if ((typeof replace === 'boolean' && replace) || (replace.length === 0 && !containsHTML(translation))) {
    return translation;
  }

  for (const k of Object.keys(replace)) {
    // `'attrs' in replace[k]` checks if `replace[k]` is a Mithril Vnode
    translation = translation.replace(`:${k}`, ((typeof replace[k] === 'object' && 'attrs' in replace[k]) ? render(replace[k]) : replace[k]));
  }

  if (returnAsString || !containsHTML(translation)) {
    return translation;
  }

  return window.m.trust(translation);
}

/**
 * Animate.css helper
 *
 * @param element
 * @param animation
 * @param prefix
 * @returns {Promise<unknown>}
 */
export default function animateCSS(element: string | Element, animation: Animation, prefix = 'animate__'): Promise {
  // We create a Promise and return it
  return new Promise((resolve, reject) => {
    const animationName = `${prefix}${animation}`;

    let node: Element = element;
    if (typeof node === 'string') {
      node = document.querySelector(element);
    }

    node.classList.add(`${prefix}animated`, animationName);

    // When the animation ends, we clean the classes and resolve the Promise
    function handleAnimationEnd(event) {
      event.stopPropagation();
      node.classList.remove(`${prefix}animated`, animationName);
      resolve('Animation ended');
    }

    node.addEventListener('animationend', handleAnimationEnd, {once: true});
  });
}
