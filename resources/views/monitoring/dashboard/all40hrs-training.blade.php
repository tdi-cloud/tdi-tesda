<div class=" flex-1 p-4 flex justify-between relative ">

    {{-- LEFT  --}}
    <div class="flex flex-col justify-between h-full flex-1"> 

        <div>

        <div class="flex justify-end">
            <h1 class="text-emerald-600 dark:text-green-300 mono leading-6 text-2xl flex gap-1 items-center" id="">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                <span id="WT_forty" class="tooltip tooltip-bottom" data-tip="With Training">0</span>
            </h1>
        </div>

        <div class="flex justify-end">         
            <h1 class="text-cyan-400 mono leading-6 text-2xl flex gap-1 items-center" id="">
                <i data-lucide="circle-x" style="width:16px;height:16px;"></i>
                <span id="NT_forty" class="tooltip tooltip-bottom" data-tip="No Training">0</span>
            </h1>
        </div>

         </div>

         <div class="flex justify-end">         
            <h1 class="text-slate-500 dark:text-slate-100 mono leading-6 text-md flex gap-1 items-center tooltip tooltip-bottom" id=""  data-tip="Total Employees">
                <i data-lucide="users" style="width:14px;height:14px;"></i>
                <span id="Total_F_forty" >0</span>
            </h1>
        </div>

    </div>

    {{-- CENTER  --}}
    <div class="40HRS-CHART-CON w-30  relative">
        <div class="absolute w-40 -left-5 -top-5">
            <div id="FortyHrsChart" class=" w-full"></div>
        </div>
        <div class="absolute top-9 left-1/2 -translate-x-1/2 top-[48%] -translate-y-1/2 text-green-500 mono text-3xl">
            <h1 ><span id="WTF_percent"></span>%</h1>
        </div>
    </div>


    {{-- RIGHT  --}}
    <div class="flex flex-col justify-center h-full flex-1"> 
        <div>
        <p class="poppins-semibold text-sky-800 text-xs dark:text-white">Employees</p>
        <h1 class="poppins-bold text-lg bg-sky-100 text-sky-900 px-2 rounded-lg inline"><i class="fa-solid fa-clock"></i> 40 Hours</h1>
        <p  class="poppins-semibold text-sky-800 text-xs dark:text-white">Program Duration <br> with Supervisory/Managerial Program</p>
        </div>

        
    </div>

    <div class="absolute dark:bg-slate-600 bottom-5 flex items-center border border-slate-300 rounded-2xl justify-between px-2  text-xs poppins-medium">
        SG
        <select id="sg_condition" class="select border-none text-lg w-15 outline-none text-right dark:bg-slate-600">
            <option value="=">=</option>
            <option value=">">&gt;</option>
            <option selected value=">=">&gt;=</option>
            <option value="<">&lt;</option>
            <option value="<=">&lt;=</option>
        </select>
        <input type="number" id="sg_value" value="18" placeholder="e.g. 18" class="input dark:bg-slate-600 w-10 text-xs border-none outline-none">
    </div>

   


</div>

<script>
/* =========================================================
   1. INITIAL CHART (START SAFE - NO FAKE VALUES)
========================================================= */
var FortyHrsOptions = {
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

var FortyHrsChart = new ApexCharts(
    document.querySelector("#FortyHrsChart"),
    FortyHrsOptions
);



/* =========================================================
   2. LOAD DATA FUNCTION
========================================================= */
function loadTrainingStats40hrs() {

    let statuses = $('input[name="plant_status[]"]:checked').map(function () {
        return this.value;
    }).get();

    $.ajax({
        url: '/training-stats/40hrs',
        method: 'GET',
        data: {
            region: $('#region_select').val(),
            plant_status: statuses,
            sg_condition: $('#sg_condition').val(),   
            sg_value: $('#sg_value').val(),   
            office_filter: $('#office_filter').val(),   
        },
        success: function (res) {
            console.log(res)

            // LEFT NUMBERS
            animateNumber('#WT_forty', 0, res.trained, 500);
            animateNumber('#NT_forty', 0, res.not_trained, 500);

            // PERCENT DISPLAY
            let percent = Math.round(res.trained_percentage || 0);
            animateNumber('#WTF_percent', 0, percent, 500);
            animateNumber('#Total_F_forty', 0, res.total, 500);

            // RADIAL CHART UPDATE
            FortyHrsChart.updateSeries([Number(res.trained_percentage)]);
        }
    });
}

/* =========================================================
   3. EVENTS (AUTO REFRESH)
========================================================= */
$('#region_select,#sg_value,#sg_condition, #office_filter, input[name="plant_status[]"]').on('change', function () {
  loadTrainingStats40hrs();
  FortyHrsChart.updateSeries([100]);
});

/* =========================================================
   4. INITIAL LOAD (IMPORTANT FIX)
========================================================= */
document.addEventListener("DOMContentLoaded", function () {
    loadTrainingStats40hrs();
    FortyHrsChart.render();
});


</script>