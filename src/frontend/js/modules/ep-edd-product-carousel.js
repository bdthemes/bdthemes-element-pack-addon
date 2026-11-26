/**
 * Start EDD product carousel widget script
 */

(() => {
    'use strict';

    const widgetEddProductCarousel = async (scope) => {
        const scopeEl = scope?.jquery ? scope[0] : scope;

        const carouselEl = scopeEl.querySelector('.bdt-edd-product-carousel');
        if (!carouselEl) return;

        const containerEl = carouselEl.querySelector('.swiper-carousel');
        const settings    = JSON.parse(carouselEl.dataset.settings || '{}');

        // data-settings can come from markup that only imitates the widget, and
        // Swiper builds some string options as HTML. Element Pack Pro's helper
        // (10.0.11+) drops those values; without it the carousel is left as is.
        if (typeof epSafeSwiperSettings !== 'function') return;

        const Swiper = elementorFrontend.utils.swiper;
        await new Swiper(containerEl, epSafeSwiperSettings(settings));

        if (settings.pauseOnHover) {
            containerEl.addEventListener('mouseenter', () => containerEl.swiper.autoplay.stop());
            containerEl.addEventListener('mouseleave', () => containerEl.swiper.autoplay.start());
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (!window.elementorFrontend?.hooks) return;

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/bdt-edd-product-carousel.default',
            widgetEddProductCarousel
        );
    });

})();

/**
 * End EDD product carousel widget script
 */
