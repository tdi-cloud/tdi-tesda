<x-layout>
<x-slot:title>View Program</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')

    <style>

        @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
        }
        .float { animation: float 4s ease-in-out infinite; }
        .fade-up { animation: fadeUp 0.6s ease-out both; }
        .fade-up-2 { animation: fadeUp 0.6s ease-out 0.15s both; }
        .fade-up-3 { animation: fadeUp 0.6s ease-out 0.3s both; }
        .fade-up-4 { animation: fadeUp 0.6s ease-out 0.45s both; }
    </style>


    <div class="flex gap-4 p-5 border-b border-slate-300 dark:border-slate-600 items-center justify-between bg-white dark:bg-slate-800 ">
        <div class="flex-1">
            <a href="/programs" class="poppins-regular text-blue-500 text-[13px]"><i class="fa-solid fa-arrow-left-long"></i> Back to programs</a>
            <h1 class="text-lg poppins-bold text-slate-900 dark:text-yellow-400">{{ $myprogram->title }}</h1>
            <p class="leading-5 poppins-regular text-slate-500 text-[13px]">{{ $myprogram->description }}</p>
        </div>

        <div class="flex gap-2 ">
            <div class="dropdown dropdown-end poppins-regular hidden">
            <div tabindex="0" role="button" class="btn btn-default shadow-xl rounded-box">Generate <i class="fa-solid fa-angle-down"></i></div>
            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <li  data-program='@json($myprogram)' class="open-to-modal  poppins-medium"><a><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a></li>
            </ul>
            </div>


            <button onclick="createBatchModal()" class="hidden btn btn-info bg-blue-600 text-white rounded-lg shadow-xl poppins-semibold"><i class="fa-solid fa-plus"></i>Add Batch</button>
        </div>

         

    </div>

    <div class="TABS flex px-5 py-2 border-b border-slate-300 bg-slate-200 dark:bg-slate-800 dark:border-slate-600 gap-2">
        <a href="/programs/{{ $myprogram->id }}" class="btn-ghost  hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-circle-info"></i> Details</a>

        <a href="/programs/{{ $myprogram->id }}/participants" class="btn-ghost  hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-user-group"></i> Participants</a>

        <a href="/programs/{{ $myprogram->id }}/submissions" class="bg-white dark:bg-slate-600 btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Submissions</a>      

        <a href="/programs/{{ $myprogram->id }}/requirements" class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-regular fa-file"></i> Requirements</a>  

        <a href="/programs/{{ $myprogram->id }}/certificate" class="hidden btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-award"></i> Certificate</a>

        <a href="/programs/{{ $myprogram->id }}/resource-speakers"
           class="btn-ghost hover:bg-white dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-solid fa-chalkboard-teacher"></i> Resource Speakers
        </a>

        @if($myprogram->tesdaOrders->isNotEmpty())
        <a href="/programs/{{ $myprogram->id }}/tesda-order" class="btn-ghost  hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl  shadow-none"><i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order</a> 
        @endif

    </div>

    <div class=" flex-1  overflow-auto flex flex-col px-5">
        <div class="py-4">
            <h1 class="poppins-bold text-yellow-600">POST Training Submission Responses</h1>
        </div>
        <div class=" space-y-4">

        <!-- Filters -->
        <div class="FILTERS flex gap-2 poppins-regular">   
            <fieldset class="fieldset flex-1">
            <legend class="fieldset-legend p-0">Search Employee</legend>
            <input id="search" class="input w-full border border-slate-300 bg-white dark:bg-slate-700 p-2 rounded-2xl rounded-2xl" placeholder="Search employee">
            </fieldset> 
            

            <fieldset class="fieldset">
            <legend class="fieldset-legend p-0">Status</legend>
            <select id="status" class="select border p-2 rounded-2xl w-40">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="Revision">Revision</option>
            </select>
            </fieldset>

            

            <input type="hidden" id="program_code" class="border p-2 rounded" placeholder="Program" value="{{ $myprogram->program_code }}">
        
            <button id="filterBtn" class="hidden bg-blue-500 text-white px-3 rounded">
                Filter
            </button>
        </div>

        <!-- Results -->
        <div id="submissionList" class="space-y-3">
            <span class="loading loading-dots loading-md"></span>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex gap-2"></div>

    </div>


    </div>



<dialog id="submissionModal" class="modal">
  <div class="modal-box max-w-3xl">

    <h3 class="font-bold text-lg">Review Submission</h3>

    <div id="modalContent" class="mt-4 space-y-2"></div>

    <!-- REVIEW FORM -->
    <div class="mt-4 space-y-3">

        <input type="hidden" id="submission_id">

        <div>
            <label class="text-sm font-semibold">Status</label>
            <select id="modal_status" class="select select-bordered w-full">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Revision">Revision</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Remarks</label>
            <textarea id="modal_remarks"
                class="textarea textarea-bordered w-full"
                placeholder="Enter remarks..."></textarea>
        </div>

    </div>

    <div class="modal-action">
        <button class="btn btn-success text-white" onclick="saveReview()">
            Save Review
        </button>

        <form method="dialog">
            <button class="btn">Close</button>
        </form>
    </div>

  </div>
</dialog>

    <script>
$(document).ready(function () {

    let currentPage = 1;
    let timer;

    loadSubmissions();

    $('#filterBtn').on('click', function () {
        currentPage = 1;
        loadSubmissions(1);
    });

    $('#status').on('change', function () {
        currentPage = 1;
        loadSubmissions(1);
    });

    // ✅ LIVE SEARCH (FIXED)
    $('#search').on('keyup', function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            currentPage = 1;
            loadSubmissions(1);
        }, 400);
    });

    function loadSubmissions(page = 1) {

        $.ajax({
            url: '/get-submissions?page=' + page,
            type: 'GET',
            data: {
                search: $('#search').val(),
                status: $('#status').val(),
                program_code: $('#program_code').val(),
            },
            success: function (res) {

                renderList(res.data);
                renderPagination(res);

                currentPage = res.current_page;
            }
        });
    }

    window.saveReview = function () {

        let id = $('#submission_id').val();

        $.ajax({
            url: '/update-submission/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: $('#modal_status').val(),
                remarks: $('#modal_remarks').val(),
            },
            success: function () {

                loadSubmissions(currentPage); // ✅ now accessible
                document.getElementById('submissionModal').close();
            }
        });
    }

    function renderList(data) {

        if (!data.length) {
            $('#submissionList').html(`
            <div id="app" class="h-full w-full flex items-center justify-center overflow-auto" >

            <div class="text-center  max-w-md mx-auto">
                <!-- Illustration -->
                <div class="float fade-up">
                <div class="relative inline-block">
                <svg width="100" height="100" viewbox="0 0 180 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                
                    <!-- Folder back --> 
                <rect x="30" y="50" width="120" height="80" rx="8" fill="#c7d7e8" stroke="#8ba4bd" stroke-width="2" /> 
                
                <!-- Folder tab --> <path d="M30 58C30 53.5817 33.5817 50 38 50H70L78 38H38C33.5817 38 30 41.5817 30 46V58Z" fill="#c7d7e8" stroke="#8ba4bd" stroke-width="2" /> 
                <!-- Folder front --> <rect x="30" y="60" width="120" height="80" rx="8" fill="#e8eff6" stroke="#8ba4bd" stroke-width="2" /> <!-- Dashed lines representing empty content --> <line x1="55" y1="85" x2="125" y2="85" stroke="#b0c4d8" stroke-width="3" stroke-linecap="round" stroke-dasharray="8 6" /> <line x1="65" y1="100" x2="115" y2="100" stroke="#b0c4d8" stroke-width="3" stroke-linecap="round" stroke-dasharray="8 6" /> <line x1="75" y1="115" x2="105" y2="115" stroke="#b0c4d8" stroke-width="3" stroke-linecap="round" stroke-dasharray="8 6" /> <!-- Sparkle --> <circle cx="148" cy="42" r="4" fill="#f59e42" opacity="0.8" /> <circle cx="24" cy="78" r="3" fill="#6ec1e4" opacity="0.7" /> 
                <circle cx="160" cy="100" r="2.5" fill="#a78bfa" opacity="0.7" />
                </svg>
                </div>
                </div>
                <!-- Text -->
                <h2 id="heading" class="fade-up-2 text-2xl font-bold" style="color: #2d4059;">No data found</h2>
                <!-- Text <p id="description" class="fade-up-3 text-base mb-8" style="color: #6b8299;">Add your first item to get started. It only takes a moment.</p>-->
                
            </div>

            </div> 
            `);
            return;
        }

        let html = data.map(sub => {
            let overdueBadge = '';

            if (sub.submitted_at > sub.requirement.due_date) {
                overdueBadge = `
                    <span class="badge badge-error text-white  badge-xs">
                        OVERDUE
                    </span>
                `;
            }

            let emp = sub.participant.employee;

            return `
            <div class="fade-up hover:scale-[1.01] dark:bg-slate-700 hover:shadow-lg duration-500 border p-4 rounded-2xl border-slate-400 dark:border-slate-600 bg-white shadow flex justify-between">

                <div class="flex gap-4 ">

                    <div>

                    <div class="flex justify-between">
                        <div>
                            <p class="poppins-bold text-sky-900 dark:text-white">
                                ${emp?.LASTNAME}, ${emp?.FIRSTNAME} ${emp?.MI ?? ''}
                            </p>
                            <p class="poppins-regular text-sm text-gray-500 dark:text-slate-200">
                            ${emp?.OFFICE} / ${emp?.SECTION} / ${emp?.UNIT} • ${emp?.EMPCODE} • ${emp?.POSITION}
                            </p>
                        </div>
                    </div>

                    <div class="mt-2 text-sm flex gap-2 items-center">
                        <span class="badge badge-primary badge-soft poppins-medium">
                            <i class="fa-regular fa-file-lines"></i>
                            ${formatRequirementTitle(sub.requirement.title)}
                        </span>
                        <span class="px-2 py-1 rounded-lg text-white poppins-medium text-sm rounded ${statusColor(sub.status)}">
                            ${sub.status}
                        </span>
                    </div>

                        <p class="text-xs mt-2  ">
                            <i class="fa-regular fa-clock"></i> <span class="poppins-medium ">${formatDate(sub.submitted_at)}</span>
                            ${overdueBadge}
                            <span class="text-slate-400">• ${timeAgo(sub.submitted_at)}</span>
                            
                        </p>

                    </div>

                ${ sub.notes ?
                    `<div class="px-4 border-l border-slate-200 max-w-100">
                    <p class="text-sm poppins-medium text-slate-600 dark:text-cyan-300"><i class="fa-regular fa-note-sticky"></i> Note:</p>
                    <p class="italic text-sm poppins-medium text-slate-400 dark:text-slate-200">"${sub.notes}"</p>
                    </div>`
                : ''}

                

                </div>

           
                <div class="flex gap-4">
                    <button
                        onclick="viewSubmission(${sub.id})"
                        class="btn btn-sm btn-info text-white w-30 shadow-none">
                        <i class="fa-regular fa-eye"></i> Review
                    </button>

                    <button  
                        class="delete-submission-btn btn btn-sm btn-error btn-soft btn-circle text-red-600  shadow-none" data-id="${sub.id}">
                        <i  class="fa-regular fa-trash-can"></i>
                    </button>

                    
                </div>

                

              
                    
               
                
            </div>
            `;
        }).join('');

        $('#submissionList').html(html);
    }

    


    function timeAgo(dateString) {
    const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);
    const intervals = [
        { label: 'year',   seconds: 31536000 },
        { label: 'month',  seconds: 2592000 },
        { label: 'week',   seconds: 604800 },
        { label: 'day',    seconds: 86400 },
        { label: 'hour',   seconds: 3600 },
        { label: 'minute', seconds: 60 },
        { label: 'second', seconds: 1 },
    ];

    const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

    for (const interval of intervals) {
        const count = Math.floor(seconds / interval.seconds);
        if (count >= 1) return rtf.format(-count, interval.label);
    }
    return 'just now';
    }

    function renderPagination(res) {

        let html = '';

        if (res.current_page > 1) {
            html += `<button class="px-3 py-1 border"
                onclick="changePage(${res.current_page - 1})">Prev</button>`;
        }

        for (let i = 1; i <= res.last_page; i++) {
            html += `
                <button onclick="changePage(${i})"
                    class="px-3 py-1 border ${i === res.current_page ? 'bg-blue-500 text-white' : ''}">
                    ${i}
                </button>
            `;
        }

        if (res.current_page < res.last_page) {
            html += `<button class="px-3 py-1 border"
                onclick="changePage(${res.current_page + 1})">Next</button>`;
        }

        $('#pagination').html(html);
    }

    window.changePage = function (page) {
        loadSubmissions(page);
    }

    function statusColor(status) {
        if (status === 'Approved') return 'bg-green-500';
        if (status === 'Rejected') return 'bg-red-500';
        if (status === 'Revision') return 'bg-orange-500';
        if (status === 'Pending') return 'bg-yellow-500';
        return 'bg-yellow-500';
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-submission-btn')) {

            const id = e.target.getAttribute('data-id');

            if (!confirm('Are you sure you want to delete this submission?')) {
                return;
            }

            fetch(`/submissions/admin/delete/${id}`, {
                method: 'DELETE',
                headers: {
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadSubmissions(currentPage);
                    // remove row from table (optional)
                    // e.target.closest('tr').remove();
                  
                } else {
                    alert('Failed to delete.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Something went wrong.');
            });
        }
    });

});


window.viewSubmission = function (id) {

    $('#modalContent').html('<span class="loading loading-spinner loading-md"></span>');
    document.getElementById('submissionModal').showModal();

    $.ajax({
        url: '/get-submission/' + id,
        type: 'GET',
        success: function (sub) {

            let emp = sub.participant.employee;

            $('#submission_id').val(sub.id);
            $('#modal_status').val(sub.status);
            $('#modal_remarks').val(sub.remarks ?? '');

            let html = `
                <div class="border-b pb-2">

                    <p class="poppins-bold text-lg">
                        ${emp?.LASTNAME}, ${emp?.FIRSTNAME} ${emp?.MI ?? ''}
                    </p>

                    <p class="text-sm text-gray-500">
                        ${emp?.EMPCODE} • ${emp?.POSITION}
                    </p>

                    <p class="text-sm">
                        ${emp?.OFFICE} / ${emp?.SECTION} / ${emp?.UNIT}
                    </p>

                </div>

                <div class="mt-3 text-sm space-y-1">
                    <span> ${ formatRequirementTitle(sub.requirement.title)}</span>
                    <p><b>Submitted:</b> ${formatDate(sub.submitted_at) ?? '-'}</p>
                    <p><b>Notes:</b> ${sub.notes ?? '-'}</p>
                </div>
            `;

            if (sub.file_path) {
                console.log(sub.file_path);
                html += `
                    <div class="mt-4">
                        <a href="/storage/${sub.file_path}" target="_blank"
                           class="btn btn-sm btn-primary">
                            View File
                        </a>
                    </div>
                `;
            }

            $('#modalContent').html(html);
        }
    });
}

    function formatRequirementTitle(title) {
        if (!title) return '';

        const t = title.toUpperCase();

        if (t === 'TREAP') {
            return `Terminal Report`;
        }

        if (t === 'REAP') {
            return `Terminal and Re-entry Action Plan (T${title})`;
        }

        if (t === 'TDOR') {
            return `Training Development Outcome Report (${title})`
        }

        return title; // default (no change)
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    



    lucide.createIcons();

</script>

</x-monitoring-layout>
</x-layout>