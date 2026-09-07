/**
 * Start EDD Category carousel widget script
 */

(() => {
    'use strict';

    const widgetEddCategoryCarousel = async (scope) => {
        const scopeEl = scope?.jquery ? scope[0] : scope;

        const carouselEl = scopeEl.querySelector('.bdt-edd-category-carousel');
        if (!carouselEl) return;

        const containerEl = carouselEl.querySelector('.swiper-carousel');
        const settings    = JSON.parse(carouselEl.dataset.settings || '{}');

        const Swiper = elementorFrontend.utils.swiper;
        await new Swiper(containerEl, settings);

        if (settings.pauseOnHover) {
            containerEl.addEventListener('mouseenter', () => containerEl.swiper.autoplay.stop());
            containerEl.addEventListener('mouseleave', () => containerEl.swiper.autoplay.start());
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (!window.elementorFrontend?.hooks) return;

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/bdt-edd-category-carousel.default',
            widgetEddCategoryCarousel
        );
    });

})();

/**
 * End EDD category carousel widget script
 */
