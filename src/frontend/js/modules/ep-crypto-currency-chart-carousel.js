/**
 * Start crypto currency chart carousel widget script
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

        const labels         = splitData.map((_, i) => i);
        const dataPointvalue = splitData.map(Number);

        new Chart(canvasEl, {
            type : 'line',
            data : {
                labels,
                datasets: [{
                    label                : '',
                    backgroundColor      : 'rgba(30,135,240,0.2)',
                    borderColor          : '#1e87f0',
                    fill                 : true,
                    lineTension          : 0.4,
                    pointStyle           : 'circle',
                    pointBackgroundColor : '#1e87f0',
                    pointBorderWidth     : 1,
                    borderWidth          : 2,
                    data                 : dataPointvalue,
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

        const cryptoWidget = scopeElement.querySelector('.bdt-crypto-currency-chart-carousel');
        if (!cryptoWidget) return;

        const cryptoSettingsData = cryptoWidget.dataset.cryptoSettings;
        if (!cryptoSettingsData) return;

        let settings;
        try {
            settings = typeof cryptoSettingsData === 'string' ? JSON.parse(cryptoSettingsData) : cryptoSettingsData;
        } catch (e) {
            console.error('Failed to parse crypto chart carousel settings:', e);
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

                const items = cryptoWidget.querySelectorAll('.bdt-crypto-currency-chart-carousel-item');
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
                    cryptoWidget.querySelectorAll('.bdt-crypto-currency-chart-carousel-item')
                        .forEach(item => item.classList.remove('data-changed'));
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto chart carousel live data fetch failed:', e);
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
                    const amount    = returnCurrencySymbol(currencyCodeUpper) + element.current_price;
                    const priceChange1h = element.price_change_percentage_1h;
                    const oneHourData   = (Number(priceChange1h) === priceChange1h && priceChange1h % 1 !== 0)
                        ? priceChange1h.toFixed(2) + '%'
                        : priceChange1h + '%';

                    const symbolHtml      = settings.showCurrencyShortName === true
                        ? `<span>(${element.symbol})</span>` : '';
                    const nameHtml        = settings.showCurrencyName === true
                        ? `<div class="bdt-crypto-currency-chart-carousel-title"><h4>${element.id} ${symbolHtml}</h4></div>` : '';
                    const priceHtml       = settings.showCurrencyCurrentPrice === true
                        ? `<div class="bdt-crypto-currency-chart-carousel-price-l"><span>${amount}</span></div>` : '';
                    const priceChangeHtml = settings.showPriceChangePercentage === true
                        ? `<div class="bdt-crypto-currency-chart-carousel-change">
                               <span class="bdt-crypto-currency-chart-carousel-list-change up" title="1 Hour Data Change">${oneHourData}</span>
                           </div>` : '';

                    swiperWrapper?.insertAdjacentHTML('beforeend',
                        `<div class="swiper-slide">
                            <div class="bdt-crypto-currency-chart-carousel-item" data-id="${element.id}">
                                <div class="bdt-crypto-currency-chart-carousel-head-content">
                                    <div class="bdt-crypto-currency-chart-carousel-head-inner-content">
                                        ${nameHtml}
                                        ${priceChangeHtml}
                                    </div>
                                    <div class="bdt-crypto-currency-chart-carousel-bottom-inner-content">
                                        ${priceHtml}
                                    </div>
                                </div>
                                <div class="bdt-crypto-currency-chart-carousel-chart">
                                    <input type="hidden" class="hdnInputCanvas-${element.id}" value="${element.last_seven_days_changes}"/>
                                    <div class="chart-container" style="position: relative;">
                                        <canvas id="canvas-${element.id}"></canvas>
                                    </div>
                                </div>
                                <span class="bdt-price-text bdt-hidden">${element.current_price}</span>
                            </div>
                        </div>`
                    );

                    const itemEl = cryptoWidget.querySelector(`.bdt-crypto-currency-chart-carousel-item[data-id="${element.id}"]`);
                    if (itemEl) initItemChart(itemEl);
                });

                const carouselContainer    = cryptoWidget.querySelector('.swiper-carousel');
                const carouselSettingsData = cryptoWidget.dataset.settings;
                const carouselSettings     = carouselSettingsData
                    ? (typeof carouselSettingsData === 'string' ? JSON.parse(carouselSettingsData) : carouselSettingsData)
                    : {};

                // data-settings can come from markup that only imitates the widget, and
                // Swiper builds some string options as HTML. Element Pack Pro's helper
                // (10.0.11+) drops those values; without it the carousel is left as is.
                if (typeof epSafeSwiperSettings !== 'function') return;

                const Swiper = elementorFrontend.utils.swiper;
                const swiper = await new Swiper(carouselContainer, epSafeSwiperSettings(carouselSettings));

                if (carouselSettings.pauseOnHover) {
                    carouselContainer.addEventListener('mouseenter', () => swiper.autoplay.stop());
                    carouselContainer.addEventListener('mouseleave', () => swiper.autoplay.start());
                }

            } catch (e) {
                console.error('Crypto chart carousel initial data fetch failed:', e);
            }
        };

        loadInitialData();

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-chart-carousel.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency chart carousel widget script
 */
