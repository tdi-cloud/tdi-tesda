<style id="2n4k8p">
.card-pop {
    opacity: 0;
    transform: scale(0.8) translateY(20px);
}

.card-pop.show {
    opacity: 1;
    transform: scale(1) translateY(0);
    transition: 
        transform 0.4s cubic-bezier(.34,1.56,.64,1), 
        opacity 0.3s ease;
}

@keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
        .fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .fade-in-delay { animation: fadeIn 0.6s ease-out 0.15s forwards; opacity: 0; }
        .fade-in-delay-2 { animation: fadeIn 0.6s ease-out 0.3s forwards; opacity: 0; }
</style>
<div>


</div>


<div class=" w-full p-5 overflow-auto h-full">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-start" id="program_grid">

        <span class="loading loading-dots loading-sm"></span>

        {{-- SINGLE PROGRAM --}}


    </div>

</div>

{{-- PAGINATION --}}
<div class="w-full px-5 py-1 flex justify-end">
    <!-- Pagination Controls -->
<div class="flex flex-wrap items-center justify-between gap-3 px-5 pt-2 pb-4">
    

    <!-- Page buttons -->
    <div id="pagination_bar" class="flex items-center gap-1"></div>

    <!-- Total info -->
    <p id="pagination_info" class="text-xs text-slate-400 poppins-regular"></p>
</div>
</div>


<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentStatus = '';
    let currentPerPage = 9;
    let currentInitiated = '';
    let currentMonth = '';

    // ─── Render Cards ────────────────────────────────────────────────
    function renderPrograms(programs) {
        const container = document.getElementById('program_grid');

        if (programs.length === 0) {
            container.innerHTML = `
                <div class="h-full w-full col-span-3 flex items-center justify-center p-6 ">
                    <div class="text-center max-w-sm">
                        <div class="float-anim fade-in mb-6 mx-auto w-28 h-28 rounded-full bg-indigo-50 flex items-center justify-center border-2 border-dashed border-indigo-200">
                            <i data-lucide="inbox" style="width:48px;height:48px;color:#6366f1;"></i>
                        </div>
                        <h3 class="fade-in-delay text-lg poppins-semibold text-slate-800 mb-2">No programs found</h3>
                        <p class="fade-in-delay-2 poppins-regular text-sm text-slate-500 mb-6">Get started by creating your first program.</p>
                    </div>
                </div>`;
            lucide.createIcons();
            return;
        }

        container.innerHTML = programs.map((program) => `
            <div class="card-pop w-full hover:scale-[1.03] shadow-lg relative duration-500 rounded-2xl border border-slate-300 bg-white dark:bg-slate-800 dark:border-slate-600 overflow-hidden">
                <div class="p-4">
                    <div class="flex gap-2">
                        <h1 onclick="window.location.href='/programs/${program.id}'"
                            class="leading-5 poppins-semibold dark:text-yellow-500 flex-1 line-clamp-3 cursor-pointer">${program.title}</h1>
                        <button class="delete-program-btn btn btn-xs btn-error btn-circle btn-soft" data-id="${program.id}">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                    <p class="poppins-regular leading-4 text-slate-400 text-[13px] mt-2">
                        <i class="fa-regular fa-file-lines"></i> ${program.requirements_count} Requirement(s)
                    </p>
                </div>
                <div class="px-4 pb-4 space-y-2 cursor-pointer" onclick="window.location.href='/programs/${program.id}/participants'">
                    ${program.batches.slice(0, 2).map(batch => `
                        <div class="rounded-lg border-slate-300 dark:border-slate-600 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 duration-500">
                            <p class="poppins-semibold mb-1">
                                <i class="fa-solid fa-layer-group text-violet-500"></i> ${batch.batch}
                                ${getStatusBadge(batch.status)}
                            </p>
                            <div class="text-xs flex gap-3">
                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-regular fa-calendar text-cyan-600"></i> ${formatDate(batch.date_start)}
                                </p>
                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-regular fa-clock text-cyan-600"></i> ${batch.hours}hrs
                                </p>
                                <p class="poppins-regular text-slate-500 dark:text-slate-300">
                                    <i class="fa-solid fa-user-group text-cyan-600"></i> ${batch.participants_count} Participants
                                </p>
                            </div>
                        </div>
                    `).join('')}
                    ${program.batches.length > 2 ? `
                        <div class="text-xs text-center text-slate-500 dark:text-slate-400 poppins-medium pt-1">
                            +${program.batches.length - 2} more
                        </div>
                    ` : ''}
                </div>
            </div>
        `).join('');

        // Staggered pop-in animation
        container.querySelectorAll('.card-pop').forEach((card, i) => {
            setTimeout(() => card.classList.add('show'), i * 80);
        });
    }

    // ─── Render Pagination Bar ───────────────────────────────────────
    function renderPagination(meta) {
        const bar  = document.getElementById('pagination_bar');
        const info = document.getElementById('pagination_info');
        const { current_page, last_page, per_page, total } = meta;

        const from = Math.min((current_page - 1) * per_page + 1, total);
        const to   = Math.min(current_page * per_page, total);
        info.textContent = total > 0 ? `Showing ${from}–${to} of ${total} Programs` : '';

        if (last_page <= 1) { bar.innerHTML = ''; return; }

        // Which page numbers to show (window of 5 around current)
        const delta   = 2;
        const pages   = [];
        const rangeStart = Math.max(2, current_page - delta);
        const rangeEnd   = Math.min(last_page - 1, current_page + delta);

        pages.push(1);
        if (rangeStart > 2) pages.push('…');
        for (let p = rangeStart; p <= rangeEnd; p++) pages.push(p);
        if (rangeEnd < last_page - 1) pages.push('…');
        if (last_page > 1) pages.push(last_page);

        const btn = (label, page, disabled = false, active = false) => `
            <button
                class="btn btn-xs rounded-lg poppins-medium
                    ${active  ? 'btn-primary'  : 'btn-ghost'}
                    ${disabled ? 'btn-disabled opacity-40 cursor-not-allowed' : ''}"
                ${disabled ? '' : `onclick="goToPage(${page})"`}>
                ${label}
            </button>`;

        bar.innerHTML =
            btn('<i class="fa-solid fa-angles-left"></i>', 1,           current_page === 1)        +  // First
            btn('<i class="fa-solid fa-angle-left"></i>', current_page - 1, current_page === 1)   +  // Prev
            pages.map(p =>
                p === '…'
                    ? `<span class="px-1 text-slate-400 text-xs self-center">…</span>`
                    : btn(p, p, false, p === current_page)
            ).join('') +
            btn('<i class="fa-solid fa-angle-right"></i>', current_page + 1, current_page === last_page) +  // Next
            btn('<i class="fa-solid fa-angles-right"></i>', last_page,        current_page === last_page);   // Last
    }

    // ─── Go to Page ──────────────────────────────────────────────────
    function goToPage(page) {
        currentPage = page;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    }


    // ─── Fetch Programs ──────────────────────────────────────────────
    async function getPrograms(search = '', status = '', page = 1, perPage = 9, initiated = '', month = '') {
        const container = document.getElementById('program_grid');
        container.innerHTML = `
        <div class="col-span-3 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                ${Array(3).fill(0).map(() => `
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden shadow-sm">
                        <div class="p-4 space-y-3">
                            <div class="flex gap-2 items-start">
                                <div class="skeleton h-4 flex-1 rounded-lg"></div>
                                <div class="skeleton h-6 w-6 rounded-full shrink-0"></div>
                            </div>
                            <div class="skeleton h-3 w-3/4 rounded-lg"></div>
                            <div class="skeleton h-3 w-1/2 rounded-lg"></div>
                        </div>
                        <div class="px-4 pb-4 space-y-2">
                            <div class="skeleton h-12 w-full rounded-xl"></div>
                            <div class="skeleton h-12 w-full rounded-xl"></div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>`;

        const response = await fetch(
            `/get-programs?search=${encodeURIComponent(search)}&status=${status}&page=${page}&per_page=${perPage}&initiated=${initiated}&month=${month}`,
            { headers: { 'Accept': 'application/json' } }
        );

        const result = await response.json();
        renderPrograms(result.data);
        renderPagination(result.meta);
    }

    // ─── Helpers ─────────────────────────────────────────────────────
    function getStatusBadge(status) {
        const map = { active: 'badge-info', completed: 'badge-success', upcoming: 'badge-primary', rescheduled: 'badge-error' };
        return `<span class="badge badge-sm badge-soft ${map[status?.toLowerCase()] || 'badge-ghost'}">${status}</span>`;
    }

    function formatDate(dateStr) {
        if (!dateStr) return 'TBD';
        try { return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }
        catch { return dateStr; }
    }

    // ─── Event Listeners ─────────────────────────────────────────────
    document.getElementById('statusFilter').addEventListener('change', function () {
        currentStatus = this.value;
        currentPage   = 1;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    });

    document.getElementById('monthFilter').addEventListener('change', function () {
        currentMonth = this.value;
        currentPage  = 1;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    });

    document.getElementById('searchInput').addEventListener('keyup', function () {
        currentSearch = this.value;
        currentPage   = 1;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    });

    document.getElementById('perPageSelect').addEventListener('change', function () {
        currentPerPage = parseInt(this.value);
        currentPage    = 1;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    });

    document.getElementById('initiatedFilter').addEventListener('change', function () {
        currentInitiated = this.value;
        currentPage = 1;
        getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated, currentMonth);
    });

    $(document).on('click', '.delete-program-btn', function () {
        let id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this program?')) return;

        $.ajax({
            url: `/programs/${id}/delete`,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                alert(res.message);
                getPrograms(currentSearch, currentStatus, currentPage, currentPerPage, currentInitiated);
            },
            error: function () { alert('Error deleting record.'); }
        });
    });

    // ─── Initial Load ─────────────────────────────────────────────────
    getPrograms('', '', 1, 9, '', '');
</script>
