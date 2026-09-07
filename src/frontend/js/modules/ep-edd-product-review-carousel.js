/**
 * Start EDD product review carousel widget script
 */

(() => {
    'use strict';

    const widgetProductReviewCarousel = async (scope) => {
        const scopeEl = scope?.jquery ? scope[0] : scope;

        const carouselEl = scopeEl.querySelector('.ep-edd-product-review-carousel');
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
            'frontend/element_ready/bdt-edd-product-review-carousel.default',
            widgetProductReviewCarousel
        );
    });

})();

/**
 * End EDD product review carousel widget script
 */
