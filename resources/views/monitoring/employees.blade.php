<x-layout>
<x-slot:title>Dashboard</x-slot:title>
<x-monitoring-layout>

<section class="p-4 gap-4">

    <div class="">
    <h1 class="poppins-bold text-lg">Employee Progress</h1>    
    <p class="poppins-regular text-sm text-slate-500">Track individual employee training progress and achievements</p>
    </div>    

    @include('monitoring.employee-list')
</section>


    


    

</x-monitoring-layout>
</x-layout>