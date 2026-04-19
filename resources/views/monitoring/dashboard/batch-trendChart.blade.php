<div id="batchTrendChart"></div>

<script>
const isDark = document.documentElement.classList.contains('dark');

$(function () {
    $.get('/batches/trend/data', function (data) {
        renderBatchChart(data);
    });
});

function renderBatchChart(data) {

    var options = {
        series: [{
            name: 'Participants',
            data: data
        }],

        chart: {
            type: 'area',
            height: '100%',
            background: 'transparent',
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },

        theme: {
            mode: isDark ? 'dark' : 'light'
        },

        colors: ['#1e88e5'],

        stroke: {
            curve: 'smooth',
            width: 3
        },

        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },

        dataLabels: {
            enabled: false
        },

        markers: {
            size: 0,
            hover: {
                size: 5
            }
        },

        xaxis: {
            type: 'datetime',
            labels: {
                format: 'dd MMM yyyy'
            }
        },

        yaxis: {
            labels: {
                formatter: function (val) {
                    return val.toFixed(0);
                }
            }
        },

        grid: {
            borderColor: isDark ? '#2d2d2d' : '#e7e7e7',
            strokeDashArray: 4
        },

        tooltip: {
            theme: isDark ? 'dark' : 'light',
            custom: function({ dataPointIndex, w }) {

                let p = w.config.series[0].data[dataPointIndex];

                return `
                    <div style="padding:10px">
                        <strong>Date:</strong> ${new Date(p.x).toDateString()}<br>
                        <strong>Batch:</strong> ${p.batch}<br>
                        <strong>Participants:</strong> ${p.y}<br>
                        <strong>Program:</strong> ${p.program_title}<br>
                        <strong>End:</strong> ${p.date_end}
                    </div>
                `;
            }
        },

        title: {
            text: 'Training Programs Trend',
            align: 'left'
        }
    };

    new ApexCharts(document.querySelector("#batchTrendChart"), options).render();
}
</script>