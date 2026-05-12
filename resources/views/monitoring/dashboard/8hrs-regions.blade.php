

<div class="w-full h-full p-5">

    <div class="flex gap-2 "> 
        <h1 class="poppins-bold text-lg bg-sky-100 text-sky-900 px-2 rounded-lg flex items-center gap-1"><i class="fa-solid fa-clock"></i> 8 Hours</h1>
        <p class="poppins-semibold text-sky-800 dark:text-slate-100 text-xs leading-4">Employees Program Duration<br>per Region</p>
    </div>


    <div class="w-full m-0 p-0 " id="eight_chart"></div>


</div>


<script>
var eight_options = {
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

var eight_chart = new ApexCharts(document.querySelector("#eight_chart"), eight_options);
eight_chart.render().then(() => {
    update8hrsBarChartTheme();
});


function showRegionEightHours(){

    eight_chart.updateOptions({
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
        url: '/training-stats/8hrs/bars',
        data: {
            region: $('#region_select').val(),
            plant_status: $('input[name="plant_status[]"]:checked').map(function () {
                return this.value;
            }).get()
        },
        success: function(res) {
            // console.log(res);
            eight_chart.updateOptions({
                xaxis: {
                    categories: res.regions
                },
                series: [
                    {
                        name: 'With Training',
                        data: res.trained
                    },
                    {
                        name: 'No Training',
                        data: res.not_trained
                    }
                ]
            });
        }
    });
}




$(document).ready(function () {
    showRegionEightHours();
});



$('#region_select').on('change', function () {
    showRegionEightHours();
});

$('input[name="plant_status[]"]').on('change', function () {
   
    showRegionEightHours();
});


function update8hrsBarChartTheme() {
    const isDark = document.documentElement.classList.contains('dark');

    eight_chart.updateOptions({
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
