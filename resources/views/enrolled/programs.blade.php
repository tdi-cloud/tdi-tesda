<x-layout>
    <x-slot:title>
        Enrolled Programs
    </x-slot:title>

    <style>
        @keyframes popupFade {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(10px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .popup-item {
            animation: popupFade 0.35s ease forwards;
        }
    </style>

    @include('enrolled.view-program-modal')


    


    <section class="w-full">

        <div class=" px-10 py-4 border-b border-slate-300 dark:border-slate-600 flex justify-between items-center">
            <h1 class="poppins-bold text-2xl bg-gradient-to-r from-indigo-800 to-indigo-700 bg-clip-text text-transparent" >Enrolled Programs</h1>
            <p class="poppins-medium text-sm"><span id="programCount">0</span> Program(s)</p>
        </div>

        {{-- FILTER AREA --}}
        <div id="filterPanel" class="hidden  px-10 py-4 flex gap-2">
            @include('enrolled.filter-section')
        </div>

        

        {{-- LIST AREA  --}}
        <div id="listPanel" class="hidden w-full ">
            <div id="results" class="grid grid-cols-4 gap-4 px-10 w-full items-start">
                @include('enrolled.loading')
            </div>
        </div>

        <div id="pagePanel" class="hidden px-10 hidden">
            <div id="pagination" class="flex gap-2 mt-3"></div>
        </div>


        <style>
            @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            }
            #icon-wrapper {
            animation: float 3s ease-in-out infinite;
            }
            #icon-circle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
            }
        </style>


        <div id="emptyPanel" class="hidden w-full items-start mt-20 flex  justify-center px-4">

            <div class="bg-white dark:bg-slate-700 rounded-2xl p-8 max-w-md w-full shadow-sm">
                <div class="mb-6 flex justify-center" id="icon-wrapper">
                <div id="icon-circle" class="rounded-full p-4 flex items-center justify-center"><i data-lucide="book-open" style="width:48px;height:48px;color:#fff;"></i>
                </div>
                </div>
                <h1 id="heading" class="text-2xl poppins-bold text-gray-900 dark:text-white mb-2 text-center">No programs yet</h1>
                <p id="description" class="text-gray-600 poppins-regular text-sm dark:text-slate-200 text-center">You haven't enrolled in any training programs</p>
            </div>
        </div>

        



    </section>

    

    

</x-layout>

<script>
    let currentPage = 1;
    let currentQuery = '';
    let perPage = 10;
    let timeout = null;
    const selectedYear = document.getElementById('yearFilter').value;

    document.addEventListener('DOMContentLoaded', function () {
        loadPrograms(1, '', perPage,selectedYear);
    });

    function loadPrograms(page = 1, query = '', perPage = 10, year = '' ) {
    fetch(`/my-programs?q=${query}&page=${page}&per_page=${perPage}&year=${year}`)
        .then(res => res.json())
        .then(data => {

            renderPrograms(data.data.data); // important fix (pagination)
            renderPagination(data.data);
            renderYearFilter(data.years);

        });
}

    

    function renderPrograms(data) {
        let container = document.getElementById('results');
        container.innerHTML = ''; // clear first

        if (!data || data.length === 0) {
            container.innerHTML = `
                <div class="col-span-4 flex flex-col items-center justify-center py-10 text-slate-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                    <p class="poppins-semibold text-lg">No programs found</p>
                    <p class="text-sm">Try adjusting your search or filters.</p>
                </div>
            `;
            return;
        }

        data.forEach((program, index) => {

            let cover = program.cover_pages?.[0]?.image ?? 'default.png';

            let div = document.createElement('div');


            div.className = `
                border border-slate-300 rounded-lg
                dark:border-slate-600 bg-white dark:bg-slate-700
                overflow-hidden mb-3 hover:scale-[1.05] duration-300
                popup-item hover:shadow-lg
            `;

            div.style.animationDelay = (index * 60) + "ms";

            div.innerHTML = `<div class="cursor-pointer">
                    <img src="/storage/${program.cover_pages?.[0]?.image ?? 'default.png'}" class="max-h-40 w-full object-cover" >

                    <div class="p-4">

                    <span >
                        <div class="badge badge-primary badge-soft poppins-bold badge-sm">${program.batches?.[0]?.batch ?? 'No Batch'}</div>
                        <div class="badge badge-sm badge-soft poppins-semibold  ${getStatusBadgeClass(program.batches?.[0]?.status)}">
                            ${program.batches?.[0]?.status ?? 'No status'}
                        </div>
                    </span>
                    <div class="poppins-semibold leading-5 text-sky-900 dark:text-yellow-500 text-sm mt-2">
                        ${program.title}
                    </div>
                    <div class="text-sm text-slate-400 poppins-regular line-clamp-2">
                        ${program.description}
                    </div>

                    <div class="mt-2">
                        <p class="poppins-regular text-xs">
                        <i class="fa-regular fa-calendar"></i>
                        <span>${formatReadable(program.batches?.[0]?.date_start)}</span>    
                    </p>
                    </div>

                    </div>
                    
                </div>
            `;

            div.addEventListener('click', () => openProgramNew(program.batches?.[0]?.participants?.[0]?.id));

            container.appendChild(div);
        });
    }

    function getStatusBadgeClass(status) {
        switch ((status || '').toLowerCase()) {
            case 'completed':
                return 'badge-success text-green-600';   // green
            case 'completed':
            case 'active':
                return 'badge-info';   // blue
            case 'cancelled':
                return 'badge-error';     // red
            case 'upcoming':
                return 'badge-warning';   // yellow
            default:
                return 'badge-neutral';   // gray
        }
    }

    function getAttendanceBadge(attendance) {
        switch ((attendance || '').toLowerCase()) {
            case 'complete':
                return 'badge-success text-green-600';   // green
            case 'absent':
                return 'badge-neutral';   // blue
            case 'pending':
                return 'badge-warning';     // red
            default:
                return 'badge-neutral';   // gray
        }
    }

    function renderPagination(data) {
        let html = `<div class="flex gap-2 items-center">`;

        if (data.prev_page_url) {
            html += `<button onclick="changePage(${data.current_page - 1})">Prev</button>`;
        }

        html += `<span>Page ${data.current_page} of ${data.last_page}</span>`;

        if (data.next_page_url) {
            html += `<button onclick="changePage(${data.current_page + 1})">Next</button>`;
        }

        html += `</div>`;

        document.getElementById('pagination').innerHTML = html;
    }

    function changePage(page) {
        currentPage = page;

        const year = document.getElementById('yearFilter').value;

        loadPrograms(currentPage, currentQuery, perPage, year);
    }

    document.getElementById('searchProg').addEventListener('keyup', function () {
        clearTimeout(timeout);

        currentQuery = this.value;
        currentPage = 1;

        timeout = setTimeout(() => {
            loadPrograms(currentPage, currentQuery, perPage,selectedYear);
        }, 300);
    });


    function renderYearFilter(years) {
        const select = document.getElementById('yearFilter');

        let html = `<option value="">All Years</option>`;

        years.forEach(year => {
            html += `<option value="${year}">${year}</option>`;
        });

        select.innerHTML = html;
    }

    document.getElementById('perPage').addEventListener('change', function () {
        perPage = this.value;
        currentPage = 1;

        loadPrograms(currentPage, currentQuery, perPage,selectedYear);
    });

    document.getElementById('yearFilter').addEventListener('change', function () {
        const selectedYear = this.value;

        loadPrograms(1, '', 10, selectedYear);
    });

    function openProgramNew(id){
        window.location.href = `/enrolled/${id}`;
    }

    function openProgramModal(program){
        document.querySelector('#programCover').src = `/storage/${program.cover_pages?.[0]?.image ?? 'default.png'}`;
        $('#viewProgBarch').text(program.batches?.[0]?.batch ?? 'No Batch');
        console.log(program);
        $('#viewProgStatus').html(`
            <div class="badge  badge-soft poppins-semibold ${getStatusBadgeClass(program.batches?.[0]?.status)}">
                ${program.batches?.[0]?.status ?? 'No status'}
            </div>
        `);
        $('#viewProgCode').text(program.program_code);
        $('#viewProgTitle').text(program.title);
        $('#viewProgDesc').text(program.description);
        $('#viewHours').text(`${program.batches?.[0]?.participants?.[0]?.hours ?? 0}/${program.batches?.[0]?.hours ?? 0}h`);
        let attendance = program.batches?.[0]?.participants?.[0]?.attendance;
        $('#viewAttendanceBadge').html(`
            <span class="badge badge-soft poppins-semibold ${getAttendanceBadge(attendance)}">
                ${attendance}
            </span>
        `);
        $('#viewFromHours').text(formatReadable(program.batches?.[0]?.date_start));
        $('#viewToHours').text(`to ${formatReadable(program.batches?.[0]?.date_end)}`);

        loadRequirements(program.program_code,
        program.batches?.[0]?.participants?.[0]?.id,
        program.batches?.[0]?.id
        );

        view_program_modal.showModal();
    }

    function formatReadable(dateStr) {
        if (!dateStr) return '';

        const months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        const [year, month, day] = dateStr.split('-');

        return `${parseInt(day)} ${months[parseInt(month) - 1]} ${year}`;
    }


    // USER PROGRAM COUNT 
    fetch('/user/program-count')
    .then(response => response.json())
    .then(data => {
        document.getElementById('programCount').innerText = data.program_count;
        if(data.program_count >= 1){
            
            $('#filterPanel').removeClass('hidden');
            $('#listPanel').removeClass('hidden');
            $('#pagePanel').removeClass('hidden');
            $('#emptyPanel').addClass('hidden');
        }else{
            $('#emptyPanel').removeClass('hidden');
        }
    })
    .catch(error => console.error(error));

    lucide.createIcons();

    

</script>