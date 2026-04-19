<x-layout>
<x-slot:title>View Program</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')
   

    <div class="flex gap-4 p-5 border-b border-slate-300 dark:border-slate-600 items-center justify-between bg-white dark:bg-slate-800 ">
        <div class="flex-1">
            <a href="/programs" class="poppins-regular text-blue-500 text-[13px]"><i class="fa-solid fa-arrow-left-long"></i> Back to programs</a>
            <h1 class="text-lg poppins-bold text-slate-900 dark:text-yellow-400">{{ $myprogram->title }}</h1>
            <p class="leading-5 poppins-regular text-slate-500 text-[13px]">{{ $myprogram->description }}</p>
        </div>

        <div class="flex gap-2 ">
            <button class="btn btn-default shadow-xl rounded-box"><i class="fa-solid fa-pen"></i>Edit</button>

            <div class="dropdown dropdown-end poppins-regular">
            <div tabindex="0" role="button" class="btn btn-default shadow-xl rounded-box">Generate <i class="fa-solid fa-angle-down"></i></div>
            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <li  data-program='@json($myprogram)' class="open-to-modal  poppins-medium"><a><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a></li>
            </ul>
            </div>


            <button onclick="createBatchModal()" class="hidden btn btn-info bg-blue-600 text-white rounded-lg shadow-xl poppins-semibold"><i class="fa-solid fa-plus"></i>Add Batch</button>
        </div>

         

    </div>

    <div class="TABS flex px-5 py-2 border-b border-slate-300 bg-slate-200 dark:bg-slate-800 dark:border-slate-600 gap-2">
        <a href="/programs/{{ $myprogram->id }}" class="btn-default bg-white dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-circle-info"></i> Details</a>

        <a href="/programs/{{ $myprogram->id }}/participants" class="btn-ghost  hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-user-group"></i> Participants</a>

        <a href="/programs/{{ $myprogram->id }}/submissions" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Submissions</a>      

        <a href="/programs/{{ $myprogram->id }}/requirements" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Requirements</a>  

        @if($myprogram->tesdaOrders->isNotEmpty())
        <a href="/programs/{{ $myprogram->id }}/tesda-order" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a> 
        @endif

    </div>

    <div class=" flex-1  overflow-auto ">


    @include('monitoring.program-info')
    @include('monitoring.myprogram-includes.upload-cover')
    @include('monitoring.myprogram-includes.generate-to-modal')

    
    </div>

    
</x-monitoring-layout>
</x-layout>