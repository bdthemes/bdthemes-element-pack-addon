/**
 * Start crypto currency carousel widget script
 */

(() => {
    'use strict';

    const widgetCrypto = (scope) => {
        const scopeElement = scope?.jquery ? scope[0] : scope;

        const cryptoWidget = scopeElement.querySelector('.bdt-crypto-currency-carousel');
        if (!cryptoWidget) return;

        const cryptoSettingsData = cryptoWidget.dataset.cryptoSettings;
        if (!cryptoSettingsData) return;

        let settings;
        try {
            settings = typeof cryptoSettingsData === 'string' ? JSON.parse(cryptoSettingsData) : cryptoSettingsData;
        } catch (e) {
            console.error('Failed to parse crypto carousel settings:', e);
            return;
        }

        const editMode = Boolean(elementorFrontend.isEditMode());

        const options = {
            currency : settings.currency || 'usd',
            limit    : settings.limit    || 100,
            order    : settings.order    || 'market_cap_desc',
        };

        const currencySelected  = options.currency;
        const currencyCodeUpper = currencySelected.toUpperCase();
        const ajaxUrl           = ElementPackConfig.ajaxurl;

        const getData = async () => {
            try {
                const params = new URLSearchParams({
                    currency : currencySelected,
                    per_page : options.limit,
                    order    : options.order,
                    ids      : settings.ids
                });

                const response = await fetch(`${ajaxUrl}?action=ep_crypto_data&${params}`);
                const liveData = await response.json();

                const liveMap = new Map(liveData.map(item => [item.id, item]));

                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-carousel-item');
                const idPriceArray = [...items].map(item => ({
                    el            : item,
                    id            : item.dataset.id,
                    current_price : parseFloat(item.querySelector('.bdt-price-text')?.textContent ?? '0')
                }));

                idPriceArray.forEach(({ el, id, current_price }) => {
                    const live = liveMap.get(id);
                    if (!live || live.current_price === current_price) return;

                    el.classList.add('data-changed');

                    const priceTextEl = el.querySelector('.bdt-price-text');
                    if (priceTextEl) priceTextEl.textContent = live.current_price;

                    const priceIntEl = el.querySelector('.price-int');
                    if (priceIntEl) priceIntEl.textContent = returnCurrencySymbol(currencyCodeUpper) + live.current_price;
                });

                setTimeout(() => {
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-carousel-item')
                        .forEach(item => item.classList.remove('data-changed'));
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto carousel live data fetch failed:', e);
            }
        };

        const loadInitialData = async () => {
            try {
                const params = new URLSearchParams({
                    currency : currencySelected,
                    per_page : options.limit,
                    order    : options.order,
                    ids      : settings.ids
                });

                const response = await fetch(`${ajaxUrl}?action=ep_crypto&${params}`);
                const result   = await response.json();

                const swiperWrapper = cryptoWidget.querySelector('.swiper-wrapper');
                if (swiperWrapper) swiperWrapper.innerHTML = '';

                if (!result.data?.length) return;

                result.data.slice(0, options.limit).forEach(element => {
                    const amount = returnCurrencySymbol(currencyCodeUpper) + element.current_price;

                    const imgHtml        = settings.showCurrencyImage === true
                        ? `<div class="bdt-crypto-currency-carousel-img"><img src="${element.image}" alt="${element.id}"></div>` : '';
                    const symbolHtml     = settings.showCurrencyShortName === true
                        ? `<span>(${element.symbol})</span>` : '';
                    const nameHtml       = settings.showCurrencyName === true
                        ? `<div class="bdt-crypto-currency-carousel-title"><h4>${element.id} ${symbolHtml}</h4></div>` : '';
                    const priceLabelHtml = settings.showCurrencyPriceLabel === true
                        ? `<div class="bdt-crypto-currency-carousel-price-text"><span>price</span></div>` : '';
                    const priceHtml      = settings.showCurrencyCurrentPrice === true
                        ? `${priceLabelHtml}<div class="bdt-crypto-currency-carousel-price-nu"><span class="price-int">${amount}</span></div>` : '';

                    swiperWrapper?.insertAdjacentHTML('beforeend',
                        `<div class="swiper-slide">
                            <div class="bdt-crypto-currency-carousel-item" data-id="${element.id}">
                                <div class="bdt-crypto-currency-carousel-content">
                                    <div class="bdt-crypto-currency-carousel-bg">
                                        <img src="${element.image}" alt="${element.id}">
                                    </div>
                                    <div class="bdt-crypto-currency-carousel-head-content">
                                        ${imgHtml}
                                        ${nameHtml}
                                    </div>
                                    <div class="bdt-crypto-currency-carousel-bottom-content">
                                        ${priceHtml}
                                    </div>
                                </div>
                                <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                            </div>
                        </div>`
                    );
                });

                const carouselContainer = cryptoWidget.querySelector('.swiper-carousel');
                const carouselSettingsData = cryptoWidget.dataset.settings;
                const carouselSettings = carouselSettingsData
                    ? (typeof carouselSettingsData === 'string' ? JSON.parse(carouselSettingsData) : carouselSettingsData)
                    : {};

                const Swiper = elementorFrontend.utils.swiper;
                const swiper = await new Swiper(carouselContainer, carouselSettings);

                if (carouselSettings.pauseOnHover) {
                    carouselContainer.addEventListener('mouseenter', () => swiper.autoplay.stop());
                    carouselContainer.addEventListener('mouseleave', () => swiper.autoplay.start());
                }

            } catch (e) {
                console.error('Crypto carousel initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-carousel.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency carousel widget script
 */
