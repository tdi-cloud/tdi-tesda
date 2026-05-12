<x-layout>
    <x-slot:title>Programs</x-slot:title>
    <x-monitoring-layout>

        <div class="w-full p-5 flex items-center justify-between">
            <div>
                <h1 class="text-slate-900 dark:text-slate-100 poppins-semibold text-lg">Training Programs</h1>
                <p class="text-slate-600 dark:text-slate-300 poppins-medium text-xs">Manage all training activities and
                    schedules</p>
            </div>


            <div class="poppins-regular">
                <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn rounded-lg">Report <i class="fa-solid fa-angle-down"></i></div>
                <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-100 p-2 shadow-sm">
                    <li onclick="showtpmrgenerate()" class="poppins-bold"><a ><i class="fa-solid fa-file"></i> Training Program Monitoring Report (TPMR)</a></li>
                </ul>
                </div>

                <button id='new_prog_btn' class="btn btn-info bg-blue-600 text-white rounded-lg shadow-none poppins-semibold"><i
                    class="fa-solid fa-plus"></i> Create Program</button>
            </div>

            

        </div>





        <script>
            document.getElementById('new_prog_btn').addEventListener('click', function() {
                blank_modal.showModal();
            })
        </script>

        <dialog id="blank_modal" class="modal">
            <div class="modal-box p-0">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-white">✕</button>
                </form>

                <div class="w-full bg-gradient-to-r from-sky-700 to-sky-800 text-white p-5">
                    <h1 class="poppins-bold text-md">Confirmation</h1>
                </div>
                <div class="p-5 text-center">
                    <p class="text-slate-800 dark:text-slate-100 poppins-regular text-[14px]">Does the program you want
                        to add already have a TESDA Order?</p>
                </div>

                <div class="w-full flex justify-end gap-2 poppins-regular p-5">
                    <button onclick="yesNotice()" class="btn btn-sm w-30 btn-success btn-outline rounded-lg">Yes</button>
                    <button id="openModalBtn"
                        class="btn btn-sm w-30 btn-success text-white rounded-lg shadow-none">No</button>

                </div>
            </div>
        </dialog>


        {{-- FILTERS --}}
        <div class="px-5">

            <label class="input w-full rounded-2xl outline-none bg-white dark:bg-slate-700 poppins-regular text-sm" >
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" id="searchInput" class="grow w-full" placeholder="Search Programs..." />
            </label>

        </div>

        @include('monitoring.myprogram-includes.create-notice-modal')
        @include('monitoring.create-program')
        
        @include('monitoring.program-list')
        @include('components.loading')
        @include('monitoring.dashboard.generate-tpmr-modal')

  




    </x-monitoring-layout>
</x-layout>
