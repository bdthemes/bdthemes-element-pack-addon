/**
 * Start EDD tabs widget script
 */

(() => {
    'use strict';

    const wrapInner = (parentEl, html) => {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const wrapper = tmp.firstElementChild;
        if (!wrapper) return;
        while (parentEl.firstChild) wrapper.appendChild(parentEl.firstChild);
        parentEl.appendChild(wrapper);
    };

    const scrollToEl = (targetEl, offset, duration, onComplete) => {
        const top = targetEl.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
        setTimeout(onComplete, duration);
    };

    const widgetTabs = (scope) => {
        const scopeEl = scope?.jquery ? scope[0] : scope;

        const tabsAreaEl = scopeEl.querySelector('.bdt-tabs-area');
        if (!tabsAreaEl) return;

        const tabsEl = tabsAreaEl.querySelector('.bdt-tabs');
        const tabEl  = tabsEl?.querySelector('.bdt-tab');
        const rawSettings = tabsEl?.dataset.settings;
        const settings = rawSettings ? JSON.parse(rawSettings) : {};

        const animTime      = settings.hashScrollspyTime ?? 500;
        const customOffset  = settings.hashTopOffset ?? 0;
        const navStickyOffset = settings.navStickyOffset ?? 10;

        // Edit mode: modal iframe links
        scopeEl.querySelectorAll('.bdt-template-modal-iframe-edit-link').forEach(linkEl => {
            const modalSelector = linkEl.dataset.modalElement;
            const modalEl       = modalSelector ? document.querySelector(modalSelector) : null;
            if (!modalEl) return;

            linkEl.addEventListener('click', (e) => {
                e.preventDefault();
                bdtUIkit.modal(modalEl).show();
            });
            modalEl.addEventListener('beforehide', () => {
                window.parent.location.reload();
            });
        });

        const hash = () => window.location.hash.substring(1);

        const hashHandler = () => {
            if (!window.location.hash || !tabEl) return;

            const targetEl = tabsEl?.querySelector(`[data-title="${hash()}"]`);
            if (!targetEl) return;

            const tabsContainer = targetEl.closest('.bdt-tabs');
            const hashTargetId  = tabsContainer?.id;
            if (!hashTargetId) return;

            const targetContainer = document.getElementById(hashTargetId);
            if (!targetContainer) return;

            const tabIndex = parseInt(targetEl.dataset.tabIndex, 10) || 0;
            scrollToEl(targetContainer, customOffset, animTime, () => {
                bdtUIkit.tab(tabEl).show(tabIndex);
            });
        };

        if (settings.activeHash === 'yes' && settings.status !== 'bdt-sticky-custom') {
            window.addEventListener('load', hashHandler);
            tabsEl?.querySelectorAll('.bdt-tabs-item-title').forEach(titleEl => {
                titleEl.addEventListener('click', (e) => {
                    const dataTitle = (titleEl.dataset.title || '').trim();
                    if (dataTitle) window.location.hash = dataTitle;
                });
            });
            window.addEventListener('hashchange', hashHandler);
        }

        const stickyHashChange = () => {
            if (!window.location.hash || !tabEl) return;

            const targetEl = tabsEl?.querySelector(`[data-title="${hash()}"]`);
            if (!targetEl) return;

            const tabsContainer = targetEl.closest('.bdt-tabs');
            const hashTargetId  = tabsContainer?.id;
            if (!hashTargetId) return;

            const targetContainer = document.getElementById(hashTargetId);
            if (!targetContainer) return;

            const tabIndex = parseInt(targetEl.dataset.tabIndex, 10) || 0;
            scrollToEl(targetContainer, navStickyOffset, 1000, () => {
                bdtUIkit.tab(tabEl).show(tabIndex);
            });
        };

        if (settings.status === 'bdt-sticky-custom') {
            tabsEl?.querySelectorAll('.bdt-tabs-item-title').forEach(titleEl => {
                titleEl.addEventListener('click', (e) => {
                    if (settings.activeHash === 'yes') {
                        const dataTitle = (titleEl.dataset.title || '').trim();
                        if (dataTitle) window.location.hash = dataTitle;
                    } else {
                        const top = tabsEl.getBoundingClientRect().top + window.scrollY - navStickyOffset;
                        window.scrollTo({ top, behavior: 'smooth' });
                    }
                });
            });

            if (settings.activeHash === 'yes') {
                window.addEventListener('load', () => {
                    if (window.location.hash) stickyHashChange();
                });
                window.addEventListener('hashchange', stickyHashChange);
            }
        }

        // linkWidget: wire up external widgets to tabs
        const editMode   = Boolean(elementorFrontend.isEditMode());
        const linkWidget = settings.linkWidgetSettings;
        const activeItem = (settings.activeItem || 1) - 1;

        if (linkWidget && !editMode) {
            linkWidget.forEach((entrySelector, index) => {
                const entryEl = document.querySelector(entrySelector);
                if (!entryEl) return;

                const parent = entryEl.parentElement;
                if (!parent) return;

                if (index === 0) {
                    const contentEl = document.getElementById('bdt-tab-content-' + settings.linkWidgetId);
                    contentEl?.parentElement?.remove();

                    wrapInner(parent, '<div class="bdt-switcher-wrapper"></div>');
                    wrapInner(parent, `<div id="bdt-tab-content-${settings.linkWidgetId}" class="bdt-switcher bdt-switcher-item-content"></div>`);

                    if (settings.activeItem === undefined) {
                        entryEl.classList.add('bdt-active');
                    }
                }

                if (settings.activeItem !== undefined && index === activeItem) {
                    entryEl.classList.add('bdt-active');
                }

                entryEl.setAttribute('data-content-id', 'tab-' + (index + 1));
            });
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (!window.elementorFrontend?.hooks) return;

        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-edd-tabs.default', widgetTabs);
    });

})();

/**
 * End EDD tabs widget script
 */
