/**
 * Start carousel widget script
 */

(() => {
    'use strict';

    const widgetCarousel = (scope) => {
        const scopeElement = scope?.jquery ? scope[0] : scope;

        const carousel = scopeElement.querySelector('.bdt-ep-carousel');
        if (!carousel) return;

        const carouselContainer = carousel.querySelector('.swiper-carousel');

        const settingsData = carousel.dataset.settings;
        if (!settingsData) return;

        let settings;
        try {
            settings = typeof settingsData === 'string' ? JSON.parse(settingsData) : settingsData;
        } catch (e) {
            console.error('Failed to parse carousel settings:', e);
            return;
        }

        // data-settings can come from markup that only imitates the widget, and
        // Swiper builds some string options as HTML. Element Pack Pro's helper
        // (10.0.11+) drops those values; without it the carousel is left as is.
        if (typeof epSafeSwiperSettings !== 'function') return;

        const Swiper = window.elementorFrontend.utils.swiper;

        async function initSwiper() {
            const swiper = await new Swiper(carouselContainer, epSafeSwiperSettings(settings));

            if (settings.pauseOnHover) {
                carouselContainer.addEventListener('mouseenter', () => swiper.autoplay.stop());
                carouselContainer.addEventListener('mouseleave', () => swiper.autoplay.start());
            }
        }

        initSwiper();
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-carousel.default',   widgetCarousel);
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-carousel.bdt-alice',    widgetCarousel);
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-carousel.bdt-vertical', widgetCarousel);
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-carousel.bdt-ramble',   widgetCarousel);
        }
    });

})();

/**
 * End carousel widget script
 */
