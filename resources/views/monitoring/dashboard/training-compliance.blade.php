

<div class="flex justify-center pb-2 items-center   relative border border-slate-300 dark:shadow-2xl shadow-lg bg-white dark:bg-slate-700 dark:border-slate-500 rounded-2xl">
    
    
                <div class="LEFTCOUNTS ">
                    

                    <div>
                        <div>
                        
                            <h1 class="text-emerald-600 dark:text-green-300 mono text-3xl text-center" id="with_training">
                                <span class="trainings_loading loading loading-ring loading-md"></span>
                            </h1>
                            <h1 class="text-emerald-500 dark:text-green-300 poppins-bold text-sm flex gap-1 items-center" >
                                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                                 With Training</h1>
                        </div>
                        <br>
                        <div>
                            <h1 class="text-[#03AED2] dark:text-cyan-500 mono text-3xl text-center" id="no_training" >
                                <span class="trainings_loading loading loading-ring loading-md"></span>
                            </h1>
                            <h1 class="text-[#03AED2] dark:text-cyan-500 poppins-bold text-sm flex gap-1 items-center" >
                                <i data-lucide="alert-circle" style="width:16px;height:16px;color:#0070d8;"></i> 
                                No Training
                            </h1>
                        </div>

                    </div>
               
                    
                         
                </div>

                <style>
                #chart svg:focus,
                #chart *:focus {
                    outline: none !important;
                }
                </style>

               
                <div class="TRIANING-CHART-CONTAINER relative ">
                    <div class=" top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 absolute flex flex-col items-center ">
                        <span class="mono text-3xl text-green-500" >
                            <span id="with_training_percents"></span>%
                        </span>
                        <span class="poppins-regular text-slate-500 dark:text-emerald-100 text-[12px]">With Training</span> 
                    </div>

                    <div class="absolute left-1/2 bottom-0 text-[12px] poppins-semibold -translate-x-1/2 w-full flex justify-center gap-2">
                        <span class="text-emerald-300"><i class="fa-solid fa-circle "></i> With Training</span>
                        <span class="text-[#03AED2]"><i class="fa-solid fa-circle"></i> No Training</span>
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

                    series: [100],

                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    },   

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
                                // background: 'rgba(37, 52, 63, 0.1)',
                                background: 'rgba(3, 174, 210,0.2)',
                                strokeWidth: '100%',
                                margin: 0
                            },
                            dataLabels: {
                                name: {
                                    show: false,
                                    offsetY: 20,
                                    color: '#25343F',
                                    // color: '#9ca3af',
                                    fontSize: '14px',
                                    formatter: function () {
                                        return "Trained";
                                    }
                                },
                                value: {
                                    show: false,
                                    fontSize: '36px',
                                    fontWeight: 'bold',
                                    color: '#25343F',
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

                    <div class="">
                        <h1 class="text-slate-600 dark:text-white  text-3xl mono text-center" id="total_employees">
                            <span class="trainings_loading loading loading-ring loading-md"></span>
                        </h1>
                        <h1 class="text-slate-800 dark:!text-slate-100 poppins-bold text-sm flex gap-1 items-center" >
                            <i data-lucide="users" style="width:16px;height:16px;"></i> 
                            Employees</h1>
                    </div>


            
</div>



