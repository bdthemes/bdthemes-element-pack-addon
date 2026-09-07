/**
 * Start crypto currency grid widget script
 */

(() => {
    'use strict';

    const widgetCrypto = (scope) => {
        const scopeElement = scope?.jquery ? scope[0] : scope;

        const cryptoWidget = scopeElement.querySelector('.bdt-crypto-currency-grid');
        if (!cryptoWidget) return;

        const settingsData = cryptoWidget.dataset.settings;
        if (!settingsData) return;

        let settings;
        try {
            settings = typeof settingsData === 'string' ? JSON.parse(settingsData) : settingsData;
        } catch (e) {
            console.error('Failed to parse crypto grid settings:', e);
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

        // ── Live price polling (every 10s) ────────────────────────────────────

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

                // Build O(1) lookup map from live data
                const liveMap = new Map(liveData.map(item => [item.id, item]));

                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-grid-item');
                const idPriceArray = [...items].map(item => ({
                    el            : item,
                    id            : item.dataset.id,
                    current_price : parseFloat(item.querySelector('.bdt-price-text')?.textContent ?? '0')
                }));

                // Apply updates only where price changed — O(n)
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
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-grid-item')
                        .forEach(item => item.classList.remove('data-changed'));
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto grid live data fetch failed:', e);
            }
        };

        // ── Initial data load ─────────────────────────────────────────────────

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

                cryptoWidget.innerHTML = '';

                if (result.apiErrors === true && result.data?.length > 0) {
                    cryptoWidget.insertAdjacentHTML('beforeend',
                        `<div class="bdt-alert-danger" bdt-alert>
                            <a class="bdt-alert-close" bdt-close></a>
                            <p>${result.data}</p>
                        </div>`
                    );
                    return;
                }

                if (!result.data?.length) return;

                result.data.slice(0, options.limit).forEach(element => {
                    const amount = returnCurrencySymbol(currencyCodeUpper) + element.current_price;

                    const imgHtml        = settings.showCurrencyImage === true
                        ? `<div class="bdt-crypto-currency-grid-img"><img src="${element.image}" alt="${element.id}"></div>` : '';
                    const symbolHtml     = settings.showCurrencyShortName === true
                        ? `<span>(${element.symbol})</span>` : '';
                    const nameHtml       = settings.showCurrencyName === true
                        ? `<div class="bdt-crypto-currency-grid-title"><h4>${element.id} ${symbolHtml}</h4></div>` : '';
                    const priceLabelHtml = settings.showCurrencyPriceLabel === true
                        ? `<div class="bdt-crypto-currency-grid-price-text"><span>price</span></div>` : '';
                    const priceHtml      = settings.showCurrencyCurrentPrice === true
                        ? `${priceLabelHtml}<div class="bdt-crypto-currency-grid-price-nu"><span class="price-int">${amount}</span></div>` : '';

                    cryptoWidget.insertAdjacentHTML('beforeend',
                        `<div class="bdt-crypto-currency-grid-item" data-id="${element.id}">
                            <div class="bdt-crypto-currency-grid-content">
                                <div class="bdt-crypto-currency-grid-bg">
                                    <img src="${element.image}" alt="${element.id}">
                                </div>
                                <div class="bdt-crypto-currency-grid-head-content">
                                    ${imgHtml}
                                    ${nameHtml}
                                </div>
                                <div class="bdt-crypto-currency-grid-bottom-content">
                                    ${priceHtml}
                                </div>
                            </div>
                            <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                        </div>`
                    );
                });

            } catch (e) {
                console.error('Crypto grid initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-grid.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency grid widget script
 */
