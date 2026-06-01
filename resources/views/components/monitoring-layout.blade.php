{{-- Backdrop overlay --}}
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden"></div>

<section class="w-full flex flex-1 overflow-x-hidden">

    {{-- LEFT SIDEBAR --}}
    <div
        id="sidebar"
        class="fixed top-0 left-0 h-full z-40 w-65
               bg-white dark:bg-slate-900
               pl-10 pr-4
               border-r border-slate-300 dark:border-slate-600
               -translate-x-full transition-transform duration-300 ease-in-out
               lg:static lg:translate-x-0 lg:z-auto lg:h-auto lg:transition-none"
    >
        {{-- Mobile header inside sidebar --}}
        <div class="flex items-center justify-between pt-4 pr-2 pb-2 border-b border-slate-200 dark:border-slate-700 lg:hidden">
            <span class="poppins-bold text-sm text-sky-700 dark:text-white">Navigation</span>
            <button id="sidebar-close" aria-label="Close menu" class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <i class="fa-solid fa-xmark text-slate-500 dark:text-slate-400 text-lg"></i>
            </button>
        </div>

        <ul class="space-y-1 py-4">

            <li class="w-full">
                <a href="/dashboard" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('dashboard') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-solid fa-chart-pie text-md text-indigo-400"></i>
                    <span class="text-[13px]">Dashboard</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/programs" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('programs*') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-solid fa-book-open text-md text-violet-500"></i>
                    <span class="text-[13px]">Programs</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/calendar" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('calendar') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-regular fa-calendar text-md text-cyan-600"></i>
                    <span class="text-[13px]">Calendar</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/employees-progress" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('employees-progress') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-solid fa-users text-md text-blue-600"></i>
                    <span class="text-[13px]">Employees Progress</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/tpmr" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('tpmr') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span class="text-[13px]">TPMR Submissions</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/program-supporting-documents" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('program-supporting-documents') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-regular fa-file-lines text-cyan-600"></i>
                    <span class="text-[13px]">Supporting Docs</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/requirements-tracker" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('requirements-tracker') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-regular fa-file-lines text-indigo-600"></i>
                    <span class="text-[13px] leading-4">Post Training Requirements Tracker</span>
                </a>
            </li>

            <li class="w-full">
                <a href="/foreign-programs" class="flex gap-2 items-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg p-2 poppins-regular text-sm text-slate-700 dark:text-slate-200 block duration-500
                {{ request()->is('foreign-programs') ? 'bg-slate-200 dark:bg-slate-700 poppins-bold' : 'hover:bg-slate-200' }}">
                    <i class="fa-solid fa-chalkboard-user text-emerald-600"></i>
                    <span class="text-[13px] leading-4">FSTP Nomination</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden max-h-[90vh]">
        {{ $slot }}
    </div>

</section>

@push('scripts')
<script>
    const sidebar    = document.getElementById('sidebar');
    const backdrop   = document.getElementById('sidebar-backdrop');
    const toggleBtn  = document.getElementById('sidebar-toggle');
    const closeBtn   = document.getElementById('sidebar-close');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
    if (backdrop)  backdrop.addEventListener('click', closeSidebar);

    // Close sidebar on lg+ resize (in case user resizes window)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
</script>
@endpush