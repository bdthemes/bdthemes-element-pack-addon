/**
 * Start crypto currency ticker widget script
 */

(() => {
    'use strict';

    const widgetCrypto = (scope) => {
        const scopeEl = scope instanceof jQuery ? scope[0] : scope;

        const cryptoWidget = scopeEl.querySelector('.bdt-crypto-currency-ticker');
        if (!cryptoWidget) return;

        const settings       = cryptoWidget.dataset.settings       ? JSON.parse(cryptoWidget.dataset.settings)       : {};
        const tickerSettings = cryptoWidget.dataset.tickerSettings  ? JSON.parse(cryptoWidget.dataset.tickerSettings)  : {};
        const editMode       = Boolean(elementorFrontend.isEditMode());

        const options = {
            currency : settings.currency || 'usd',
            limit    : settings.limit    || 100,
            order    : settings.order    || 'market_cap_desc',
        };

        const currencySelected  = options.currency;
        const currencyCodeUpper = currencySelected.toUpperCase();
        const ajaxUrl           = ElementPackConfig.ajaxurl;
        const listEl            = cryptoWidget.querySelector('ul');

        // ── Live price polling ────────────────────────────────────────────────

        const getData = async () => {
            try {
                const params = new URLSearchParams({
                    currency : currencySelected,
                    per_page : options.limit,
                    order    : options.order,
                    ids      : settings.ids,
                });

                const response = await fetch(`${ajaxUrl}?action=ep_crypto_data&${params}`);
                const liveData = await response.json();

                // Build O(1) lookup map from live data
                const liveMap = new Map(liveData.map(item => [item.id, item]));

                // Read current prices from DOM
                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-ticker-item');
                const changesIdArray = [];

                items.forEach(item => {
                    const id           = item.dataset.id;
                    const currentPrice = parseFloat(item.querySelector('.bdt-price-text')?.textContent ?? 0);
                    const live         = liveMap.get(id);

                    if (live && live.current_price !== currentPrice) {
                        changesIdArray.push({
                            id            : id,
                            current_price : live.current_price,
                            old_price     : currentPrice,
                        });
                    }
                });

                if (changesIdArray.length) {
                    changesIdArray.forEach(({ id, current_price }) => {
                        const itemEl = cryptoWidget.querySelector(`[data-id="${id}"]`);
                        if (!itemEl) return;

                        itemEl.classList.add('data-changed');
                        const priceTextEl = itemEl.querySelector('.bdt-price-text');
                        if (priceTextEl) priceTextEl.textContent = current_price;

                        const priceIntEl = itemEl.querySelector('.price-int');
                        if (priceIntEl) priceIntEl.textContent = returnCurrencySymbol(currencyCodeUpper) + current_price;
                    });
                }

                setTimeout(() => {
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-ticker-item').forEach(el => {
                        el.classList.remove('data-changed');
                    });
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto ticker live data fetch failed:', e);
            }
        };

        // ── Initial data load ─────────────────────────────────────────────────

        const loadInitialData = async () => {
            try {
                const params = new URLSearchParams({
                    currency : currencySelected,
                    per_page : options.limit,
                    order    : options.order,
                    ids      : settings.ids,
                });

                const response = await fetch(`${ajaxUrl}?action=ep_crypto&${params}`);
                const result   = await response.json();

                if (!listEl || !result?.data) return;

                listEl.innerHTML = '';

                result.data.slice(0, options.limit).forEach(element => {
                    const amount     = returnCurrencySymbol(currencyCodeUpper) + element.current_price;
                    const rawChange  = element.price_change_percentage_1h;
                    const oneHourPct = (Number(rawChange) === rawChange && rawChange % 1 !== 0)
                        ? rawChange.toFixed(2) + '%'
                        : rawChange + '%';

                    const imgHtml = settings.showCurrencyImage
                        ? `<div class="bdt-crypto-currency-ticker-img"><img src="${element.image}" alt="${element.id}"></div>`
                        : '';

                    const symbolHtml = settings.showCurrencyShortName
                        ? `<span>(${element.symbol})</span>`
                        : '';

                    const nameHtml = settings.showCurrencyName
                        ? `<h3 class="bdt-crypto-currency-ticker-title">${element.id} ${symbolHtml}</h3>`
                        : '';

                    const priceHtml = settings.showCurrencyCurrentPrice
                        ? `<span class="bdt-crypto-currency-ticker-price">${amount}</span>`
                        : '';

                    const pctHtml = settings.showPriceChangePercentage
                        ? `<div class="bdt-crypto-currency-ticker-percentage">
                                <svg width="25" height="22" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6.646 11.646a.5.5 0 01.708 0L10 14.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M10 4.5a.5.5 0 01.5.5v9a.5.5 0 01-1 0V5a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
                                </svg>
                                <span>${oneHourPct}</span>
                            </div>`
                        : '';

                    listEl.insertAdjacentHTML('beforeend',
                        `<li class="bdt-crypto-currency-ticker-item" data-id="${element.id}">
                            <div class="bdt-crypto-currency-ticker-inner-item">
                                ${imgHtml}
                                <div class="bdt-crypto-currency-ticker-content">
                                    ${nameHtml}
                                    ${priceHtml}
                                    ${pctHtml}
                                </div>
                            </div>
                            <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                        </li>`
                    );
                });

            } catch (e) {
                console.error('Crypto ticker initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            // Start live polling and ticker plugin after initial data has had time to load
            setTimeout(() => {
                getData();
                jQuery(cryptoWidget).epNewsTicker(tickerSettings); // jQuery required for epNewsTicker plugin
            }, 7000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-ticker.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency ticker widget script
 */
