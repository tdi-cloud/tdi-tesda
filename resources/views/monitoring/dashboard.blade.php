<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>

<section class="p-5 space-y-4 overflow-auto">

    <div>
        <h1 class="text-4xl poppins-bold bg-gradient-to-r to-blue-500 from-purple-700  bg-clip-text text-transparent">DASHBOARD</h1>
    </div>


    <section class=" pb-5 bg-white dark:bg-slate-700 to-sky-800 dark:border-slate-600 border border-slate-300 rounded-2xl">
        @include('monitoring.dashboard.top-filter')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4  px-5">

            <div class="TRAININGS w-full ">
                @include('monitoring.dashboard.training-compliance')
            </div>

            <div class="TRAININGS w-full h-full bg-white dark:bg-slate-700 dark:border-slate-500 border border-slate-300 shadow-lg rounded-2xl flex flex-col">
                @include('monitoring.dashboard.all8hrs-training')
                @include('monitoring.dashboard.all40hrs-training')
            </div>

            <div class="TRAININGS border dark:bg-slate-700 h-auto dark:border-slate-500 border-slate-300 rounded-2xl shadow-2xl bg-white">
                @include('monitoring.dashboard.8hrs-regions')
            </div>

            <div class="TRAININGS border dark:bg-slate-700 dark:border-slate-500 h-auto border-slate-300 rounded-2xl shadow-2xl bg-white">
                @include('monitoring.dashboard.40hrs-regions')
            </div>
        </div>

    </section>
    

    

        {{-- 2ND SECTION  --}}
    <div class="grid grind-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 ">

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl pt-4">
             @include('monitoring.dashboard.batch-trendChart')
        </div>

    </div>


    




    

    <div class="grid grid-cols-2 gap-4 items-start hidden">

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl p-5">
            @include('monitoring.dashboard.training-trend')

        </div>


        <div class="w-full bg-white dark:bg-slate-600 dark:border-slate-700 border border-slate-300 rounded-2xl p-5">
           
        </div>

    </div>

</section>    

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
    let office_filter = $('#office_filter').val();

    let types = [];

    $('.type_checkbox:checked').each(function () {
        types.push($(this).val());
    });

    $.ajax({
        url: '/employee-trainings',
        type: 'GET',
        data: {
            region: region,
            types: types,
            office_filter: office_filter
        },
        success: function (res) {

            // console.log(res);

            $('#wait_trainings').fadeOut();

            let with_training_percent =
                Math.round(((res.with_training + 0) / (res.total + 0)) * 100);

            $('.trainings_loading').addClass('hidden');

            animateNumber('#with_training', 0, res.with_training, 500);
            animateNumber('#no_training', 0, res.no_training, 500);
            animateNumber('#total_employees', 0, res.total, 500);
            animateNumber('#with_training_percents', 0, with_training_percent, 500);

            chart.updateSeries([with_training_percent]);
        }
    });
}


$('#region_select, .type_checkbox ,#office_filter').on('change', function () {
    getTrainings();
    chart.updateSeries([100]);
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


    lucide.createIcons();
    
</script>

</x-monitoring-layout>
</x-layout>