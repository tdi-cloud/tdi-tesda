<div class="bg-white border-1 border-slate-300 rounded-2xl shadow-lg dark:bg-slate-700 dark:border-slate-600">

    <div class="flex justify-between ">

        <div class=" min-h-full flex flex-col justify-between p-5 max-w-1/2">
                <h1 class="poppins-bold text-lg text-emerald-900 dark:text-white leading-5">Terminal Report</h1>
                <span class="text-xs poppins-medium flex flex-col">
                    <span class="text-emerald-600"><i class="fa-solid fa-square text-lg"></i> Submitted</span> 
                    <span class="text-red-600"><i class="fa-solid fa-square text-lg"></i> No Submission</span>
                </span>
        </div>

        <div class=" flex-1 flex flex-col items-end justify-center  w-auto">
            <h1 class="text-2xl mono text-emerald-600 leading-5" id="SubmittedNumber">0</h1>
            <h1 class="text-2xl mono text-red-600 leading-5" id="NotSubmittedNumber">0</h1>
            <p class="text-2xl  text-slate-400 dark:text-white text-xs">total <span class="mono text-lg" id="TreapTotal">0</span></p>
        </div>

        <div class=" relative m-0 p-0 w-40">
            <div id="TreapRadialChart" class="w-full"></div>
            <h1 class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 text-3xl text-emerald-600 mono"><span id="treapPerc"></span>%</h1>
        </div>
    </div>

    <div class="px-5">

        <div class="w-full m-0 p-0 " id="treap_regional_chart"></div>

    </div>

</div>


<script>
    function fetchTreapDashboard() {
    const officeFilter = document.getElementById('office_filter').value;
    const regionFilter = document.getElementById('region_select').value;

    const checkedStatuses = [...document.querySelectorAll('.type_checkbox:checked')]
        .map(cb => cb.value);

    const params = new URLSearchParams();
    params.append('office_filter', officeFilter);
    params.append('region', regionFilter);
    checkedStatuses.forEach(status => params.append('plant_status[]', status));

    fetch(`/treap-dashboard?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        TreapRadialChart.updateSeries([Number(data.totals.percentage)]);
        animateNumber('#treapPerc', 0, data.radial_chart.series, 500);
        animateNumber('#SubmittedNumber', 0, data.totals.with, 500);
        animateNumber('#NotSubmittedNumber', 0, data.totals.without, 500);
        animateNumber('#TreapTotal', 0, data.totals.with + data.totals.without, 500);

        treap_regional_chart.updateOptions({
                xaxis: {
                    categories: data.region_chart.xaxis
                },
                series: [
                    data.region_chart.series[0],
                    data.region_chart.series[1]
                ]
            });
   
    })
    .catch(err => console.error('TREAP Dashboard Error:', err));
}

// Attach listeners
document.getElementById('office_filter').addEventListener('change', fetchTreapDashboard);
document.getElementById('region_select').addEventListener('change', fetchTreapDashboard);
document.querySelectorAll('.type_checkbox').forEach(cb => {
    cb.addEventListener('change', fetchTreapDashboard);
});

var TreapRadialOptions = {
    chart: {
        type: 'radialBar',
        sparkline: {
            enabled: true
        }
    },

    series: [100], 

    plotOptions: {
        radialBar: {
            startAngle: 0,
            endAngle: 360,
            hollow: {
                size: '60%'
            },
            track: {
                background: 'rgba(217, 34, 67,0.3)',
                strokeWidth: '100%',
                margin: 0,
            },
            dataLabels: {
                show: false
            }
        }
    },
    colors: ['#48A111'],

    stroke: {
        lineCap: 'round'
    }
};

var TreapRadialChart = new ApexCharts(
    document.querySelector("#TreapRadialChart"),
    TreapRadialOptions
);

TreapRadialChart.render();

// Initial load
fetchTreapDashboard();


var treap_regional_options = {
  chart: {
    type: 'bar',
    stacked: true,
    stackType: '100%',
    height: 500,
    parentHeightOffset: 0,
    toolbar: {
        show: false
    },
  },
  grid: {
    padding: {
      top: -30,
      right: 0,
      bottom: 0,
      left: 0
    }
  },
  plotOptions: {
    bar: {
        horizontal: true,
        borderRadius: 6,
        borderRadiusApplication: 'around', 
        borderRadiusWhenStacked: 'all',
        barHeight: '70%',
    }
  },
  series: [
    {
        name: 'Submitted',
        data: [100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100],
    },     
    {
        name: 'No Submission',
        data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
    }
  ],
  xaxis: {
    categories: [
        'CO',
        'NCR',
        'R1',
        'R2',
        'R3',
        'R4A',
        'R4B',
        'R5',
        'NIR',
        'R6',
        'R7',
        'R8',
        'R9',
        'R10',
        'R11',
        'R12',
        'CAR',
        'CARAGA',
    ],
    min: 0,
    // max: 500,
  },
  yaxis: {
    labels: {
        align: 'left',
        offsetY: 4,  
        offsetX: -10,
        style: {
            fontSize: '12px',
        } 
    }
    },
  colors: ['#48A111','rgba(217, 34, 67,0.5)'],
    
    legend: { 
        show: false,  
    },
};

var treap_regional_chart = new ApexCharts(document.querySelector("#treap_regional_chart"), treap_regional_options);
treap_regional_chart.render();



</script>