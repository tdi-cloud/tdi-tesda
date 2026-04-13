<div id="trend_chart" class="!focus:outline-none"></div>
<script>
var dates = [
  { x: new Date('2026-01-01').getTime(), y: 2 },
  { x: new Date('2026-01-07').getTime(), y: 3 },
  { x: new Date('2026-01-13').getTime(), y: 6 },
  { x: new Date('2026-01-14').getTime(), y: 4 },
  { x: new Date('2026-01-18').getTime(), y: 3 },
  { x: new Date('2026-01-24').getTime(), y: 2 },
  { x: new Date('2026-01-27').getTime(), y: 3 },
  { x: new Date('2026-01-30').getTime(), y: 1 },
  { x: new Date('2026-02-01').getTime(), y: 2 },
  { x: new Date('2026-02-07').getTime(), y: 3 },
  { x: new Date('2026-02-13').getTime(), y: 7 },
  { x: new Date('2026-02-14').getTime(), y: 8 },
  { x: new Date('2026-02-18').getTime(), y: 9 },
  { x: new Date('2026-02-24').getTime(), y: 10 },
  { x: new Date('2026-02-27').getTime(), y: 11 },
  { x: new Date('2026-02-28').getTime(), y: 3 },
].sort((a, b) => a.x - b.x);

var options1 = {
          series: [{
          name: 'PROGRAMS',
          data: dates
        }],
          chart: {
          type: 'area',
          stacked: false,
          height: 200,
          zoom: {
            type: 'x',
            enabled: true,
            autoScaleYaxis: true
          },
          toolbar: {
            autoSelected: 'zoom'
          }
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
        },
        title: {
          text: null,
          align: 'left',
          show: false
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        yaxis: {
            shared: false,
          labels: {
            formatter: function (val) {
              return val;
            },
          },
          title: {
            text: null,
            show: false
          },
        },
        xaxis: {
          type: 'datetime',
        },
        tooltip: {
          shared: false,
          y: {
            formatter: function (val) {
              return (val / 1000000).toFixed(0)
            }
          }
        }
};

var trendchart = new ApexCharts(document.querySelector("#trend_chart"), options1);

trendchart.render();
</script>