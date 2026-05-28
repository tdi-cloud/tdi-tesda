<x-layout>
<x-slot:title>Submissions</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')

<div class="space-y-4 p-5 overflow-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Requirements Tracker</h1>
            <p class="text-sm text-base-content/60 mt-0.5">List of participants and their requirements submission status</p>
        </div>
        <div class="flex gap-2">
            <button onclick="exportCSV()" class="btn btn-outline btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div id="summary-cards" class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-4">
                <p class="text-xs text-base-content/50 uppercase tracking-wider font-semibold">Total Records</p>
                <p id="summary-total" class="text-3xl font-bold text-base-content mt-1">—</p>
            </div>
        </div>
        <div class="card bg-success/10 border border-success/20 shadow-sm">
            <div class="card-body p-4">
                <p class="text-xs text-success/70 uppercase tracking-wider font-semibold">Submitted</p>
                <p id="summary-submitted" class="text-3xl font-bold text-success mt-1">—</p>
            </div>
        </div>
        <div class="card bg-warning/10 border border-warning/20 shadow-sm">
            <div class="card-body p-4">
                <p class="text-xs text-warning/70 uppercase tracking-wider font-semibold">Not Yet Submitted</p>
                <p id="summary-not-submitted" class="text-3xl font-bold text-warning mt-1">—</p>
            </div>
        </div>
        <div class="card bg-error/10 border border-error/20 shadow-sm">
            <div class="card-body p-4">
                <p class="text-xs text-error/70 uppercase tracking-wider font-semibold">Overdue</p>
                <p id="summary-overdue" class="text-3xl font-bold text-error mt-1">—</p>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-base-content text-sm uppercase tracking-wide">Filters</h2>
                <button onclick="resetFilters()" class="btn btn-ghost btn-xs text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filters
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">

                {{-- Search by name / empcode --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Name / Employee Code</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="filter-search"
                            placeholder="Search employee..."
                            class="input input-bordered input-sm w-full pl-8 text-sm"
                        >
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-base-content/40" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Office filter --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Office</span>
                    </label>
                    <select id="filter-office" class="select select-bordered select-sm w-full text-sm">
                        <option value="">-- All Offices --</option>
                        @foreach($offices as $office)
                            <option value="{{ $office }}">{{ $office }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Requirement title filter --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Requirement</span>
                    </label>
                    <select id="filter-requirement" class="select select-bordered select-sm w-full text-sm">
                        <option value="">-- All Requirements --</option>
                        @foreach($requirementTitles as $req)
                            <option value="{{ $req->id }}">{{ $req->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Submission status filter --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Submission Status</span>
                    </label>
                    <select id="filter-submission-status" class="select select-bordered select-sm w-full text-sm">
                        <option value="">-- All --</option>
                        <option value="submitted">With Submission</option>
                        <option value="not_submitted">No Submission Yet</option>
                    </select>
                </div>

                {{-- Date from --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Date From</span>
                    </label>
                    <input type="date" id="filter-date-from" class="input input-bordered input-sm w-full text-sm">
                </div>

                {{-- Date to --}}
                <div class="form-control w-full">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Date To</span>
                    </label>
                    <input type="date" id="filter-date-to" class="input input-bordered input-sm w-full text-sm">
                </div>

                {{-- Overdue only toggle --}}
                <div class="form-control w-full justify-end">
                    <label class="label py-0.5">
                        <span class="label-text text-xs font-medium text-base-content/70">Overdue Only</span>
                    </label>
                    <label class="label cursor-pointer justify-start gap-3 py-1.5">
                        <input type="checkbox" id="filter-overdue" class="toggle toggle-error toggle-sm">
                        <span class="label-text text-sm text-base-content/70">Show overdue only</span>
                    </label>
                </div>

            </div>
        </div>
    </div>

    {{-- TABLE CONTAINER --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-0">
            <div id="table-container">
                <div class="flex items-center justify-center py-16 text-base-content/40">
                    <span class="loading loading-spinner loading-md mr-2"></span>
                    <span class="text-sm">Loading data...</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- DETAIL MODAL --}}
<dialog id="detail-modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-2xl">
        <div class="flex items-start justify-between mb-4">
            <h3 class="font-bold text-lg" id="modal-title">Submission Details</h3>
            <button class="btn btn-sm btn-circle btn-ghost" onclick="document.getElementById('detail-modal').close()">✕</button>
        </div>
        <div id="modal-body" class="space-y-3 text-sm">
            {{-- filled via JS --}}
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
let allRows      = [];   // full dataset — used for CSV export and showDetail()
let currentPage  = 1;
let perPage      = 25;
let lastPage     = 1;
let debounceTimer = null;

document.addEventListener('DOMContentLoaded', function () {
    fetchData();
    bindFilters();
});

// ── FILTERS ────────────────────────────────────────────────────────────────────

function bindFilters() {
    const resetAndFetch = () => { currentPage = 1; fetchData(); };
    const debounced = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(resetAndFetch, 350);
    };

    document.getElementById('filter-search').addEventListener('input', debounced);
    document.getElementById('filter-office').addEventListener('change', resetAndFetch);
    document.getElementById('filter-requirement').addEventListener('change', resetAndFetch);
    document.getElementById('filter-submission-status').addEventListener('change', resetAndFetch);
    document.getElementById('filter-date-from').addEventListener('change', resetAndFetch);
    document.getElementById('filter-date-to').addEventListener('change', resetAndFetch);
    document.getElementById('filter-overdue').addEventListener('change', resetAndFetch);
}

function getFilters() {
    return {
        search:            document.getElementById('filter-search').value,
        office:            document.getElementById('filter-office').value,
        requirement_id:    document.getElementById('filter-requirement').value,
        submission_status: document.getElementById('filter-submission-status').value,
        date_from:         document.getElementById('filter-date-from').value,
        date_to:           document.getElementById('filter-date-to').value,
        overdue:           document.getElementById('filter-overdue').checked ? '1' : '',
        page:              currentPage,
        per_page:          perPage,
    };
}

function resetFilters() {
    document.getElementById('filter-search').value            = '';
    document.getElementById('filter-office').value            = '';
    document.getElementById('filter-requirement').value       = '';
    document.getElementById('filter-submission-status').value = '';
    document.getElementById('filter-date-from').value         = '';
    document.getElementById('filter-date-to').value           = '';
    document.getElementById('filter-overdue').checked         = false;
    currentPage = 1;
    fetchData();
}

// ── DATA FETCHING ───────────────────────────────────────────────────────────────

function fetchData() {
    const container = document.getElementById('table-container');
    container.innerHTML = `
        <div class="flex items-center justify-center py-16 text-base-content/40">
            <span class="loading loading-spinner loading-md mr-2"></span>
            <span class="text-sm">Loading data...</span>
        </div>
    `;

    const params = new URLSearchParams(getFilters());

    fetch(`{{ route('requirements-tracker.data') }}?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        allRows     = data.all_rows;                  // full set for CSV + showDetail
        lastPage    = data.pagination.last_page;
        currentPage = data.pagination.current_page;
        updateSummary(data.summary);
        renderTable(data.rows, data.pagination);
    })
    .catch(() => {
        container.innerHTML = `
            <div class="flex items-center justify-center py-16 text-error/60">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">Failed to load data. Please try again.</span>
            </div>
        `;
    });
}

// ── SUMMARY ─────────────────────────────────────────────────────────────────────

function updateSummary(summary) {
    document.getElementById('summary-total').textContent         = summary.total.toLocaleString();
    document.getElementById('summary-submitted').textContent     = summary.submitted.toLocaleString();
    document.getElementById('summary-not-submitted').textContent = summary.not_submitted.toLocaleString();
    document.getElementById('summary-overdue').textContent       = summary.overdue.toLocaleString();
}

// ── TABLE RENDERING ──────────────────────────────────────────────────────────────

function renderTable(rows, pagination) {
    const container = document.getElementById('table-container');

    if (rows.length === 0) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-base-content/40 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm font-medium">No records found</p>
                <p class="text-xs">Try adjusting your filters and search again</p>
            </div>
        `;
        return;
    }

    // Global row-number offset for correct numbering across pages
    const offset = (pagination.current_page - 1) * pagination.per_page;

    let html = `
        <div class="overflow-x-auto">
            <table class="table table-sm w-full" id="tracker-table">
                <thead class="bg-base-200/70 sticky top-0 z-10">
                    <tr class="text-xs uppercase tracking-wide text-base-content/60">
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">#</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Employee</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Office</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Program</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Batch</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Requirement</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Due Date</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Status</th>
                        <th class="font-semibold py-3 px-4 whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
    `;

    rows.forEach((row, index) => {
        const globalIndex = offset + index; // index into allRows for showDetail()
        const rowNumber   = offset + index + 1;
        const statusBadge = getStatusBadge(row);
        const overdueBadge = row.is_overdue
            ? `<span class="badge badge-error badge-outline badge-xs ml-1">Overdue</span>`
            : '';

        const dueDateDisplay = row.due_date
            ? `<span class="${row.is_overdue ? 'text-error font-semibold' : 'text-base-content/70'}">${formatDate(row.due_date)}</span>${overdueBadge}`
            : `<span class="text-base-content/30 text-xs">—</span>`;

        html += `
            <tr class="hover:bg-base-50 transition-colors ${row.is_overdue ? 'bg-error/5' : ''}">
                <td class="px-4 py-2.5 text-base-content/40 text-xs font-mono">${rowNumber}</td>
                <td class="px-4 py-2.5">
                    <div class="font-medium text-sm text-base-content">${escHtml(row.fullname)}</div>
                    <div class="text-xs text-base-content/50 font-mono">${escHtml(row.empcode)}</div>
                </td>
                <td class="px-4 py-2.5">
                    <span class="text-xs text-base-content/70 leading-tight block">${escHtml(row.office || '—')}</span>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-xs font-mono text-primary">${escHtml(row.program_code)}</div>
                    <div class="text-xs text-base-content/60 max-w-[180px] truncate" title="${escHtml(row.program_title)}">${escHtml(row.program_title)}</div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-xs text-base-content/70">${escHtml(row.batch)}</div>
                    <div class="text-xs text-base-content/40">${formatDate(row.batch_date_start)} – ${formatDate(row.batch_date_end)}</div>
                </td>
                <td class="px-4 py-2.5">
                    <div class="text-xs font-medium text-base-content">${escHtml(row.requirement_title)}</div>
                    <div class="text-xs text-base-content/40">${row.required === 'yes' ? '⚠ Required' : 'Optional'}</div>
                </td>
                <td class="px-4 py-2.5 whitespace-nowrap">${dueDateDisplay}</td>
                <td class="px-4 py-2.5 whitespace-nowrap">${statusBadge}</td>
                <td class="px-4 py-2.5">
                    <button
                        onclick="showDetail(${globalIndex})"
                        class="btn btn-ghost btn-xs text-primary hover:bg-primary/10"
                        title="View details"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View
                    </button>
                </td>
            </tr>
        `;
    });

    html += `</tbody></table></div>`;

    // ── PAGINATION FOOTER ───────────────────────────────────────────────────────
    html += `
        <div class="px-4 py-3 border-t border-base-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-base-content/50">

            <div class="flex items-center gap-3 flex-wrap">
                <span>
                    Showing
                    <span class="font-semibold text-base-content/70">${pagination.from}</span>–<span class="font-semibold text-base-content/70">${pagination.to}</span>
                    of
                    <span class="font-semibold text-base-content/70">${pagination.total.toLocaleString()}</span>
                    record(s)
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="w-40">Rows per page:</span>
                    <select
                        onchange="changePerPage(this.value)"
                        class="select select-bordered select-xs text-xs"
                        style="min-height:1.5rem;height:1.5rem;padding-top:0;padding-bottom:0;"
                    >
                        ${[10, 25, 50, 100].map(n =>
                            `<option value="${n}" ${n === pagination.per_page ? 'selected' : ''}>${n}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-1 flex-wrap">
                <button
                    onclick="goToPage(1)"
                    ${pagination.current_page === 1 ? 'disabled' : ''}
                    class="btn btn-xs btn-ghost font-mono disabled:opacity-30"
                    title="First page"
                >«</button>
                <button
                    onclick="goToPage(${pagination.current_page - 1})"
                    ${pagination.current_page === 1 ? 'disabled' : ''}
                    class="btn btn-xs btn-ghost disabled:opacity-30"
                    title="Previous page"
                >‹</button>

                ${buildPageButtons(pagination.current_page, pagination.last_page)}

                <button
                    onclick="goToPage(${pagination.current_page + 1})"
                    ${pagination.current_page === pagination.last_page ? 'disabled' : ''}
                    class="btn btn-xs btn-ghost disabled:opacity-30"
                    title="Next page"
                >›</button>
                <button
                    onclick="goToPage(${pagination.last_page})"
                    ${pagination.current_page === pagination.last_page ? 'disabled' : ''}
                    class="btn btn-xs btn-ghost font-mono disabled:opacity-30"
                    title="Last page"
                >»</button>
            </div>

        </div>
    `;

    container.innerHTML = html;
}

// ── PAGINATION HELPERS ──────────────────────────────────────────────────────────

/**
 * Builds numbered page buttons with ellipsis for large page counts.
 * Always shows first, last, and a window of ±2 pages around current.
 */
function buildPageButtons(current, last) {
    if (last <= 1) return '';

    const delta = 2;
    const pages = [];

    for (let i = 1; i <= last; i++) {
        if (
            i === 1 ||
            i === last ||
            (i >= current - delta && i <= current + delta)
        ) {
            pages.push(i);
        }
    }

    let html = '';
    let prev = null;

    for (const page of pages) {
        if (prev !== null && page - prev > 1) {
            html += `<span class="px-1 text-base-content/30 select-none">…</span>`;
        }
        html += `
            <button
                onclick="goToPage(${page})"
                class="btn btn-xs ${page === current ? 'btn-primary pointer-events-none' : 'btn-ghost'}"
                ${page === current ? 'aria-current="page"' : ''}
            >${page}</button>
        `;
        prev = page;
    }

    return html;
}

function goToPage(page) {
    if (page < 1 || page > lastPage) return;
    currentPage = page;
    fetchData();
    // Scroll table back into view smoothly
    document.getElementById('table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function changePerPage(value) {
    perPage     = parseInt(value, 10);
    currentPage = 1;
    fetchData();
}

// ── STATUS BADGE ────────────────────────────────────────────────────────────────

function getStatusBadge(row) {
    if (!row.submitted) {
        if (row.is_overdue) {
            return `<span class="badge badge-error badge-sm gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Overdue
            </span>`;
        }
        return `<span class="badge badge-warning badge-sm gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Not Submitted
        </span>`;
    }

    const statusMap = {
        pending:  { cls: 'badge-info',    label: 'Pending'  },
        approved: { cls: 'badge-success', label: 'Approved' },
        rejected: { cls: 'badge-error',   label: 'Rejected' },
    };
    const s = statusMap[row.submission_status] || { cls: 'badge-success', label: 'Submitted' };

    return `<span class="badge ${s.cls} badge-sm gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        ${s.label}
    </span>`;
}

// ── DETAIL MODAL ────────────────────────────────────────────────────────────────

function showDetail(index) {
    const row = allRows[index]; // allRows = full dataset, not just current page
    if (!row) return;

    document.getElementById('modal-title').textContent = row.fullname;

    const fields = [
        ['Employee Code',     row.empcode],
        ['Office',            row.office],
        ['Position',          row.position],
        ['Program',           `${row.program_code} – ${row.program_title}`],
        ['Batch',             row.batch],
        ['Batch Dates',       `${formatDate(row.batch_date_start)} to ${formatDate(row.batch_date_end)}`],
        ['Requirement',       row.requirement_title],
        ['Required',          row.required === 'yes' ? 'Yes (Required)' : 'No (Optional)'],
        ['Due Date',          row.due_date ? formatDate(row.due_date) : '—'],
        ['Overdue',           row.is_overdue ? '⚠️ Yes' : 'No'],
        ['Submitted',         row.submitted ? 'Yes' : 'No'],
        ['Submission Status', row.submission_status || '—'],
        ['Date Submitted',    row.submitted_at ? formatDate(row.submitted_at) : '—'],
        ['Notes',             row.submission_notes || '—'],
    ];

    document.getElementById('modal-body').innerHTML = fields.map(([label, value]) => `
        <div class="flex gap-3 py-1.5 border-b border-base-200 last:border-0">
            <span class="text-base-content/50 w-36 flex-shrink-0 text-xs pt-0.5">${label}</span>
            <span class="text-base-content font-medium text-xs flex-1">${escHtml(String(value ?? '—'))}</span>
        </div>
    `).join('');

    document.getElementById('detail-modal').showModal();
}

// ── CSV EXPORT ──────────────────────────────────────────────────────────────────

function exportCSV() {
    if (!allRows.length) {
        alert('No data to export.');
        return;
    }

    const headers = [
        '#', 'Empcode', 'Full Name', 'Office', 'Program Code', 'Program Title',
        'Batch', 'Date Start', 'Date End', 'Requirement', 'Required',
        'Due Date', 'Overdue', 'Submitted', 'Status', 'Date Submitted',
    ];
    const csvRows = [headers.join(',')];

    allRows.forEach((row, i) => {
        const cols = [
            i + 1,
            row.empcode,
            `"${(row.fullname  || '').replace(/"/g, '""')}"`,
            `"${(row.office    || '').replace(/"/g, '""')}"`,
            row.program_code,
            `"${(row.program_title    || '').replace(/"/g, '""')}"`,
            `"${(row.batch            || '').replace(/"/g, '""')}"`,
            row.batch_date_start || '',
            row.batch_date_end   || '',
            `"${(row.requirement_title || '').replace(/"/g, '""')}"`,
            row.required,
            row.due_date            || '',
            row.is_overdue          ? 'Yes' : 'No',
            row.submitted           ? 'Yes' : 'No',
            row.submission_status   || '',
            row.submitted_at        || '',
        ];
        csvRows.push(cols.join(','));
    });

    const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `requirements-tracker-${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}

// ── UTILITIES ───────────────────────────────────────────────────────────────────

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    if (isNaN(d)) return dateStr;
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
}

function escHtml(str) {
    if (!str) return '—';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
</script>

</x-monitoring-layout>
</x-layout>