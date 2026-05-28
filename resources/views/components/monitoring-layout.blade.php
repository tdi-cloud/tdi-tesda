

<section class="w-full flex flex-1 overflow-x-hidden">

    {{-- LEFT --}}
    <div class="w-65 bg-white dark:bg-slate-900 pl-10 pr-4 border-r border-slate-300 dark:border-slate-600 ">
        <ul class="space-y-1 py-4">

            <li class="w-full">
                <a href="/dashboard" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular  text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('dashboard') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-solid fa-chart-pie text-md text-indigo-400"></i>
                    <span class="text-[13px]">Dashboard</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/programs" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('programs*') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-solid fa-book-open text-md text-violet-500"></i>
                    
                    <span class="text-[13px]">Programs</span>
                </a>
            </li>
            
            


            <li class="w-full">
                <a href="/calendar" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('calendar') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-regular fa-calendar text-md text-cyan-600"></i>
                    <span class="text-[13px]">Calendar</span>
                    
                </a>
            </li>

    

            <li class="w-full">
                <a href="/employees-progress" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('employees-progress') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-solid fa-users text-md text-blue-600"></i>
                    <span class="text-[13px]">Employees Progress</span>
                    
                </a>
            </li>

            <li class="w-full">
                <a href="/tpmr" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('tpmr') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-solid fa-chart-column"></i>
                    <span class="text-[13px]">TPMR Submissions</span>
                    
                </a>
            </li>

            <li class="w-full">
                <a href="/program-supporting-documents" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('program-supporting-documents') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-regular fa-file-lines text-cyan-600"></i>
                    <span class="text-[13px]">Supporting Docs</span>
                    
                </a>
            </li>

            <li class="w-full">
                <a href="/requirements-tracker" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('requirements-tracker') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}
                ">
                    <i class="fa-regular fa-file-lines text-indigo-600"></i>
                    <span class="text-[13px] leading-4">Requirements Tracker</span>
                    
                </a>
            </li>

        </ul>


    </div>

    <div class="flex-1 flex flex-col overflow-hidden  max-h-[90vh]">
        {{ $slot }}
    </div>

</section>


