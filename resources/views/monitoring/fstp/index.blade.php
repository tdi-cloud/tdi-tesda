{{-- resources/views/monitoring/fstp/index.blade.php --}}
<x-layout>
<x-slot:title>FSTP Nomination</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')

<div class="px-4 sm:px-6 lg:px-8 py-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Foreign Programs</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage nominations for foreign training programs</p>
        </div>
        <button onclick="openAddModal()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Program
        </button>
    </div>

    {{-- Toast Notification --}}
    <div id="toast"
         class="hidden fixed top-5 right-5 z-[999] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all duration-300">
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col gap-2 mb-5">

        {{-- Row 1: Search + Status + Semester + Year + Clear --}}
        <div class="flex flex-wrap gap-2">
            <div class="relative flex-1 min-w-[180px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search title, sponsor, agency…"
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400"/>
            </div>
            <select id="statusFilter"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                <option value="">All Statuses</option>
                @foreach($statusOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <select id="semesterFilter"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                <option value="">All Semesters</option>
                <option value="1">1st Semester (Jan – Jun)</option>
                <option value="2">2nd Semester (Jul – Dec)</option>
            </select>
            <select id="yearFilter"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                <option value="">All Years</option>
                @php $currentYear = now()->year; @endphp
                @for($y = $currentYear + 1; $y >= $currentYear - 5; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            <button id="clearBtn" onclick="clearFilters()"
                    class="hidden px-4 py-2 bg-white hover:bg-gray-50 text-gray-500 text-sm rounded-lg border border-gray-300 transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                Clear
            </button>
        </div>

        {{-- Row 2: Organizing Sponsor + Embassy Deadline range + Interview Date range --}}
        <div class="flex flex-wrap gap-2 items-center">
            {{-- Organizing Sponsor --}}
            <div class="relative min-w-[200px] flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <input type="text" id="sponsorFilter" placeholder="Filter by organizing sponsor…"
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400"/>
            </div>

            {{-- Embassy Deadline range --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">
                    Embassy Deadline
                </span>
                <input type="date" id="deadlineFrom"
                       class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-400 focus:border-rose-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                       title="Deadline from"/>
                <span class="text-gray-400 text-xs">–</span>
                <input type="date" id="deadlineTo"
                       class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-400 focus:border-rose-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                       title="Deadline to"/>
            </div>

            {{-- Interview Date range --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide whitespace-nowrap">
                    Interview Date
                </span>
                <input type="date" id="interviewFrom"
                       class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-violet-400 focus:border-violet-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                       title="Interview date from"/>
                <span class="text-gray-400 text-xs">–</span>
                <input type="date" id="interviewTo"
                       class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-violet-400 focus:border-violet-400 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                       title="Interview date to"/>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3 text-left font-semibold">Program Title</th>
                        <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Program Period</th>
                        <th class="px-4 py-3 text-center font-semibold">Slots</th>
                        <th class="px-4 py-3 text-left font-semibold">Modality</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Participants</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="programTableBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                            Loading…
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div id="paginationContainer" class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 hidden">
        </div>
    </div>
</div>

{{-- ===================== VIEW DETAILS MODAL ===================== --}}
<div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeViewModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Program Details</h2>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- Body --}}
            <div id="viewModalBody" class="px-6 py-5 space-y-4 text-sm text-gray-700 dark:text-gray-300">
                {{-- Populated by JS --}}
            </div>
            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <a id="viewParticipantsLink" href="#"
                   class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-700 font-medium dark:text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    View Participants
                </a>
                <button onclick="closeViewModal()"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== ADD MODAL ===================== --}}
<div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-3xl p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Add Foreign Program</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="addForm">
                @csrf
                @include('monitoring.fstp._form', ['program' => null])
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" id="addSubmitBtn"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                        Save Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== EDIT MODAL ===================== --}}
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-3xl p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Edit Foreign Program</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editForm">
                @csrf
                <input type="hidden" name="_method" value="PUT"/>
                @include('monitoring.fstp._form', ['program' => null, 'editMode' => true])
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" id="editSubmitBtn"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                        Update Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ─── Config ──────────────────────────────────────────────────────────────────
const BASE_URL          = '{{ route("foreign-programs.index") }}';
const PARTICIPANTS_BASE = '/foreign-programs';
const CSRF              = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';

// Modality / status label maps (mirrors PHP)
const MODALITY_LABELS = { 'in-person':'In-Person', online:'Online', hybrid:'Hybrid' };
const STATUS_LABELS   = {
    for_dissemination:    'For Dissemination',
    waiting_for_nominees: 'Waiting for Nominees',
    for_interview:        'For Interview',
    for_endorsement:      'For Endorsement',
    no_nominee:           'No Nominee',
    waiting_for_result:   'Waiting for Result',
    ongoing:              'Ongoing',
    concluded:            'Concluded',
};
const MODALITY_CLASSES = {
    'in-person': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
    online:      'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
    hybrid:      'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
};
const STATUS_CLASSES = {
    for_dissemination:    'bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300',
    waiting_for_nominees: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
    for_interview:        'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
    for_endorsement:      'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
    no_nominee:           'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
    waiting_for_result:   'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
    ongoing:              'bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300',
    concluded:            'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
};

// ─── State ───────────────────────────────────────────────────────────────────
let currentPage = 1;

// ─── Toast ───────────────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.className = `fixed top-5 right-5 z-[999] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all duration-300
        ${type === 'success'
            ? 'bg-green-50 border border-green-200 text-green-700 dark:bg-green-900 dark:text-green-300'
            : 'bg-red-50 border border-red-200 text-red-700 dark:bg-red-900 dark:text-red-300'}`;
    toast.innerHTML = `
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            ${type === 'success'
                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'}
        </svg>
        <span>${message}</span>`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3500);
}

// ─── Fetch & Render ───────────────────────────────────────────────────────────
function fetchPrograms(page = 1) {
    currentPage = page;
    const search        = document.getElementById('searchInput').value.trim();
    const status        = document.getElementById('statusFilter').value;
    const semester      = document.getElementById('semesterFilter').value;
    const year          = document.getElementById('yearFilter').value;
    const sponsor       = document.getElementById('sponsorFilter').value.trim();
    const deadlineFrom  = document.getElementById('deadlineFrom').value;
    const deadlineTo    = document.getElementById('deadlineTo').value;
    const interviewFrom = document.getElementById('interviewFrom').value;
    const interviewTo   = document.getElementById('interviewTo').value;

    const hasFilter = search || status || semester || year || sponsor
                   || deadlineFrom || deadlineTo || interviewFrom || interviewTo;
    document.getElementById('clearBtn').classList.toggle('hidden', !hasFilter);

    const params = new URLSearchParams({ page });
    if (search)        params.append('search',         search);
    if (status)        params.append('status',         status);
    if (semester)      params.append('semester',       semester);
    if (year)          params.append('year',           year);
    if (sponsor)       params.append('sponsor',        sponsor);
    if (deadlineFrom)  params.append('deadline_from',  deadlineFrom);
    if (deadlineTo)    params.append('deadline_to',    deadlineTo);
    if (interviewFrom) params.append('interview_from', interviewFrom);
    if (interviewTo)   params.append('interview_to',   interviewTo);

    const tbody = document.getElementById('programTableBody');
    tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-10 text-center text-gray-400 dark:text-gray-500 text-sm">Loading…</td></tr>`;

    fetch(`${BASE_URL}?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(json => {
        renderTable(json.data);
        renderPagination(json);
    })
    .catch(() => {
        tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-10 text-center text-red-400 text-sm">Failed to load data.</td></tr>`;
    });
}

function fmtDate(d) {
    if (!d) return '—';
    const dt = new Date(d);
    return dt.toLocaleDateString('en-PH', { month: 'short', day: '2-digit', year: 'numeric' });
}

function renderTable(programs) {
    const tbody = document.getElementById('programTableBody');
    if (!programs.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No programs found.</p>
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = programs.map(p => {
        const modalityCls   = MODALITY_CLASSES[p.modality] ?? 'bg-gray-100 text-gray-600';
        const statusCls     = STATUS_CLASSES[p.status]     ?? 'bg-gray-100 text-gray-600';
        const statusLabel   = STATUS_LABELS[p.status]      ?? p.status;
        const modalityLabel = MODALITY_LABELS[p.modality]  ?? p.modality;
        const period        = `${fmtDate(p.program_start)} – ${fmtDate(p.program_end)}`;

        return `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 max-w-[220px]">
                <a href="${PARTICIPANTS_BASE}/${p.id}/participants">
                <div class="truncate" title="${escHtml(p.program_title)}">${escHtml(p.program_title)}</div>
                </a>
                <div class="text-xs text-gray-400 truncate" title="${escHtml(p.organizing_sponsor)}">${escHtml(p.organizing_sponsor)}</div>
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap text-xs">${period}</td>
            <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">${p.slots}</td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${modalityCls}">
                    ${modalityLabel}
                </span>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap ${statusCls}">
                    ${statusLabel}
                </span>
            </td>
            <td class="px-4 py-3 text-center">
                <a href="${PARTICIPANTS_BASE}/${p.id}/participants"
                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium rounded-lg border border-blue-200 transition dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    ${p.participants_count}
                </a>
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-center gap-1">
                    <button onclick="openViewModal(${p.id})"
                        class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-indigo-900/30"
                        title="View Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button onclick="openEditModal(${p.id})"
                        class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/30"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteProgram(${p.id})"
                        class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/30"
                        title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderPagination(json) {
    const container = document.getElementById('paginationContainer');
    const { current_page, last_page, from, to, total } = json;

    if (last_page <= 1) {
        container.classList.add('hidden');
        return;
    }
    container.classList.remove('hidden');

    const btnBase     = 'px-3 py-1.5 text-xs rounded-lg border transition ';
    const btnActive   = btnBase + 'bg-blue-600 text-white border-blue-600';
    const btnNormal   = btnBase + 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700';
    const btnDisabled = btnBase + 'bg-gray-50 text-gray-300 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700';

    let pages = '';
    const delta = 2;
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - delta && i <= current_page + delta)) {
            pages += `<button onclick="fetchPrograms(${i})"
                class="${i === current_page ? btnActive : btnNormal}">${i}</button>`;
        } else if (i === current_page - delta - 1 || i === current_page + delta + 1) {
            pages += `<span class="px-1 text-gray-400 text-xs">…</span>`;
        }
    }

    container.innerHTML = `
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Showing <span class="font-medium">${from}</span>–<span class="font-medium">${to}</span>
                of <span class="font-medium">${total}</span> programs
            </p>
            <div class="flex items-center gap-1.5 flex-wrap">
                <button onclick="fetchPrograms(${current_page - 1})" ${current_page === 1 ? 'disabled' : ''}
                    class="${current_page === 1 ? btnDisabled : btnNormal}">‹ Prev</button>
                ${pages}
                <button onclick="fetchPrograms(${current_page + 1})" ${current_page === last_page ? 'disabled' : ''}
                    class="${current_page === last_page ? btnDisabled : btnNormal}">Next ›</button>
            </div>
        </div>`;
}

// ─── View Details Modal ───────────────────────────────────────────────────────
function openViewModal(id) {
    const body = document.getElementById('viewModalBody');
    body.innerHTML = `<p class="text-gray-400 text-center py-6">Loading…</p>`;
    document.getElementById('viewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`/foreign-programs/${id}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(p => {
            document.getElementById('viewParticipantsLink').href = `${PARTICIPANTS_BASE}/${p.id}/participants`;

            const row   = (label, value) => `
                <div class="flex gap-3">
                    <span class="w-36 flex-shrink-0 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 pt-0.5">${label}</span>
                    <span class="text-gray-800 dark:text-gray-200">${value || '—'}</span>
                </div>`;
            const badge = (text, cls) =>
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cls}">${text}</span>`;

            body.innerHTML = `
                <div class="mb-2">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">${escHtml(p.program_title)}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">${escHtml(p.organizing_sponsor)}</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5 mt-4">
                    ${row('Period', `${fmtDate(p.program_start)} – ${fmtDate(p.program_end)}`)}
                    ${row('Slots', p.slots)}
                    ${row('Modality', badge(MODALITY_LABELS[p.modality] ?? p.modality, MODALITY_CLASSES[p.modality] ?? 'bg-gray-100 text-gray-600'))}
                    ${row('Status',   badge(STATUS_LABELS[p.status]     ?? p.status,    STATUS_CLASSES[p.status]    ?? 'bg-gray-100 text-gray-600'))}
                    ${p.online_start ? row('Online Schedule', `${fmtDate(p.online_start)} – ${fmtDate(p.online_end)}`) : ''}
                    ${row('Submission Date', fmtDate(p.submission_date))}
                    ${p.embassy_deadline ? row('Embassy Deadline', `<span class="font-semibold text-rose-600 dark:text-rose-400">${fmtDate(p.embassy_deadline)}</span>`) : ''}
                    ${p.interview_date   ? row('Interview Date',   `<span class="font-semibold text-violet-600 dark:text-violet-400">${fmtDate(p.interview_date)}</span>`)  : ''}
                    ${row('Attached Agency',  p.attached_agency  ? escHtml(p.attached_agency)  : null)}
                    ${row('Invited Agencies', p.invited_agencies ? escHtml(p.invited_agencies) : null)}
                </div>`;
        });
}
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ─── Add Modal ────────────────────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('addForm').reset();
    handleModalityChange('add');
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('addSubmitBtn');
    btn.disabled    = true;
    btn.textContent = 'Saving…';

    fetch(BASE_URL, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body:    new FormData(this),
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        if (ok) {
            closeAddModal();
            fetchPrograms(1);
            showToast('Program added successfully.');
        } else {
            const errs = Object.values(data.errors ?? {}).flat().join(' ');
            showToast(errs || 'Failed to save.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => { btn.disabled = false; btn.textContent = 'Save Program'; });
});

// ─── Edit Modal ───────────────────────────────────────────────────────────────
let editingId = null;

function openEditModal(id) {
    editingId = id;
    const form = document.getElementById('editForm');
    form.reset();

    fetch(`/foreign-programs/${id}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            // Populate all fields except inperson (removed)
            ['program_title','program_start','program_end','slots','modality',
             'online_start','online_end',
             'organizing_sponsor','status','submission_date','embassy_deadline',
             'interview_date','invited_agencies','attached_agency'].forEach(field => {
                const el = form.querySelector(`[name="${field}"]`);
                if (el) el.value = data[field] ?? '';
            });
            handleModalityChange('edit', data.modality);
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('editSubmitBtn');
    btn.disabled    = true;
    btn.textContent = 'Updating…';

    fetch(`/foreign-programs/${editingId}`, {
        method:  'POST',  // FormData with _method=PUT
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body:    new FormData(this),
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        if (ok) {
            closeEditModal();
            fetchPrograms(currentPage);
            showToast('Program updated successfully.');
        } else {
            const errs = Object.values(data.errors ?? {}).flat().join(' ');
            showToast(errs || 'Failed to update.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => { btn.disabled = false; btn.textContent = 'Update Program'; });
});

// ─── Delete ───────────────────────────────────────────────────────────────────
function deleteProgram(id) {
    if (!confirm('Delete this program and all its participants?')) return;

    fetch(`/foreign-programs/${id}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => {
        if (r.ok) {
            fetchPrograms(currentPage);
            showToast('Program deleted.');
        } else {
            showToast('Failed to delete.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'));
}

// ─── Modality Toggle ──────────────────────────────────────────────────────────
// Only hybrid needs an extra online schedule section.
// In-person and online both use program_start/program_end as their period.
function handleModalityChange(prefix, value) {
    const modal = document.getElementById(`${prefix}Modal`);
    const sel   = modal?.querySelector('[name="modality"]');
    const mod   = value ?? sel?.value;
    const form  = sel?.closest('form');
    if (!form) return;

    const onlineSection = form.querySelector('.modality-online');

    if (mod === 'hybrid') {
        onlineSection?.classList.remove('hidden');
    } else {
        onlineSection?.classList.add('hidden');
    }
}

// ─── Clear Filters ────────────────────────────────────────────────────────────
function clearFilters() {
    document.getElementById('searchInput').value    = '';
    document.getElementById('statusFilter').value   = '';
    document.getElementById('semesterFilter').value = '';
    document.getElementById('yearFilter').value     = '';
    document.getElementById('sponsorFilter').value  = '';
    document.getElementById('deadlineFrom').value   = '';
    document.getElementById('deadlineTo').value     = '';
    document.getElementById('interviewFrom').value  = '';
    document.getElementById('interviewTo').value    = '';
    fetchPrograms(1);
}

// ─── Misc ─────────────────────────────────────────────────────────────────────
function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Event Listeners ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    ['addModal','editModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        const sel   = modal?.querySelector('[name="modality"]');
        if (sel) {
            sel.addEventListener('change', () => {
                handleModalityChange(modalId === 'addModal' ? 'add' : 'edit', sel.value);
            });
        }
    });

    // Debounce helper
    function debounce(fn, delay) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
    }

    const autoSearch = debounce(() => fetchPrograms(1), 400);

    // Text inputs — debounced
    document.getElementById('searchInput').addEventListener('input', autoSearch);
    document.getElementById('sponsorFilter').addEventListener('input', autoSearch);

    // Selects — fire immediately on change
    ['statusFilter', 'semesterFilter', 'yearFilter'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => fetchPrograms(1));
    });

    // Date pickers — fire immediately on change
    ['deadlineFrom', 'deadlineTo', 'interviewFrom', 'interviewTo'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => fetchPrograms(1));
    });

    // Initial load
    fetchPrograms(1);
});
</script>
@endpush

</x-monitoring-layout>
</x-layout>