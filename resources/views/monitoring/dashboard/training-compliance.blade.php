

<div class="flex justify-between p-4 relative">
    <div class="absolute poppins-medium uppercase text-white text-sm"><i class="fa-solid fa-graduation-cap"></i> EMPLOYEES Training Compliance Rate
    <br>
    <span id="wait_trainings" class=" text-[10px] poppins-regular">Please wait...</span>
    </div>

                <div class=" flex flex-col   justify-end flex-1 ">
                    

                    <div>

                        <div>
                        
                        <h1 class="text-emerald-200 mono text-3xl " id="with_training">
                            <span class="trainings_loading loading loading-ring loading-md"></span>
                        </h1>
                        <h1 class="text-emerald-100 poppins-bold text-sm flex gap-1 items-center" >
                            <i data-lucide="check-circle" style="width:16px;height:16px;color:#04ea9d;"></i> With Training</h1>
                    </div>

                    <div>
                        
                        <h1 class="text-rose-300 mono text-3xl" id="no_training" >
                            <span class="trainings_loading loading loading-ring loading-md"></span>
                        </h1>
                        <h1 class="text-rose-300 poppins-bold text-sm flex gap-1 items-center" ><i data-lucide="alert-circle" style="width:16px;height:16px;color:#f47187;"></i> No Training</h1>
                    </div>

                    <div class="mt-8">
                        <h1 class="text-slate-100  text-3xl mono" id="total_employees">
                            <span class="trainings_loading loading loading-ring loading-md"></span>
                        </h1>
                        <h1 class="text-slate-100 poppins-bold text-sm flex gap-1 items-center" ><i data-lucide="users" style="width:16px;height:16px;color:#e1e1e1;"></i> Employees</h1>
                    </div>

                    </div>
               
                    
                         
                </div>

                <style>
                #chart svg:focus,
                #chart *:focus {
                    outline: none !important;
                }
                </style>

               
                <div class="TRIANING-CHART-CONTAINER relative">
                    <div class=" top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 absolute flex flex-col items-center">
                        <span class="mono text-3xl text-white" >
                            <span id="with_training_percents"></span>%
                        </span>
                        <span class="poppins-regular text-white/80 text-[12px]">With Training</span> 
                    </div>

                    <div class="absolute left-1/2 bottom-0 text-[12px] poppins-semibold -translate-x-1/2 w-full flex justify-center gap-2">
                        <span class="text-emerald-300"><i class="fa-solid fa-circle "></i> With Training</span>
                        <span class="text-white/50"><i class="fa-solid fa-circle"></i> No Training</span>
                    </div>

                    <div id="chart" class=" w-full"></div>

                </div>
                

                    <script>


                        var options = {
                    chart: {
                        type: 'radialBar',
                        offsetY: 0,
                        offsetX: 0,
                        sparkline: {
                            enabled: true
                        }
                    },

                    series: [0],

                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    },   // ← comma was missing here!

                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 0,
                            endAngle: 360,
                            hollow: {
                                margin: 0,
                                size: '60%'
                            },
                            track: {
                                background: 'rgba(255, 255, 255, 0.1)',
                                strokeWidth: '100%',
                                margin: 0
                            },
                            dataLabels: {
                                name: {
                                    show: false,
                                    offsetY: 20,
                                    color: '#9ca3af',
                                    fontSize: '14px',
                                    formatter: function () {
                                        return "Trained";
                                    }
                                },
                                value: {
                                    show: false,
                                    fontSize: '36px',
                                    fontWeight: 'bold',
                                    color: '#ffffff',
                                    formatter: function (val) {
                                        return val + "%";
                                    }
                                }
                            }
                        }
                    },

                    colors: ['#2FEB89'],

                    stroke: {
                        lineCap: 'round',
                        width: 0
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();

                lucide.createIcons();
                    </script>


                <div class=" flex-1 flex flex-col justify-between">
                    
                    <select id="region_select"  class="bg-sky-900/50 poppins-semibold text-slate-200 text-xs rounded-lg px-3 py-1.5 border border-slate-700 focus:outline-none focus:border-emerald-500 cursor-pointer"> 
                        <option value="ALL">All Regions</option> 
                        <option value="CO">Central Office</option> 
                        <option value="NCR">NCR</option> 
                        <option value="R1">Region I</option> 
                        <option value="R2">Region II</option> 
                        <option value="R3">Region III</option> 
                        <option value="R4A">Region IV-A</option> 
                        <option value="R4B">Region IV-B</option> 
                        <option value="R5">Region V</option> 
                        <option value="NIR">NIR</option> 
                        <option value="R6">Region VI</option> 
                        <option value="R7">Region VII</option> 
                        <option value="R8">Region VIII</option> 
                        <option value="R9">Region IX</option> 
                        <option value="R10">Region X</option> 
                        <option value="R11">Region XI</option> 
                        <option value="R12">Region XII</option> 
                    </select>


                    <fieldset class="fieldset ">
                    <legend class="fieldset-legend text-slate-400 text-xs poppins-regular">PLANTILLA:</legend>

                    <label class="label text-white poppins-medium flex justify-between" >
                        PERMANENT
                        <input type="checkbox" value="PERMANENT"  checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                    </label>
                    <label class="label text-white poppins-medium flex justify-between" >
                        JOB ORDER
                        <input type="checkbox" value="JOB ORDER" checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                    </label>
                    <label class="label text-white poppins-medium flex justify-between" >
                        CTI
                        <input type="checkbox" value="CTI" checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                    </label>
                    </fieldset>
                </div>

            
            </div>



<script>
    $.ajax({
        url: '/programs-count',
        method: 'GET',
        success: function(response) {
            // $('#prog_count').text(response);
        }
    });

getTrainings();
function getTrainings() {
    $('#wait_trainings').fadeIn();
    let region = $('#region_select').val();

    let types = [];
    $('.type_checkbox:checked').each(function () {
        types.push($(this).val());
    });

    $.ajax({
        url: '/employee-trainings',
        type: 'GET',
        data: {
            region: region,
            types: types
        },
        success: function (res) {
            $('#wait_trainings').fadeOut();
            let with_training_percent = Math.round(((res.with_training + 0) / (res.total + 0)) * 100);

            $('.trainings_loading').addClass('hidden');
            animateNumber('#with_training', 0, res.with_training, 500);
            animateNumber('#no_training', 0, res.no_training, 500);
            animateNumber('#total_employees', 0, res.total, 500);
            animateNumber('#with_training_percents', 0, with_training_percent, 500);
            chart.updateSeries([with_training_percent]);

            
        }
    });
}


$('#region_select, .type_checkbox').on('change', function () {
    getTrainings();
});


function animateNumber(selector, start, end, duration) {
    const el = document.querySelector(selector);
    let startTime = null;

    function animation(currentTime) {
        if (!startTime) startTime = currentTime;
        const progress = Math.min((currentTime - startTime) / duration, 1);

        const value = Math.floor(progress * (end - start) + start);
        el.textContent = value;

        if (progress < 1) {
            requestAnimationFrame(animation);
        }
    }

    requestAnimationFrame(animation);
}
    
</script>