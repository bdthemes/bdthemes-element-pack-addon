/**
 * Start honeycombs widget script
 */

(() => {
    'use strict';

    const widgetHoneycombs = (scope) => {
        const scopeEl = scope instanceof jQuery ? scope[0] : scope;

        const honeycombsAreaEl = scopeEl.querySelector('.bdt-honeycombs-area');
        if (!honeycombsAreaEl) return;

        const honeycombsEl = honeycombsAreaEl.querySelector('.bdt-honeycombs');
        if (!honeycombsEl) return;

        const rawSettings = honeycombsEl.dataset.settings;
        const settings = rawSettings ? JSON.parse(rawSettings) : {};
        if (!settings) return;

        jQuery(honeycombsEl).honeycombs({
            combWidth   : settings.width,
            margin     : settings.margin,
            threshold  : 3,
            widthTablet: settings.width_tablet,
            widthMobile: settings.width_mobile,
            viewportLg : settings.viewport_lg,
            viewportMd : settings.viewport_md,
        });

        honeycombsEl.classList.add('honeycombs-loaded');
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (!window.elementorFrontend?.hooks) return;

        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-honeycombs.default', widgetHoneycombs);
    });

})();

/**
 * End honeycombs widget script
 */
