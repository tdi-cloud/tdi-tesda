<div class="border-b border-slate-300 dark:border-slate-500 flex-1 p-4 flex justify-between relative">

    {{-- LEFT --}}
    <div class="flex flex-col justify-between h-full flex-1">

        <div>

        <div class="flex justify-end">
            <h1 class="text-emerald-600 dark:text-green-300 mono leading-6 text-2xl flex gap-1 items-center">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                <span id="WT_eight" class="tooltip tooltip-bottom" data-tip="With Training"></span>
            </h1>
        </div>

        <div class="flex justify-end">
            <h1 class="text-[#03AED2] dark:text-cyan-500 mono leading-6 text-2xl flex gap-1 items-center">
                <i data-lucide="alert-circle" style="width:16px;height:16px;color:"></i>
                <span id="NT_eight" class="tooltip tooltip-bottom" data-tip="No Training"></span>
            </h1>
        </div>

        </div>

        <div class="flex justify-end">         
            <h1 class="text-slate-500 dark:text-slate-100 mono leading-6 text-md flex gap-1 items-center tooltip tooltip-bottom" id=""  data-tip="Total Employees">
                <i data-lucide="users" style="width:14px;height:14px;"></i>
                <span id="Total_eight" >0</span>
            </h1>
        </div>

    </div>

    {{-- CENTER --}}
    <div class="8HRS-CHART-CON w-30 relative ">

        <div class="absolute w-40 -left-5 -top-3">
            <div id="EightHrsChart" class="w-full"></div>
        </div>

        <div class="absolute top-9 left-1/2 -translate-x-1/2 text-green-500 mono text-3xl">
            <h1><span id="WT_percent"></span>%</h1>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="flex flex-col justify-center h-full flex-1 ">

        <div>
            <p class="poppins-semibold text-sky-800 text-xs dark:text-slate-100">Employees</p>
            <h1 class="poppins-bold text-lg  bg-sky-100 text-sky-900 px-2 rounded-lg inline "><i class="fa-solid fa-clock"></i> 8 Hours</h1>
            <p class="poppins-semibold text-sky-800 text-xs dark:text-slate-100">Program Duration</p>
        </div>

    </div>

</div>

<script>
/* =========================================================
   1. INITIAL CHART (START SAFE - NO FAKE VALUES)
========================================================= */
var EightHrsoptions = {
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
                background: 'rgba(3, 174, 210,0.1)',
                strokeWidth: '100%'
            },
            dataLabels: {
                show: false
            }
        }
    },

    colors: ['#2FEB89'],

    stroke: {
        lineCap: 'round'
    }
};

var EightHrsChart = new ApexCharts(
    document.querySelector("#EightHrsChart"),
    EightHrsoptions
);



/* =========================================================
   2. LOAD DATA FUNCTION
========================================================= */
function loadTrainingStats8hrs() {
    

    let statuses = $('input[name="plant_status[]"]:checked').map(function () {
        return this.value;
    }).get();

    $.ajax({
        url: '/training-stats/8hrs',
        method: 'GET',
        data: {
            region: $('#region_select').val(),
            plant_status: statuses,
            office_filter: $('#office_filter').val()
        },
        success: function (res) {
            console.log(res)
            // LEFT NUMBERS
            animateNumber('#WT_eight', 0, res.trained, 500);
            animateNumber('#NT_eight', 0, res.not_trained, 500);

            // PERCENT DISPLAY
            let percent = Math.round(res.trained_percentage || 0);
            animateNumber('#WT_percent', 0, percent, 500);
            animateNumber('#Total_eight', 0, res.total, 500);

            // RADIAL CHART UPDATE
            EightHrsChart.updateSeries([Number(res.trained_percentage)]);

        }
    });
}

/* =========================================================
   3. EVENTS (AUTO REFRESH)
========================================================= */
$('#region_select, input[name="plant_status[]"], #office_filter').on('change', function () {
    loadTrainingStats8hrs();
    EightHrsChart.updateSeries([100]);
});

/* =========================================================
   4. INITIAL LOAD (IMPORTANT FIX)
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    EightHrsChart.render();
    loadTrainingStats8hrs();
});
</script>