{{-- resources/views/requirements-tracker/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Requirements Tracker</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════════
   ROOT TOKENS
═══════════════════════════════════════════════════════ */
:root {
    --bg:        #0d1117;
    --surface:   #161b22;
    --surface2:  #1c2230;
    --border:    #2a3244;
    --border2:   #334155;
    --text:      #e2e8f0;
    --text-dim:  #8899aa;
    --text-muted:#4a5568;
    --accent:    #3b82f6;
    --accent-dim:#1d4ed8;
    --danger:    #ef4444;
    --danger-dim:#991b1b;
    --warn:      #f59e0b;
    --success:   #22c55e;
    --success-dim:#15803d;
    --mono:      'IBM Plex Mono', monospace;
    --sans:      'IBM Plex Sans', sans-serif;
    --radius:    6px;
    --shadow:    0 4px 24px rgba(0,0,0,.45);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: var(--bg);
    color: var(--text);
    font-family: var(--sans);
    font-size: 14px;
    line-height: 1.6;
    min-height: 100vh;
}

/* ═══════════════════════════════════════════════════════
   LAYOUT
═══════════════════════════════════════════════════════ */
.app-header {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 28px;
    display: flex;
    align-items: center;
    gap: 16px;
    height: 58px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.app-header .logo-mark {
    width: 32px;
    height: 32px;
    background: var(--accent);
    border-radius: 6px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.app-header .logo-mark svg { width: 18px; height: 18px; }

.app-header h1 {
    font-family: var(--mono);
    font-size: 14px;
    font-weight: 600;
    letter-spacing: .5px;
    color: var(--text);
}

.app-header .breadcrumb {
    font-family: var(--mono);
    font-size: 11px;
    color: var(--text-muted);
    margin-left: auto;
}

.main-wrap {
    max-width: 1540px;
    margin: 0 auto;
    padding: 24px 28px 60px;
}

/* ═══════════════════════════════════════════════════════
   SUMMARY CARDS
═══════════════════════════════════════════════════════ */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}

.summary-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
}

.summary-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
}

.summary-card.card-total::before    { background: var(--accent); }
.summary-card.card-submitted::before { background: var(--success); }
.summary-card.card-missing::before  { background: var(--warn); }
.summary-card.card-overdue::before  { background: var(--danger); }

.summary-card .label {
    font-family: var(--mono);
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.summary-card .value {
    font-family: var(--mono);
    font-size: 32px;
    font-weight: 600;
    line-height: 1;
}

.summary-card.card-total    .value { color: var(--accent); }
.summary-card.card-submitted .value { color: var(--success); }
.summary-card.card-missing  .value { color: var(--warn); }
.summary-card.card-overdue  .value { color: var(--danger); }

/* ═══════════════════════════════════════════════════════
   FILTER PANEL
═══════════════════════════════════════════════════════ */
.filter-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 20px;
    margin-bottom: 20px;
}

.filter-panel-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.filter-panel-header span {
    font-family: var(--mono);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-dim);
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1.5fr 1.5fr 1fr 1fr 1fr auto;
    gap: 10px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-family: var(--mono);
    font-size: 10px;
    font-weight: 500;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: var(--text-muted);
}

.filter-group input,
.filter-group select {
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    color: var(--text);
    font-family: var(--sans);
    font-size: 13px;
    padding: 8px 10px;
    outline: none;
    transition: border-color .18s;
    width: 100%;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: var(--accent);
}

.filter-group select option { background: var(--surface2); }

.btn {
    font-family: var(--mono);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .5px;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    padding: 8px 16px;
    transition: background .18s, transform .1s;
    white-space: nowrap;
}

.btn:active { transform: scale(.97); }

.btn-primary {
    background: var(--accent);
    color: #fff;
}

.btn-primary:hover { background: var(--accent-dim); }

.btn-ghost {
    background: transparent;
    border: 1px solid var(--border2);
    color: var(--text-dim);
}

.btn-ghost:hover {
    background: var(--surface2);
    color: var(--text);
}

.btn-actions {
    display: flex;
    gap: 6px;
}

/* ═══════════════════════════════════════════════════════
   TABLE WRAPPER
═══════════════════════════════════════════════════════ */
.table-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
}

.table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
}

.table-toolbar .tbl-title {
    font-family: var(--mono);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: var(--text-dim);
}

.table-toolbar .record-count {
    font-family: var(--mono);
    font-size: 11px;
    color: var(--text-muted);
}

.table-scroll { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: collapse;
}

thead tr {
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}

thead th {
    font-family: var(--mono);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .9px;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 11px 14px;
    text-align: left;
    white-space: nowrap;
}

tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .1s;
}

tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--surface2); }

tbody td {
    padding: 11px 14px;
    font-size: 13px;
    vertical-align: middle;
}

.cell-mono {
    font-family: var(--mono);
    font-size: 12px;
    color: var(--text-dim);
}

.cell-name {
    font-weight: 500;
    white-space: nowrap;
}

.cell-office {
    color: var(--text-dim);
    font-size: 12px;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cell-program {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 12px;
    color: var(--text-dim);
}

.cell-req {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-weight: 500;
}

/* ── Badges ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: var(--mono);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .6px;
    text-transform: uppercase;
    border-radius: 3px;
    padding: 3px 8px;
    white-space: nowrap;
}

.badge-submitted  { background: rgba(34,197,94,.12);  color: var(--success); border: 1px solid rgba(34,197,94,.25); }
.badge-pending    { background: rgba(245,158,11,.12); color: var(--warn);    border: 1px solid rgba(245,158,11,.25); }
.badge-approved   { background: rgba(59,130,246,.12); color: var(--accent);  border: 1px solid rgba(59,130,246,.25); }
.badge-rejected   { background: rgba(239,68,68,.12);  color: var(--danger);  border: 1px solid rgba(239,68,68,.25); }
.badge-missing    { background: rgba(255,255,255,.05); color: var(--text-muted); border: 1px solid var(--border); }
.badge-overdue    { background: rgba(239,68,68,.18);  color: var(--danger);  border: 1px solid rgba(239,68,68,.35); }

/* ── Due date cell ── */
.due-cell { white-space: nowrap; }

.due-date-label {
    font-family: var(--mono);
    font-size: 12px;
}

.due-meta {
    font-size: 10px;
    font-family: var(--mono);
    margin-top: 2px;
}

.due-meta.overdue  { color: var(--danger); }
.due-meta.upcoming { color: var(--warn); }
.due-meta.ok       { color: var(--success); }

/* ═══════════════════════════════════════════════════════
   EMPTY / LOADING
═══════════════════════════════════════════════════════ */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 60px 20px;
    color: var(--text-muted);
}

.empty-state svg { opacity: .3; }

.empty-state p {
    font-family: var(--mono);
    font-size: 12px;
    letter-spacing: .5px;
}

.loading-row td {
    text-align: center;
    padding: 40px !important;
}

.spinner {
    display: inline-block;
    width: 22px;
    height: 22px;
    border: 2px solid var(--border2);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ═══════════════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════════════ */
.pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-top: 1px solid var(--border);
    background: var(--surface2);
}

.pag-info {
    font-family: var(--mono);
    font-size: 11px;
    color: var(--text-muted);
}

.pag-controls {
    display: flex;
    gap: 4px;
}

.pag-btn {
    font-family: var(--mono);
    font-size: 11px;
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text-dim);
    border-radius: 4px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background .15s, border-color .15s;
}

.pag-btn:hover:not(:disabled) {
    background: var(--surface);
    border-color: var(--border2);
    color: var(--text);
}

.pag-btn.active {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
}

.pag-btn:disabled {
    opacity: .35;
    cursor: not-allowed;
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
    .filter-grid {
        grid-template-columns: 1fr 1fr 1fr;
    }
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 680px) {
    .filter-grid { grid-template-columns: 1fr; }
    .summary-grid { grid-template-columns: 1fr 1fr; }
    .main-wrap { padding: 16px; }
}

/* ═══════════════════════════════════════════════════════
   SCROLLBAR
═══════════════════════════════════════════════════════ */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--surface); }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
</style>
</head>
<body>

{{-- ─── HEADER ─── --}}
<header class="app-header">
    <div class="logo-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
    </div>
    <h1>Requirements Tracker</h1>
    <span class="breadcrumb">Training Management System</span>
</header>

{{-- ─── MAIN ─── --}}
<div class="main-wrap">

    {{-- Summary Cards --}}
    <div class="summary-grid">
        <div class="summary-card card-total">
            <div class="label">Total Records</div>
            <div class="value" id="sum-total">—</div>
        </div>
        <div class="summary-card card-submitted">
            <div class="label">Submitted</div>
            <div class="value" id="sum-submitted">—</div>
        </div>
        <div class="summary-card card-missing">
            <div class="label">Not Yet Submitted</div>
            <div class="value" id="sum-missing">—</div>
        </div>
        <div class="summary-card card-overdue">
            <div class="label">Overdue</div>
            <div class="value" id="sum-overdue">—</div>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="filter-panel">
        <div class="filter-panel-header">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8899aa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span>Filters</span>
        </div>

        <div class="filter-grid">
            {{-- Search --}}
            <div class="filter-group">
                <label>Search Employee</label>
                <input type="text" id="f-search" placeholder="Name or employee code…" autocomplete="off">
            </div>

            {{-- Requirement --}}
            <div class="filter-group">
                <label>Requirement</label>
                <select id="f-requirement">
                    <option value="">All Requirements</option>
                </select>
            </div>

            {{-- Office --}}
            <div class="filter-group">
                <label>Office / Division</label>
                <select id="f-office">
                    <option value="">All Offices</option>
                </select>
            </div>

            {{-- Submission Status --}}
            <div class="filter-group">
                <label>Status</label>
                <select id="f-status">
                    <option value="">All</option>
                    <option value="submitted">Submitted</option>
                    <option value="not_submitted">Not Submitted</option>
                </select>
            </div>

            {{-- Date Start --}}
            <div class="filter-group">
                <label>Batch End From</label>
                <input type="date" id="f-date-start">
            </div>

            {{-- Date End --}}
            <div class="filter-group">
                <label>Batch End To</label>
                <input type="date" id="f-date-end">
            </div>

            {{-- Actions --}}
            <div class="filter-group">
                <label>&nbsp;</label>
                <div class="btn-actions">
                    <button class="btn btn-ghost" id="btn-reset" type="button">Reset</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <div class="table-toolbar">
            <span class="tbl-title">Participant Requirements</span>
            <span class="record-count" id="record-count">Loading…</span>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Office</th>
                        <th>Program</th>
                        <th>Batch</th>
                        <th>Requirement</th>
                        <th>Due Date</th>
                        <th>Submission</th>
                        <th>Sub. Date</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="tbl-body">
                    <tr class="loading-row">
                        <td colspan="10"><div class="spinner"></div></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <span class="pag-info" id="pag-info">—</span>
            <div class="pag-controls" id="pag-controls"></div>
        </div>
    </div>

</div>{{-- /main-wrap --}}

<script>
/* ════════════════════════════════════════════════════════
   STATE
════════════════════════════════════════════════════════ */
const STATE = {
    page:    1,
    perPage: 20,
    search:  '',
    office:  '',
    requirement: '',
    status:  '',
    dateStart: '',
    dateEnd:   '',
    debounceTimer: null,
    loading: false,
};

/* ════════════════════════════════════════════════════════
   REFS
════════════════════════════════════════════════════════ */
const $ = id => document.getElementById(id);
const fSearch      = $('f-search');
const fRequirement = $('f-requirement');
const fOffice      = $('f-office');
const fStatus      = $('f-status');
const fDateStart   = $('f-date-start');
const fDateEnd     = $('f-date-end');
const btnReset     = $('btn-reset');
const tblBody      = $('tbl-body');
const recordCount  = $('record-count');
const pagInfo      = $('pag-info');
const pagControls  = $('pag-controls');

/* ════════════════════════════════════════════════════════
   INIT
════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    loadRequirements();
    loadOffices();
    fetchData();
    bindFilters();
});

/* ════════════════════════════════════════════════════════
   LOAD FILTER OPTIONS
════════════════════════════════════════════════════════ */
async function loadRequirements() {
    try {
        const res  = await fetch("{{ route('requirements-tracker.requirements') }}");
        const data = await res.json();
        data.forEach(r => {
            const opt = document.createElement('option');
            opt.value       = r.id;
            opt.textContent = `[${r.program_code}] ${r.title}`;
            fRequirement.appendChild(opt);
        });
    } catch (e) { console.error('Requirements load error', e); }
}

async function loadOffices() {
    try {
        const res  = await fetch("{{ route('requirements-tracker.offices') }}");
        const data = await res.json();
        data.forEach(o => {
            const opt = document.createElement('option');
            opt.value       = o;
            opt.textContent = o;
            fOffice.appendChild(opt);
        });
    } catch (e) { console.error('Offices load error', e); }
}

/* ════════════════════════════════════════════════════════
   BIND FILTER EVENTS
════════════════════════════════════════════════════════ */
function bindFilters() {
    // Debounced text search
    fSearch.addEventListener('input', () => {
        clearTimeout(STATE.debounceTimer);
        STATE.debounceTimer = setTimeout(() => {
            STATE.search = fSearch.value.trim();
            STATE.page   = 1;
            fetchData();
        }, 350);
    });

    // Instant selects / dates
    fRequirement.addEventListener('change', () => { STATE.requirement = fRequirement.value; STATE.page = 1; fetchData(); });
    fOffice.addEventListener('change',      () => { STATE.office      = fOffice.value;      STATE.page = 1; fetchData(); });
    fStatus.addEventListener('change',      () => { STATE.status      = fStatus.value;      STATE.page = 1; fetchData(); });
    fDateStart.addEventListener('change',   () => { STATE.dateStart   = fDateStart.value;   STATE.page = 1; fetchData(); });
    fDateEnd.addEventListener('change',     () => { STATE.dateEnd     = fDateEnd.value;      STATE.page = 1; fetchData(); });

    // Reset
    btnReset.addEventListener('click', () => {
        fSearch.value         = '';
        fRequirement.value    = '';
        fOffice.value         = '';
        fStatus.value         = '';
        fDateStart.value      = '';
        fDateEnd.value        = '';
        Object.assign(STATE, {
            page: 1, search: '', office: '', requirement: '',
            status: '', dateStart: '', dateEnd: '',
        });
        fetchData();
    });
}

/* ════════════════════════════════════════════════════════
   FETCH DATA (AJAX)
════════════════════════════════════════════════════════ */
async function fetchData() {
    if (STATE.loading) return;
    STATE.loading = true;
    showLoading();

    const params = new URLSearchParams({
        page:               STATE.page,
        per_page:           STATE.perPage,
        search:             STATE.search,
        office:             STATE.office,
        requirement_id:     STATE.requirement,
        submission_status:  STATE.status,
        date_start:         STATE.dateStart,
        date_end:           STATE.dateEnd,
    });

    try {
        const res  = await fetch(`{{ route('requirements-tracker.data') }}?${params}`);
        const json = await res.json();
        renderTable(json.data, json.meta);
        renderSummary(json.summary);
        renderPagination(json.meta);
    } catch (e) {
        tblBody.innerHTML = `<tr><td colspan="10" style="text-align:center;padding:40px;color:var(--danger);font-family:var(--mono);font-size:12px;">Error loading data. Please try again.</td></tr>`;
        console.error('Fetch error', e);
    } finally {
        STATE.loading = false;
    }
}

/* ════════════════════════════════════════════════════════
   RENDER TABLE
════════════════════════════════════════════════════════ */
function renderTable(rows, meta) {
    if (!rows || rows.length === 0) {
        tblBody.innerHTML = `
            <tr>
                <td colspan="10">
                    <div class="empty-state">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <p>No records found matching your filters.</p>
                    </div>
                </td>
            </tr>`;
        recordCount.textContent = '0 records';
        return;
    }

    const offset = (meta.current_page - 1) * meta.per_page;

    tblBody.innerHTML = rows.map((row, i) => {
        const rowNum = offset + i + 1;

        // Submission badge
        let subBadge = '';
        if (row.submission_id) {
            const statusMap = {
                pending:  'badge-pending',
                approved: 'badge-approved',
                rejected: 'badge-rejected',
            };
            const cls   = statusMap[row.submission_status] || 'badge-pending';
            const label = capitalize(row.submission_status || 'pending');
            subBadge = `<span class="badge ${cls}">${label}</span>`;
        } else {
            if (row.is_overdue) {
                subBadge = `<span class="badge badge-overdue">Overdue</span>`;
            } else {
                subBadge = `<span class="badge badge-missing">Not Submitted</span>`;
            }
        }

        // Due date cell
        let dueCellHtml = '—';
        if (row.due_date) {
            let metaClass  = 'ok';
            let metaLabel  = '';
            if (!row.submission_id) {
                if (row.is_overdue) {
                    metaClass = 'overdue';
                    const days = Math.abs(row.days_remaining);
                    metaLabel = `${days}d overdue`;
                } else if (row.days_remaining !== null && row.days_remaining <= 14) {
                    metaClass = 'upcoming';
                    metaLabel = `${Math.round(row.days_remaining)}d left`;
                }
            } else {
                metaLabel = 'Submitted';
            }

            dueCellHtml = `
                <div class="due-cell">
                    <div class="due-date-label">${formatDate(row.due_date)}</div>
                    ${metaLabel ? `<div class="due-meta ${metaClass}">${metaLabel}</div>` : ''}
                </div>`;
        }

        // Submitted at
        const submittedAt = row.submitted_at
            ? `<span style="font-family:var(--mono);font-size:11px;color:var(--text-dim)">${formatDate(row.submitted_at)}</span>`
            : '<span style="color:var(--text-muted)">—</span>';

        // Remarks / notes
        const remarks = row.remarks || row.notes || '—';

        return `
            <tr>
                <td class="cell-mono">${rowNum}</td>
                <td>
                    <div class="cell-name">${escHtml(row.full_name)}</div>
                    <div class="cell-mono" style="font-size:10px;color:var(--text-muted)">${escHtml(row.empcode)}</div>
                </td>
                <td class="cell-office" title="${escHtml(row.office_division || '')}">
                    ${escHtml(row.office || row.office_division || '—')}
                </td>
                <td class="cell-program" title="${escHtml(row.program_title)}">
                    <span style="font-family:var(--mono);font-size:10px;color:var(--accent)">${escHtml(row.program_code)}</span><br>
                    ${escHtml(row.program_title)}
                </td>
                <td class="cell-mono" style="font-size:11px">
                    ${escHtml(row.batch)}<br>
                    <span style="color:var(--text-muted)">${formatDate(row.date_end)}</span>
                </td>
                <td class="cell-req" title="${escHtml(row.requirement_title)}">${escHtml(row.requirement_title)}</td>
                <td>${dueCellHtml}</td>
                <td>${subBadge}</td>
                <td>${submittedAt}</td>
                <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:var(--text-dim)" title="${escHtml(remarks)}">${escHtml(remarks)}</td>
            </tr>`;
    }).join('');

    const start = offset + 1;
    const end   = Math.min(offset + rows.length, meta.total);
    recordCount.textContent = `Showing ${start}–${end} of ${meta.total} records`;
}

/* ════════════════════════════════════════════════════════
   RENDER SUMMARY
════════════════════════════════════════════════════════ */
function renderSummary(s) {
    animateCount('sum-total',     s.total_submitted + s.total_not_submitted);
    animateCount('sum-submitted', s.total_submitted);
    animateCount('sum-missing',   s.total_not_submitted);
    animateCount('sum-overdue',   s.total_overdue);
}

function animateCount(id, target) {
    const el   = $(id);
    const start = parseInt(el.textContent) || 0;
    const diff  = target - start;
    const steps = 18;
    let   step  = 0;
    const timer = setInterval(() => {
        step++;
        el.textContent = Math.round(start + (diff * step / steps));
        if (step >= steps) { el.textContent = target; clearInterval(timer); }
    }, 20);
}

/* ════════════════════════════════════════════════════════
   PAGINATION
════════════════════════════════════════════════════════ */
function renderPagination(meta) {
    const { current_page: cur, last_page: last, per_page: pp, total } = meta;

    const from = (cur - 1) * pp + 1;
    const to   = Math.min(cur * pp, total);
    pagInfo.textContent = total > 0 ? `Page ${cur} of ${last}` : 'No records';

    // Build page number buttons (window of ±2)
    const pages = [];
    for (let p = Math.max(1, cur - 2); p <= Math.min(last, cur + 2); p++) {
        pages.push(p);
    }

    pagControls.innerHTML = '';

    const prevBtn = makePageBtn('‹ Prev', cur === 1, () => { STATE.page = cur - 1; fetchData(); });
    pagControls.appendChild(prevBtn);

    if (pages[0] > 1) {
        pagControls.appendChild(makePageBtn('1', false, () => { STATE.page = 1; fetchData(); }));
        if (pages[0] > 2) pagControls.appendChild(makeEllipsis());
    }

    pages.forEach(p => {
        const btn = makePageBtn(p, false, () => { STATE.page = p; fetchData(); });
        if (p === cur) btn.classList.add('active');
        pagControls.appendChild(btn);
    });

    if (pages[pages.length - 1] < last) {
        if (pages[pages.length - 1] < last - 1) pagControls.appendChild(makeEllipsis());
        pagControls.appendChild(makePageBtn(last, false, () => { STATE.page = last; fetchData(); }));
    }

    const nextBtn = makePageBtn('Next ›', cur === last || last === 0, () => { STATE.page = cur + 1; fetchData(); });
    pagControls.appendChild(nextBtn);
}

function makePageBtn(label, disabled, onClick) {
    const btn = document.createElement('button');
    btn.className   = 'pag-btn';
    btn.textContent = label;
    btn.disabled    = disabled;
    if (!disabled) btn.addEventListener('click', onClick);
    return btn;
}

function makeEllipsis() {
    const s = document.createElement('span');
    s.className   = 'pag-btn';
    s.textContent = '…';
    s.style.cursor = 'default';
    s.style.opacity = '.4';
    return s;
}

/* ════════════════════════════════════════════════════════
   HELPERS
════════════════════════════════════════════════════════ */
function showLoading() {
    tblBody.innerHTML = `<tr class="loading-row"><td colspan="10"><div class="spinner"></div></td></tr>`;
    recordCount.textContent = 'Loading…';
}

function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function formatDate(d) {
    if (!d) return '—';
    const dt = new Date(d);
    if (isNaN(dt)) return d;
    return dt.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit' });
}

function capitalize(s) {
    if (!s) return '';
    return s.charAt(0).toUpperCase() + s.slice(1);
}
</script>

</body>
</html>