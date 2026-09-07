/**
 * Start crypto currency chart widget script
 */

(() => {
    'use strict';

    const initItemChart = (itemEl) => {
        const hiddenInput = itemEl.querySelector('input[type="hidden"]');
        const canvasEl    = itemEl.querySelector('canvas');
        if (!hiddenInput || !canvasEl) return;

        let splitData = hiddenInput.value.split(',');
        if (splitData.length > 15) {
            splitData = splitData.slice(0, 14);
        }

        new Chart(canvasEl, {
            type : 'line',
            data : {
                labels   : splitData.map((_, i) => i),
                datasets : [{
                    label                : '',
                    backgroundColor      : 'rgba(30,135,240,0.2)',
                    borderColor          : '#1e87f0',
                    fill                 : true,
                    lineTension          : 0.4,
                    pointStyle           : 'circle',
                    pointBackgroundColor : '#1e87f0',
                    pointBorderWidth     : 1,
                    borderWidth          : 2,
                    data                 : splitData.map(Number),
                }],
            },
            options: {
                responsive : true,
                plugins    : {
                    legend  : { display: false },
                    tooltip : { enabled: true }
                },
                scales: {
                    x: { ticks: { display: false }, grid: { display: false, drawBorder: false, drawOnChartArea: false, drawTicks: false } },
                    y: { ticks: { display: false }, grid: { display: false, drawBorder: false, drawOnChartArea: false, drawTicks: false } },
                },
            },
        });
    };

    const widgetCrypto = (scope) => {
        const scopeElement = scope?.jquery ? scope[0] : scope;

        const cryptoWidget = scopeElement.querySelector('.bdt-crypto-currency-chart');
        if (!cryptoWidget) return;

        const settingsData = cryptoWidget.dataset.settings;
        if (!settingsData) return;

        let settings;
        try {
            settings = typeof settingsData === 'string' ? JSON.parse(settingsData) : settingsData;
        } catch (e) {
            console.error('Failed to parse crypto chart settings:', e);
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

                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-chart-item');
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
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-chart-item')
                        .forEach(item => item.classList.remove('data-changed'));
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto chart live data fetch failed:', e);
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

                if (!result.data?.length) return;

                result.data.slice(0, options.limit).forEach(element => {
                    const amount    = returnCurrencySymbol(currencyCodeUpper) + element.current_price;
                    const priceChange1h = element.price_change_percentage_1h;
                    const oneHourData   = (Number(priceChange1h) === priceChange1h && priceChange1h % 1 !== 0)
                        ? priceChange1h.toFixed(2) + '%'
                        : priceChange1h + '%';

                    const symbolHtml      = settings.showCurrencyShortName === true
                        ? `<span>(${element.symbol})</span>` : '';
                    const nameHtml        = settings.showCurrencyName === true
                        ? `<div class="bdt-crypto-currency-chart-title"><h4>${element.id} ${symbolHtml}</h4></div>` : '';
                    const priceHtml       = settings.showCurrencyCurrentPrice === true
                        ? `<div class="bdt-crypto-currency-chart-price-l"><span class="price-int">${amount}</span></div>` : '';
                    const priceChangeHtml = settings.showPriceChangePercentage === true
                        ? `<div class="bdt-crypto-currency-chart-change">
                               <span class="bdt-crypto-currency-chart-list-change up" title="1 Hour Data Change">${oneHourData}</span>
                           </div>` : '';

                    cryptoWidget.insertAdjacentHTML('beforeend',
                        `<div class="bdt-crypto-currency-chart-item" data-id="${element.id}">
                            <div class="bdt-crypto-currency-chart-head-content">
                                <div class="bdt-crypto-currency-chart-head-inner-content">
                                    ${nameHtml}
                                    ${priceChangeHtml}
                                </div>
                                <div class="bdt-crypto-currency-chart-bottom-inner-content">
                                    ${priceHtml}
                                </div>
                            </div>
                            <div class="bdt-crypto-currency-chart-chart">
                                <input type="hidden" class="hdnInputCanvas-${element.id}" value="${element.last_seven_days_changes}"/>
                                <div class="chart-container" style="position: relative;">
                                    <canvas id="canvas-${element.id}"></canvas>
                                </div>
                            </div>
                            <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                        </div>`
                    );

                    const itemEl = cryptoWidget.querySelector(`.bdt-crypto-currency-chart-item[data-id="${element.id}"]`);
                    if (itemEl) initItemChart(itemEl);
                });

            } catch (e) {
                console.error('Crypto chart initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-chart.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency chart widget script
 */
