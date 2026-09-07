/**
 * Start crypto currency card widget script
 */

(() => {
    'use strict';

    const widgetCrypto = (scope) => {
        const scopeElement = scope?.jquery ? scope[0] : scope;

        const cryptoWidget = scopeElement.querySelector('.bdt-ep-crypto-currency-card');
        if (!cryptoWidget) return;

        const settingsData = cryptoWidget.dataset.settings;
        if (!settingsData) return;

        let settings;
        try {
            settings = typeof settingsData === 'string' ? JSON.parse(settingsData) : settingsData;
        } catch (e) {
            console.error('Failed to parse crypto currency card settings:', e);
            return;
        }

        const editMode = Boolean(elementorFrontend.isEditMode());

        const options = {
            currency : settings.currency || 'usd',
            limit    : 100,
            order    : settings.order || 'market_cap_desc',
        };

        const currencySelected    = options.currency;
        const currencyCodeUpper   = currencySelected.toUpperCase();
        const ajaxUrl             = ElementPackConfig.ajaxurl;

        const getData = async () => {
            try {
                const params = new URLSearchParams({
                    currency : currencySelected,
                    per_page : 1,
                    order    : options.order,
                    ids      : settings.ids
                });

                const response = await fetch(`${ajaxUrl}?action=ep_crypto_data&${params}`);
                const liveData = await response.json();

                const liveMap = new Map(liveData.map(item => [item.id, item]));

                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-card-item');
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
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-card-item')
                        .forEach(item => item.classList.remove('data-changed'));
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto live data fetch failed:', e);
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

                const element = result.data?.[0];
                if (!element) return;

                const amount       = returnCurrencySymbol(currencyCodeUpper) + element.current_price;
                const priceChange1h = element.price_change_percentage_1h;
                const oneHourData  = (Number(priceChange1h) === priceChange1h && priceChange1h % 1 !== 0)
                    ? priceChange1h.toFixed(2) + '%'
                    : priceChange1h + '%';

                const imgHtml           = settings.showCurrencyImage === true
                    ? `<div class="bdt-ep-currency-image"><img src="${element.image}" alt="${element.id}"></div>` : '';
                const symbolHtml        = settings.showCurrencyShortName === true
                    ? `<div class="bdt-ep-currency-short-name"><span>${element.symbol}</span></div>` : '';
                const nameHtml          = settings.showCurrencyName === true
                    ? `<div class="bdt-crypto-name-wrap"><div class="bdt-ep-currency-name"><span>${element.id}</span></div>${symbolHtml}</div>` : '';
                const hourlyPriceHtml   = settings.showCurrencyChangePrice === true
                    ? `<div class="bdt-percentage" title="1 Hour Data Change">${oneHourData}</div>` : '';
                const priceHtml         = settings.showCurrencyCurrentPrice === true
                    ? `<div class="bdt-width-1-1 bdt-width-1-2@s"><div class="bdt-ep-current-price"><div class="bdt-price">${amount}</div>${hourlyPriceHtml}</div></div>` : '';
                const marketCapRankHtml = settings.showMarketCapRank === true
                    ? `<div class="bdt-ep-ccc-atribute"><span class="bdt-ep-item-text">Market Cap Rank: </span><span>#${element.market_cap_rank}</span></div>` : '';
                const marketCapHtml     = settings.showMarketCap === true
                    ? `<div class="bdt-ep-ccc-atribute"><span class="bdt-ep-item-text">Market Cap: </span><span>${element.market_cap}</span></div>` : '';
                const totalVolumeHtml   = settings.showTotalVolume === true
                    ? `<div class="bdt-ep-ccc-atribute"><span class="bdt-ep-item-text">Total Volume: </span><span>${element.total_volume}</span></div>` : '';
                const priceChangeHtml   = settings.showPriceChange === true
                    ? `<div class="bdt-ep-ccc-atribute"><span class="bdt-ep-item-text">24H Change(%): </span><span>${element.price_change_percentage_24h}</span></div>` : '';

                cryptoWidget.insertAdjacentHTML('beforeend',
                    `<div class="bdt-grid" bdt-grid data-id="${element.id}">
                        <div class="bdt-width-1-1 bdt-width-1-2@s">
                            <div class="bdt-ep-currency">
                                ${imgHtml}
                                ${nameHtml}
                            </div>
                        </div>
                        ${priceHtml}
                        <div class="bdt-width-1-1 bdt-margin-small-top bdt-ep-ccc-atributes bdt-grid-margin bdt-first-column">
                            ${marketCapRankHtml}
                            ${marketCapHtml}
                            ${totalVolumeHtml}
                            ${priceChangeHtml}
                        </div>
                        <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                    </div>`
                );

            } catch (e) {
                console.error('Crypto initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-card.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency card widget script
 */
