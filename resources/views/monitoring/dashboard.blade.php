<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>

<section class="p-5 space-y-5">
    

    <div class="grid grind-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 ">


        {{-- PROGRAMS COUNT --}}
        <div class="TRAININGS w-full bg-gradient-to-r from-sky-800 to-sky-900 dark:border-slate-600 border border-slate-300 rounded-2xl ">
            @include('monitoring.dashboard.training-compliance')
        </div>

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl pt-4">
            @include('monitoring.dashboard.training-trend')
        </div>

       

    </div>

    {{-- 2ND SECTION  --}}

    <div class="grid grid-cols-2 gap-4 items-start hidden">

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl p-5">
            <div id="chart1" class="w-100"></div>


            

        </div>


        <div class="w-full bg-white dark:bg-slate-600 dark:border-slate-700 border border-slate-300 rounded-2xl p-5">
            <div id="candleChart" class=""></div>

            <script>
                const stockData = [
                { 
                    symbol: 'AAPL', 
                    date: '2024-01-01',
                    prices: { open: 180, high: 185, low: 178, close: 183 },
                    volume: 50000000,
                    metrics: { rsi: 65, sma: 182 }
                },
                { 
                    symbol: 'AAPL', 
                    date: '2024-01-02',
                    prices: { open: 183, high: 187, low: 181, close: 186 },
                    volume: 4800000,
                    metrics: { rsi: 68, sma: 183 }
                }
                ];

                // Candlestick chart with nested objects
                const candlestickChart = new ApexCharts(document.querySelector("#candleChart"), {
                series: [{
                    name: 'AAPL Stock Price',
                    data: stockData,
                    parsing: {
                    x: 'date',
                    y: ['prices.open', 'prices.high', 'prices.low', 'prices.close']
                    }
                }],
                chart: { type: 'candlestick' }
                });

                
                candlestickChart.render();
            </script>
        </div>

    </div>

</section>    

</x-monitoring-layout>
</x-layout>