/**
 * Start crypto currency table widget script
 */

(() => {
    'use strict';

    const numFormatter = (num) => {
        if (num > 1_000_000_000) return (num / 1_000_000_000).toFixed(2) + 'B';
        if (num > 1_000_000)     return (num / 1_000_000).toFixed(2) + 'M';
        if (num > 999)           return (num / 1_000).toFixed(2) + 'K';
        return num;
    };

    const widgetCrypto = (scope) => {
        const scopeElement = scope instanceof jQuery ? scope[0] : scope;

        const cryptoWidget = scopeElement.querySelector('.bdt-crypto-currency-table');
        if (!cryptoWidget) return;

        const settingsData = cryptoWidget.dataset.settings;
        if (!settingsData) return;

        let settings;
        try {
            settings = typeof settingsData === 'string' ? JSON.parse(settingsData) : settingsData;
        } catch (e) {
            console.error('Failed to parse crypto table settings:', e);
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
                const liveMap  = new Map(liveData.map(item => [item.id, item]));
                const tableData = table.rows().data();

                // Find changed rows
                const changesIdArray = [];
                for (let i = 0; i < tableData.length; i++) {
                    const row  = tableData[i];
                    const live = liveMap.get(row.id);
                    if (live && live.current_price !== row.current_price) {
                        changesIdArray.push({
                            id            : row.id,
                            current_price : live.current_price,
                            old_price     : row.current_price,
                        });
                    }
                }

                // Update changed DataTable cells
                changesIdArray.forEach(changed => {
                    table.column(1).data().each((value, index) => {
                        if (value !== changed.id) return;

                        table.column(2).nodes().each((node, colIndex) => {
                            if (colIndex === index) {
                                table.cell(node).data(changed.current_price);
                                table.column(2).nodes()[index].classList.add('focus-item');
                            }
                        });
                    });
                });

                setTimeout(() => {
                    // DataTables passes the node as the first argument; `this` is the API instance.
                    table.column(2).nodes().each(function (node) {
                        if (node && node.classList) {
                            node.classList.remove('focus-item');
                        }
                    });
                    getData();
                }, 10000);

            } catch (e) {
                console.error('Crypto table live data fetch failed:', e);
            }
        };

        // ── DataTable init (jQuery DataTables plugin — jQuery required) ───────

        // The widget prints its table inside the wrapper. settings.tableId is
        // not used: data-settings can come from markup that only imitates the
        // widget, and jQuery() builds elements from a string holding markup.
        const tableEl = cryptoWidget.querySelector('table');
        if (!tableEl) return;

        const table = jQuery(tableEl).DataTable({
            language    : window.ElementPackConfig.data_table.language,
            destroy     : true,
            processing  : true,
            serverSide  : false,
            searching   : settings.searching,
            ordering    : settings.ordering,
            paging      : settings.paging,
            info        : settings.info,
            pageLength  : settings.pageLength,
            ajax: {
                type     : 'GET',
                dataType : 'json',
                url      : `${ajaxUrl}?action=ep_crypto`,
                data: {
                    currency : currencySelected,
                    per_page : options.limit,
                    order    : options.order,
                    ids      : settings.ids
                },
            },
            columns: [
                { data: 'market_cap_rank' },
                {
                    data   : 'id',
                    render : (data, type, row) =>
                        `<div class="bdt-coin">
                            <div class="bdt-coin-image"><img src="${row.image}" alt="${row.id}"></div>
                            <div class="bdt-coin-title">
                                <div class="bdt-coin-name">${row.id}</div>
                                <div class="bdt-coin-symbol">${row.symbol}</div>
                            </div>
                        </div>`,
                },
                {
                    data   : 'current_price',
                    render : (data) => {
                        const val = Number(data) === data && data % 1 !== 0 ? data.toFixed(2) : data;
                        return returnCurrencySymbol(currencyCodeUpper) + val;
                    },
                },
                {
                    data   : 'price_change_percentage_24h',
                    render : (data) =>
                        Number(data) === data && data % 1 !== 0 ? data.toFixed(2) + '%' : data + '%',
                },
                {
                    data   : 'market_cap',
                    render : (data) => returnCurrencySymbol(currencyCodeUpper) + numFormatter(data),
                },
                {
                    data   : 'total_volume',
                    render : (data) => returnCurrencySymbol(currencyCodeUpper) + numFormatter(data),
                },
                {
                    data   : 'circulating_supply',
                    render : (data) => numFormatter(data),
                },
                {
                    data   : 'last_seven_days_changes',
                    render : (data, type, row) =>
                        `<input type="hidden" class="hdnInputCanvas-${row.id}" value="${data}"/>` +
                        `<div class="chart-container" style="position: relative; height: 100%; width: 250px">` +
                            `<canvas id="canvas-${row.id}"></canvas>` +
                        `</div>`,
                },
            ],
            columnDefs : [{ searchable: false, orderable: false, targets: [7] }],
            order      : [[0, 'asc']],
            createdRow : (row, data) => {
                const tdEl       = row.querySelectorAll('td')[7];
                const hiddenInput = tdEl?.querySelector('input');
                const canvasEl   = tdEl?.querySelector('canvas');
                if (!hiddenInput || !canvasEl) return;

                let splitData = hiddenInput.value.split(',');
                if (splitData.length > 15) {
                    splitData = splitData.slice(0, 14);
                }

                const chart = new Chart(canvasEl, {
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
                            pointBackgroundColor : 'transparent',
                            pointBorderWidth     : 0,
                            borderWidth          : 2,
                            data                 : splitData.map(Number),
                        }],
                    },
                    options: {
                        responsive : true,
                        plugins    : {
                            legend  : { display: false },
                            tooltip : { enabled: true, callbacks: { title: () => '' } }
                        },
                        scales: {
                            x: { ticks: { display: false }, grid: { display: false, drawBorder: false, drawOnChartArea: false, drawTicks: false } },
                            y: { ticks: { display: false }, grid: { display: false, drawBorder: false, drawOnChartArea: false, drawTicks: false } },
                        },
                    },
                });

                chart.canvas.parentNode.style.width  = '100%';
                chart.canvas.parentNode.style.height = '60px';
                chart.canvas.style.width  = '100%';
                chart.canvas.style.height = '60px';
            },
        });

        if (!editMode) {
            setTimeout(getData, 5000);
        }
    };

    window.addEventListener('elementor/frontend/init', () => {
        if (window.elementorFrontend?.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/bdt-crypto-currency-table.default', widgetCrypto);
        }
    });

})();

/**
 * End crypto currency table widget script
 */
