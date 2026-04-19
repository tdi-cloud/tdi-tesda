<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>

<section class="p-5 space-y-5">
    

    <div class="grid grind-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 ">


        {{-- PROGRAMS COUNT --}}
        <div class="TRAININGS w-full bg-gradient-to-r from-sky-800 to-sky-900 dark:border-slate-600 border border-slate-300 rounded-2xl ">
            @include('monitoring.dashboard.training-compliance')
        </div>

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl pt-4">
             @include('monitoring.dashboard.batch-trendChart')
        </div>

       

    </div>

    {{-- 2ND SECTION  --}}

    <div class="grid grid-cols-2 gap-4 items-start hidden">

        <div class="w-full bg-white dark:bg-slate-700 dark:border-slate-600 border border-slate-300 rounded-2xl p-5">
            @include('monitoring.dashboard.training-trend')


            

        </div>


        <div class="w-full bg-white dark:bg-slate-600 dark:border-slate-700 border border-slate-300 rounded-2xl p-5">
           
        </div>

    </div>

</section>    

</x-monitoring-layout>
</x-layout>