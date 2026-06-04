<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ isset($title) ? $title . ' | TDI' : 'TDI' }}
    </title>

    {{-- JQUERY  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- POPPINS FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{-- SPACE MONO FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&amp;family=Space+Mono:wght@700&amp;display=swap" rel="stylesheet">

    {{-- FONTAWESOME  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- WEB ICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/favicon_io/android-chrome-512x512.png') }}">

    {{-- TAILWIND --}}
    <script>
        const saved = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
        if (saved === 'dark') document.documentElement.classList.add('dark');
    </script>

    @vite('resources/css/app.css')

    {{-- DAISY UI  --}}
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

    {{-- LUCID ICONS  --}}
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>

    {{-- FULL CALENDAR --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>

    {{-- APEX CHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body class="min-h-screen flex flex-col bg-slate-100 dark:bg-slate-800/50">

    @auth

        <nav class="navbar bg-white dark:bg-slate-900/50 px-4 lg:px-10 border-b border-slate-300 dark:border-slate-600 sticky top-0 z-20">

            <div class="navbar-start gap-2">
                {{-- Hamburger button - mobile only --}}
                <button id="sidebar-toggle" class="lg:hidden text-slate-700 dark:text-slate-200 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <a href="/"><img class="w-10 rounded-lg" src="{{ asset('images/TDI.png') }}" alt=""></a>
                <h1 class="poppins-bold text-sky-700 dark:text-white text-sm hidden sm:block">TESDA Development Institute</h1>
            </div>

            <div class="navbar-center gap-4">
                <a href="/enrolled" class="cursor-pointer text-slate-900 dark:text-white poppins-regular text-[12px] cursor-default">
                    <i class="fa-solid fa-book-open"></i> Enrolled
                </a>

                @auth
                    @if(auth()->user()->access === 'admin')
                        <a href="/dashboard" class="cursor-pointer text-slate-900 dark:text-white poppins-regular text-[12px] cursor-default">
                            <i class="fa-solid fa-chart-column"></i> Monitoring
                        </a>
                    @endif
                @endauth
            </div>

            <div class="navbar-end gap-2">
                <button onclick="toggleTheme()" id="theme-icon" class="mr-4 border-none outline-none text-cyan-500">
                    <i class="fa-regular fa-moon"></i>
                </button>

                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="cursor-default poppins-regular text-sm">
                        {{ ucwords(strtolower(auth()->user()->employee->FIRSTNAME ?? 'UNKNOWN')) }}
                        <i class="fa-solid fa-angle-down text-xs"></i>
                    </div>
                    <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                        <li>
                            <a href="/profile">
                                <button class="poppins-regular text-[13px]">
                                    <i class="fa-regular fa-user"></i>
                                    Profile
                                </button>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full poppins-regular text-[12px]">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    @endauth


    @if (session('success'))
        <div class="toast">
            <div class="alert alert-success">
                <span class="text-[12px] poppins-regular">
                    <i class="fa-solid fa-hands-clapping"></i>
                    {{ session('success') }}
                </span>
            </div>
        </div>
    @endif

    <div id="toast-container" class="toast toast-top toast-end"></div>

    <main class="flex-1 flex">
        {{ $slot }}
    </main>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', next);
            html.classList.toggle('dark', next === 'dark');
            localStorage.setItem('theme', next);

            const icon = document.getElementById('theme-icon');
            if (next === 'dark') {
                icon.innerHTML = '<i class="fa-regular fa-moon"></i>';
            } else {
                icon.innerHTML = '<i class="fa-regular fa-sun"></i>';
            }

            update8hrsBarChartTheme();
            update40hrsBarChartTheme();
        }

        window.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('theme') || 'light';
            const icon = document.getElementById('theme-icon');
            if (saved === 'dark') {
                icon.innerHTML = '<i class="fa-regular fa-moon"></i>';
            } else {
                icon.innerHTML = '<i class="fa-regular fa-sun"></i>';
            }
        });

        function createBatchModal() {
            $('#createBatchTitle').html('Add New Batch');
            create_batch_modal.showModal();
            $('#batch_submit_btn').removeClass('hidden');
            $('#batch_edit_btn').addClass('hidden');
            $('#batchForm').trigger('reset');
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');

            const alertType = {
                success: 'alert-success',
                error: 'alert-error',
                warning: 'alert-warning',
                info: 'alert-info'
            };

            const alertIcon = {
                success: '<i class="fa-solid fa-check"></i>',
                error: '<i class="fa-regular fa-circle-xmark"></i>',
                warning: '<i class="fa-solid fa-triangle-exclamation"></i>',
                info: '<i class="fa-solid fa-circle-info"></i>'
            };

            const toast = document.createElement('div');
            toast.className = `alert ${alertType[type]} shadow-lg opacity-0 translate-y-[-10px] transition-all duration-300`;
            toast.innerHTML = `<span class="poppins-semibold text-sm">${alertIcon[type]} ${message}</span>`;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-[-10px]');
            }, 100);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-[-10px]');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>

    @stack('scripts')

</body>

</html>