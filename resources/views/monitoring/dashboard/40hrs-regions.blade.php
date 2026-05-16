

<div class="w-full h-full p-5">

    <div class="flex gap-2 items-center"> 
        <h1 class="poppins-bold text-lg bg-sky-100 text-sky-900 px-2 rounded-lg"><i class="fa-solid fa-clock"></i> 40 Hours</h1>
        <div>
            <p class="poppins-semibold text-sky-800 text-xs leading-4 dark:text-slate-100">Employees Program Duration with Supervisory Programs per Region</p>
            <p  id="forty_regions_baseText" class="poppins-semibold text-sky-800 text-xs leading-4 dark:text-slate-100"></p>
        </div>
    </div>


    <div class="w-full m-0 p-0 " id="forty_regions_chart"></div>


</div>


<script>
var forty_regions_options = {
  chart: {
    type: 'bar',
    stacked: true,
    stackType: '100%',
    height: 500,
    parentHeightOffset: 0,
    toolbar: {
        show: false
    }
  },
  grid: {
    padding: {
      top: 0,
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
        name: 'With Training',
        data: [100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100],
    },     
    {
        name: 'No Training',
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
  colors: ['#48A111','rgba(3, 174, 210,0.6)'],
    dataLabels: { 
        enabled:true,
        formatter: function (val, opts) {
        // shows actual value instead of percentage
        return opts.w.config.series[opts.seriesIndex].data[opts.dataPointIndex];
        },
        style: {
            fontSize: '14px',
            colors: ['#FFF6F6']
        },
    },
    
    legend: { 
        // show: false,
        position: 'top', 
        horizontalAlign: 'center',
        fontSize: '12px',
        itemMargin: {
            horizontal: 0,
            vertical: 0
        },
        offsetY: 20,   
    },
};

var forty_regions_chart = new ApexCharts(document.querySelector("#forty_regions_chart"), forty_regions_options);

forty_regions_chart.render().then(() => {
    update40hrsBarChartTheme();
});


function showRegionFortyHours(){
    forty_regions_chart.updateOptions({
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
                    ]
                },
                series: [
                    {
                        name: 'With Training',
                        data: [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
                    },     
                    {
                        name: 'No Training',
                        data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                    }
                ]
            });



    $.ajax({
        url: '/training-stats/40hrs',
        data: {
            region: $('#region_select').val(),
            plant_status: $('input[name="plant_status[]"]:checked').map(function () {
                return this.value;
            }).get(),
            sg_condition: $('#sg_condition').val(),
            sg_value: $('#sg_value').val()
        },
        success: function(res) {
            // console.log(res.regions_trained)
            forty_regions_chart.updateOptions({
                xaxis: {
                    categories: res.regions
                },
                series: [
                    {
                        name: 'With Training',
                        data: res.regions_trained   
                    },
                    {
                        name: 'No Training',
                        data: res.regions_not_trained
                    }
                ]
            });
        }
    });
}




$(document).ready(function () {
    showRegionFortyHours();
    debouncedDescribeSG();
});

$('#region_select, #sg_condition, #sg_value, input[name="plant_status[]"], #office_filter').on('change', function () {
    showRegionFortyHours();
    debouncedDescribeSG();
});




function debounce(fn, delay = 500) {
  let timer;

  return function (...args) {
    clearTimeout(timer);

    timer = setTimeout(() => {
      fn.apply(this, args);
    }, delay);
  };
}

const operatorMap = {
  "=": "Equal to",
  ">": "Greater than",
  ">=": "Greater than or equal to",
  "<": "Less than",
  "<=": "Less than or equal to"
};

function describeSG() {
  const operator = document.getElementById("sg_condition").value;
  const value = document.getElementById("sg_value").value;

  let baseText = "";

  if (value) {
    const text = operatorMap[operator] || operator;
    baseText += ` (${text} SG ${value})`;
  }
  $('#forty_regions_baseText').text(baseText);
}


const debouncedDescribeSG = debounce(describeSG, 600);

function update40hrsBarChartTheme() {
    const isDark = document.documentElement.classList.contains('dark');

    forty_regions_chart.updateOptions({
        theme: {
            mode: isDark ? 'dark' : 'light'
        },

        xaxis: {
            labels: {
                style: {
                    colors: isDark ? '#e5e7eb' : '#374151' // gray-200 / gray-700
                }
            }
        },

        yaxis: {
            labels: {
                style: {
                    colors: isDark ? '#e5e7eb' : '#374151'
                }
            }
        },

        legend: {
            labels: {
                colors: isDark ? '#ffffff' : '#000000'
            }
        },

        // dataLabels: {
        //     style: {
        //         colors: isDark ? ['#ffffff'] : ['#111827'] // white / dark text
        //     }
        // },

        grid: {
            borderColor: isDark ? '#374151' : '#e5e7eb'
        },
        chart: {
            background: 'transparent'
        }
    });
}


</script>
