let stocks = [
    {symbol: 'AAPL', value: 0},
    {symbol: 'GOOGL', value: 0},
    {symbol: 'MSFT', value: 0},
    {symbol: 'AMZN', value: 0},
    {symbol: 'FB', value: 0},
    {symbol: 'TSLA', value: 0},
];

const stocksTag = document.getElementById('stocks');

stocks.forEach(stock => {
    let div = document.createElement('div');
    div.setAttribute('id', stock.symbol);
    div.classList.add('px-4', 'py-3', 'text-sm', 'flex', 'items-center', 'justify-between', 'font-medium');
    div.innerHTML = `
        <p class="text-slate-900" id="stock-symbol">${stock.symbol}</p>
        <p class="text-slate-500" id="stock-value">${stock.value == 0 ? 'Loading...' : `$${stock.value}`}</p>
    `;

    stocksTag.appendChild(div);
});

let sse = new EventSource('http://localhost/updates-handle');

sse.onopen = function () {
    console.log('EventSource opened');
};

sse.addEventListener('stock:update', function (event) {
    updateStockValue(event.data);
});

sse.onerror = function () {
    console.log('EventSource failed');
    sse.close();
};

window.onbeforeunload = function () {
    sse.close();
};

window.onclose = function () {
    sse.close();
};

function updateStockValue(stock) {
    let stockData = JSON.parse(stock);

    if (stockData.value === 0) {
        return;
    };

    stocks.forEach((stock, index) => {
        if (stock.symbol === stockData.symbol) {
            stocks[index].value = stockData.value;
            return;
        }
    });

    stocksTag.innerHTML = '';
    stocks.forEach(stock => {
        let div = document.createElement('div');
        div.setAttribute('id', stock.symbol);
        div.classList.add('px-4', 'py-3', 'text-sm', 'flex', 'items-center', 'justify-between', 'font-medium');
        div.innerHTML = `
            <p class="text-slate-900" id="stock-symbol">${stock.symbol}</p>
            <p class="text-slate-500" id="stock-value">${stock.value == 0 ? 'Loading...' : `$${stock.value}`}</p>
        `;

        stocksTag.appendChild(div);
    });
}
