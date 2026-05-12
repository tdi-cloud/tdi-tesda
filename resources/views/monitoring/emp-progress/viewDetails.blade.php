

  <style>
    /* Skeleton shimmer */
    .skeleton-line {
      background: linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
      border-radius: 6px;
    }
    @keyframes shimmer {
      0%   { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Inner modal scrollbar */
    .req-scroll::-webkit-scrollbar       { width: 5px; }
    .req-scroll::-webkit-scrollbar-track { background: #f8fafc; }
    .req-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* Status pill colours (used in JS) */
    .pill-submitted { @apply badge badge-success badge-sm text-white font-semibold; }
    .pill-approved  { @apply badge badge-success badge-sm text-white font-semibold; }
    .pill-reviewed  { @apply badge badge-info    badge-sm text-white font-semibold; }
    .pill-pending   { @apply badge badge-warning  badge-sm font-semibold; }
    .pill-missing   { @apply badge badge-error    badge-sm text-white font-semibold; }
  </style>

  {{-- ══════════════════════ MAIN PROFILE MODAL ══════════════════════ --}}
  <dialog id="profile_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden flex flex-col" style="max-height:92vh;">

      {{-- Header gradient --}}
      <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-500 px-6 py-5 shrink-0">
        <div class="flex items-center justify-between">
          <h3 class="text-white poppins-bold text-lg flex items-center gap-2">
            <i data-lucide="id-card" class="w-5 h-5"></i> Employee Training Details
          </h3>
          <button onclick="document.getElementById('profile_modal').close()"
                  class="btn btn-ghost btn-sm btn-circle text-white hover:bg-white/20">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      {{-- Scrollable body --}}
      <div id="modal-body" class="flex-1 overflow-y-auto px-6 py-6 req-scroll">

        {{-- ── SKELETON ── --}}
        <div id="skeleton-loader">
          <div class="flex gap-5 mb-6">
            <div class="skeleton-line w-24 h-24 rounded-2xl shrink-0"></div>
            <div class="flex-1 space-y-2 pt-1">
              <div class="skeleton-line h-6 w-2/3"></div>
              <div class="skeleton-line h-4 w-1/3"></div>
              <div class="skeleton-line h-4 w-full mt-4"></div>
            </div>
          </div>
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
            <div class="skeleton-line h-24 rounded-xl"></div>
            <div class="skeleton-line h-24 rounded-xl"></div>
            <div class="skeleton-line h-24 rounded-xl"></div>
            <div class="skeleton-line h-24 rounded-xl"></div>
          </div>
          <div class="skeleton-line h-36 rounded-2xl mb-6"></div>
          <div class="skeleton-line h-56 rounded-2xl"></div>
        </div>

        {{-- ── ERROR STATE ── --}}
        <div id="error-state" class="hidden text-center py-16">
          <div class="w-16 h-16 bg-error/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="alert-triangle" class="w-8 h-8 text-error"></i>
          </div>
          <p class="font-semibold text-lg">Could not load profile</p>
          <p id="error-message" class="text-base-content/50 text-sm mt-1"></p>
        </div>

        {{-- ── PROFILE CONTENT ── --}}
        <div id="profile-content" class="hidden space-y-6">

          {{-- Profile card --}}
          <div class="card card-bordered shadow-sm bg-base-100">
            <div class="card-body p-5">
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div id="avatar"
                     class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg shrink-0 ring-4 ring-base-100">
                </div>
                <div class="flex-1 w-full">
                  <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 id="full-name" class="text-2xl font-bold"></h3>
                    <div class="badge badge-success gap-1 font-semibold">
                      <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                      <span id="plantilla-status"></span>
                    </div>
                  </div>
                  <p id="position" class="text-base-content/50 text-sm mb-3"></p>
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                    <div class="flex items-center gap-2 text-base-content/70">
                      <i data-lucide="hash" class="w-4 h-4 opacity-50"></i>
                      <span class="opacity-60">Emp Code:</span>
                      <span id="empcode" class="font-semibold"></span>
                    </div>
                    <div class="flex items-center gap-2 text-base-content/70">
                      <i data-lucide="building-2" class="w-4 h-4 opacity-50"></i>
                      <span id="division" class="font-semibold"></span>
                    </div>
                    <div class="flex items-center gap-2 text-base-content/70">
                      <i data-lucide="briefcase" class="w-4 h-4 opacity-50"></i>
                      <span id="office" class="font-semibold"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Stats grid --}}
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-base-content/40 mb-3 flex items-center gap-2">
              <i data-lucide="chart-bar" class="w-4 h-4"></i> Program Overview
            </p>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
              <div class="stat bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                <div class="stat-figure text-blue-500">
                  <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                  </div>
                </div>
                <div id="stat-attended" class="stat-value text-blue-900 text-2xl">—</div>
                <div class="stat-desc text-blue-700 font-medium text-xs">Programs Attended</div>
              </div>
              <div class="stat bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3">
                <div class="stat-figure text-emerald-500">
                  <div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="badge-check" class="w-5 h-5"></i>
                  </div>
                </div>
                <div id="stat-completed" class="stat-value text-emerald-900 text-2xl">—</div>
                <div class="stat-desc text-emerald-700 font-medium text-xs">Programs Completed</div>
              </div>
              <div class="stat bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
                <div class="stat-figure text-amber-500">
                  <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                  </div>
                </div>
                <div id="stat-hours" class="stat-value text-amber-900 text-2xl">—</div>
                <div class="stat-desc text-amber-700 font-medium text-xs">Total Hours Rendered</div>
              </div>
              <div class="stat bg-purple-50 border border-purple-100 rounded-xl px-4 py-3">
                <div class="stat-figure text-purple-500">
                  <div class="w-9 h-9 bg-purple-500 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="star" class="w-5 h-5"></i>
                  </div>
                </div>
                <div id="stat-rating" class="stat-value text-purple-900 text-2xl">—</div>
                <div class="stat-desc text-purple-700 font-medium text-xs">Average Rating</div>
              </div>
            </div>
          </div>

          {{-- Post-Training Submissions summary --}}
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-base-content/40 mb-3 flex items-center gap-2">
              <i data-lucide="file-text" class="w-4 h-4"></i> Post-Training Submissions
            </p>
            <div class="card card-bordered shadow-sm bg-base-100">
              <div class="card-body p-5">
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div class="text-center p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white mx-auto mb-2">
                      <i data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <div id="sub-submitted" class="text-3xl font-bold text-emerald-700">—</div>
                    <div class="text-xs text-emerald-600 font-medium mt-1">Submitted</div>
                  </div>
                  <div class="text-center p-4 bg-rose-50 rounded-xl border border-rose-100">
                    <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center text-white mx-auto mb-2">
                      <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                    <div id="sub-pending" class="text-3xl font-bold text-rose-700">—</div>
                    <div class="text-xs text-rose-600 font-medium mt-1">Not Yet Approved</div>
                  </div>
                </div>
                <div>
                  <div class="flex justify-between text-xs mb-1.5">
                    <span class="font-semibold">Completion Rate</span>
                    <span id="sub-rate-label" class="font-bold">0%</span>
                  </div>
                  <progress id="sub-rate-bar" class="progress progress-success w-full" value="0" max="100"></progress>
                </div>
              </div>
            </div>
          </div>

          {{-- Enrolled programs --}}
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-base-content/40 mb-3 flex items-center gap-2">
              <i data-lucide="book-open" class="w-4 h-4"></i> Enrolled Programs
            </p>
            <div id="programs-list" class="card card-bordered shadow-sm bg-base-100 overflow-hidden">
              {{-- rows injected by JS --}}
            </div>
          </div>

        </div>{{-- /profile-content --}}
      </div>{{-- /modal-body --}}

      {{-- Footer --}}
      <div class="border-t border-base-200 px-6 py-3 flex justify-end gap-2 bg-base-50 shrink-0">
        <button onclick="document.getElementById('profile_modal').close()"
                class="btn btn-ghost btn-sm">Close</button>

        <button onclick="exportProfile()"
                class="hidden btn btn-primary btn-sm gap-1.5">
          <i data-lucide="download" class="w-4 h-4"></i> Export Profile
        </button>
      </div>

    </div>
    {{-- click-outside closes --}}
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

  {{-- ══════════════════════ REQUIREMENTS DRILL-DOWN MODAL ══════════════════════ --}}
  <dialog id="req_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-2xl p-0 overflow-hidden flex flex-col" style="max-height:85vh;">

      {{-- Header --}}
      <div class="bg-base-200 px-5 py-4 border-b border-base-300 shrink-0">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <p class="text-xs text-base-content/50 font-medium mb-0.5 uppercase tracking-wide">Requirements</p>
            <h3 id="req-modal-title" class="font-bold text-base leading-tight truncate"></h3>
            <div class="flex items-center gap-2 mt-1.5" id="req-modal-meta"></div>
          </div>
          <button onclick="document.getElementById('req_modal').close()"
                  class="btn btn-ghost btn-sm btn-circle shrink-0 mt-0.5">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>

        {{-- Mini progress bar --}}
        <div class="mt-3">
          <div class="flex justify-between text-xs mb-1">
            <span class="font-medium text-base-content/60">Submission Progress</span>
            <span id="req-modal-rate" class="font-bold"></span>
          </div>
          <progress id="req-modal-bar" class="progress progress-success w-full h-2" value="0" max="100"></progress>
        </div>
      </div>

      {{-- Tabs: All / Submitted / Pending --}}
      <div class="px-5 pt-3 pb-0 shrink-0 border-b border-base-200 bg-base-100">
        <div role="tablist" class="tabs tabs-bordered">
          <button role="tab" class="tab tab-active font-semibold text-sm" onclick="filterReqs('all', this)">
            All <span id="tab-count-all" class="ml-1 badge badge-sm badge-ghost"></span>
          </button>
          <button role="tab" class="tab font-semibold text-sm" onclick="filterReqs('submitted', this)">
            <span class="text-success">Approved</span>
            <span id="tab-count-submitted" class="ml-1 badge badge-sm badge-success text-white"></span>
          </button>
          <button role="tab" class="tab font-semibold text-sm" onclick="filterReqs('pending', this)">
            <span class="text-warning">Not Approved</span>
            <span id="tab-count-pending" class="ml-1 badge badge-sm badge-warning"></span>
          </button>
        </div>
      </div>

      {{-- Requirements list --}}
      <div id="req-list" class="flex-1 overflow-y-auto req-scroll divide-y divide-base-200">
        {{-- rows injected by JS --}}
      </div>

      {{-- Footer --}}
      <div class="border-t border-base-200 px-5 py-3 flex justify-end bg-base-50 shrink-0">
        <button onclick="document.getElementById('req_modal').close()"
                class="btn btn-ghost btn-sm">Close</button>
      </div>

    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

{{-- ══════════════════════ JAVASCRIPT ══════════════════════ --}}
<script>
// ── Config ──────────────────────────────────────────────────────────────────
// In a real Blade file replace with: const API_BASE = "{{ url('') }}";
const API_BASE = '';

// ── Status configs ───────────────────────────────────────────────────────────
const PROG_STATUS = {
  'Completed':   { badge: 'badge-success text-white', icon: 'award',       iconCls: 'bg-emerald-100 text-emerald-600' },
  'In Progress': { badge: 'badge-info    text-white', icon: 'play-circle',  iconCls: 'bg-blue-100 text-blue-600'      },
  'Upcoming':    { badge: 'badge-warning',             icon: 'hourglass',    iconCls: 'bg-amber-100 text-amber-600'    },
  'Dropped':     { badge: 'badge-error   text-white', icon: 'x-circle',     iconCls: 'bg-rose-100 text-rose-600'      },
};

const SUB_STATUS = {
  // Keys match exact DB values (case-sensitive)
  'Pending':  { badge: 'badge-warning',           label: 'Pending',   icon: 'clock',        iconBg: 'bg-warning/10 text-warning'   },
  'Approved': { badge: 'badge-success text-white', label: 'Approved', icon: 'shield-check', iconBg: 'bg-success/10 text-success'   },
  'Revised':  { badge: 'badge-info text-white',   label: 'Revised',   icon: 'refresh-cw',   iconBg: 'bg-info/10 text-info'         },
  'Rejected': { badge: 'badge-error text-white',  label: 'Rejected',  icon: 'x-circle',     iconBg: 'bg-error/10 text-error'       },
};

// ── State ────────────────────────────────────────────────────────────────────
let _currentRequirements = [];
let _currentFilter       = 'all';

// ── Profile modal ────────────────────────────────────────────────────────────
async function openParticipantProfile(empcode) {
  showSkeleton();
  document.getElementById('profile_modal').showModal();

  try {
    const res  = await fetch(`${API_BASE}/employees/${encodeURIComponent(empcode)}/profile`);
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Unknown error');
    renderProfile(data);
  } catch (err) {
    showError(err.message);
  }
}

function showSkeleton() {
  document.getElementById('skeleton-loader').classList.remove('hidden');
  document.getElementById('error-state').classList.add('hidden');
  document.getElementById('profile-content').classList.add('hidden');
}

function showError(msg) {
  document.getElementById('skeleton-loader').classList.add('hidden');
  document.getElementById('error-state').classList.remove('hidden');
  document.getElementById('profile-content').classList.add('hidden');
  document.getElementById('error-message').textContent = msg;
  lucide.createIcons();
}

function renderProfile(data) {
  const { employee, stats, submissions, enrolled_programs } = data;

  // Employee card
  document.getElementById('avatar').textContent        = employee.initials;
  document.getElementById('full-name').textContent     = employee.full_name;
  document.getElementById('plantilla-status').textContent = employee.status || 'Active';
  document.getElementById('position').textContent      = employee.position;
  document.getElementById('empcode').textContent       = employee.empcode;
  document.getElementById('division').textContent      = employee.division;
  document.getElementById('office').textContent        = employee.office || employee.section || '—';

  // Stats
  document.getElementById('stat-attended').textContent  = stats.programs_attended;
  document.getElementById('stat-completed').textContent = stats.programs_completed;
  document.getElementById('stat-hours').textContent     = stats.total_hours;
  document.getElementById('stat-rating').textContent    = `${stats.avg_rating}%` ?? 'N/A';

  // Submissions summary
  document.getElementById('sub-submitted').textContent   = submissions.submitted;
  document.getElementById('sub-pending').textContent     = submissions.pending;
  document.getElementById('sub-rate-label').textContent  = submissions.completion_rate + '%';
  document.getElementById('sub-rate-bar').value          = submissions.completion_rate;

  // Programs list
  const list = document.getElementById('programs-list');
  list.innerHTML = '';

  if (!enrolled_programs.length) {
    list.innerHTML = `
      <div class="p-10 text-center text-base-content/30">
        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-40"></i>
        <p class="text-sm">No programs enrolled yet.</p>
      </div>`;
  } else {
    enrolled_programs.forEach((prog, idx) => {
      const cfg     = PROG_STATUS[prog.status] || PROG_STATUS['Upcoming'];
      const isLast  = idx === enrolled_programs.length - 1;
      const dateStr = prog.date_start && prog.date_end
        ? `${fmtDate(prog.date_start)} – ${fmtDate(prog.date_end)}`
        : (prog.date_start ? `Starts ${fmtDate(prog.date_start)}` : '—');

      // Requirements pill — show only if there are requirements
      let reqPill = '';
      if (prog.req_total > 0) {
        const allDone = prog.req_pending === 0;
        // Count by status for richer pill label
        const approved = prog.req_submitted;
        const notDone  = prog.req_pending;
        reqPill = `
          <button
            onclick='openRequirements(${JSON.stringify(prog)})'
            class="btn btn-xs gap-1 ${allDone ? 'btn-success' : 'btn-warning'} btn-outline shrink-0">
            <i data-lucide="${allDone ? 'check-circle-2' : 'alert-circle'}" class="w-3 h-3"></i>
            ${approved}/${prog.req_total} approved
          </button>`;
      } else {
        reqPill = `<span class="text-xs text-base-content/30 shrink-0">No requirements</span>`;
      }

      list.innerHTML += `
        <div class="flex items-center gap-4 p-4 ${isLast ? '' : 'border-b border-base-200'} hover:bg-base-50 transition">
          <div class="w-10 h-10 ${cfg.iconCls} rounded-lg flex items-center justify-center shrink-0">
            <i data-lucide="${cfg.icon}" class="w-5 h-5"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-sm truncate">${esc(prog.title)}</div>
            <div class="text-xs text-base-content/50 mt-0.5">${esc(dateStr)}${prog.hours ? ' · ' + prog.hours + ' hrs' : ''}</div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            ${reqPill}
            <span class="badge ${cfg.badge} badge-sm font-semibold whitespace-nowrap">${prog.status}</span>
          </div>
        </div>`;
    });
  }

  document.getElementById('skeleton-loader').classList.add('hidden');
  document.getElementById('error-state').classList.add('hidden');
  document.getElementById('profile-content').classList.remove('hidden');
  lucide.createIcons();
}

// ── Requirements drill-down modal ────────────────────────────────────────────
function openRequirements(prog) {
  _currentRequirements = prog.requirements || [];
  _currentFilter       = 'all';

  // Header
  document.getElementById('req-modal-title').textContent = prog.title;

  // Meta badges
  const metaEl = document.getElementById('req-modal-meta');
  const cfg = PROG_STATUS[prog.status] || PROG_STATUS['Upcoming'];
  metaEl.innerHTML = `
    <span class="badge badge-sm ${cfg.badge} font-semibold">${prog.status}</span>
    ${prog.batch ? `<span class="text-xs text-base-content/50">Batch ${esc(prog.batch)}</span>` : ''}
    ${prog.hours ? `<span class="text-xs text-base-content/50">· ${prog.hours} hrs</span>` : ''}`;

  // Progress
  const total = prog.req_total;
  const done  = prog.req_submitted;
  const pct   = total > 0 ? Math.round((done / total) * 100) : 0;
  document.getElementById('req-modal-bar').value  = pct;
  document.getElementById('req-modal-rate').textContent = `${done} / ${total} (${pct}%)`;

  // Tab counts
  document.getElementById('tab-count-all').textContent       = _currentRequirements.length;
  document.getElementById('tab-count-submitted').textContent = _currentRequirements.filter(r => r.status === 'Approved').length;
  document.getElementById('tab-count-pending').textContent   = _currentRequirements.filter(r => r.status !== 'Approved').length;

  // Reset tab active state
  document.querySelectorAll('#req_modal [role=tab]').forEach(t => t.classList.remove('tab-active'));
  document.querySelector('#req_modal [role=tab]').classList.add('tab-active');

  renderRequirements('all');
  document.getElementById('req_modal').showModal();
  lucide.createIcons();
}

function filterReqs(filter, tabEl) {
  _currentFilter = filter;
  document.querySelectorAll('#req_modal [role=tab]').forEach(t => t.classList.remove('tab-active'));
  tabEl.classList.add('tab-active');
  renderRequirements(filter);
}

function renderRequirements(filter) {
  let reqs = _currentRequirements;
  if (filter === 'submitted') reqs = reqs.filter(r => r.status === 'Approved');
  if (filter === 'pending')   reqs = reqs.filter(r => r.status !== 'Approved');

  const listEl = document.getElementById('req-list');
  listEl.innerHTML = '';

  if (!reqs.length) {
    const msgs = {
      all:       ['inbox',          'No requirements recorded for this program.'],
      submitted: ['check-circle-2', 'No approved requirements yet.'],
      pending:   ['party-popper',   'All requirements have been approved!'],
    };
    const [icon, msg] = msgs[filter] || msgs.all;
    listEl.innerHTML = `
      <div class="py-14 text-center text-base-content/30">
        <i data-lucide="${icon}" class="w-10 h-10 mx-auto mb-3 opacity-40"></i>
        <p class="text-sm">${msg}</p>
      </div>`;
    lucide.createIcons();
    return;
  }

  reqs.forEach(req => {
    const sc  = SUB_STATUS[req.status] || SUB_STATUS['Pending'];
    const due = req.month_due && req.day_due
      ? `Due: ${monthName(req.month_due)} ${req.day_due}`
      : '';

    // File link
    const fileLink = req.file_path
      ? `<a href="/storage/${esc(req.file_path)}" target="_blank"
            class="btn btn-xs btn-ghost gap-1 text-primary">
           <i data-lucide="paperclip" class="w-3 h-3"></i> View File
         </a>`
      : '';

    // Timestamps
    let timestamps = '';
    if (req.submitted_at) {
      timestamps += `<span class="text-xs text-base-content/40">Submitted: ${fmtDateTime(req.submitted_at)}</span>`;
    }
    if (req.reviewed_at) {
      timestamps += `<span class="text-xs text-base-content/40 ml-3">Reviewed: ${fmtDateTime(req.reviewed_at)}${req.reviewed_by ? ' by ' + esc(req.reviewed_by) : ''}</span>`;
    }

    // Remarks
    const remarks = req.remarks
      ? `<div class="mt-2 text-xs bg-base-200 rounded-lg px-3 py-2 text-base-content/60 italic">
           <i data-lucide="message-square" class="w-3 h-3 inline mr-1 align-middle"></i>${esc(req.remarks)}
         </div>`
      : '';

    listEl.innerHTML += `
      <div class="px-5 py-4 hover:bg-base-50 transition">
        <div class="flex items-start gap-3">
          {{-- Status icon --}}
          <div class="mt-0.5 shrink-0">
            <div class="w-8 h-8 rounded-full flex items-center justify-center ${sc.iconBg}">
              <i data-lucide="${sc.icon}" class="w-4 h-4"></i>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-0.5">
              <span class="font-semibold text-sm">${esc(req.title)}</span>
              <span class="badge badge-xs ${sc.badge} font-semibold">${sc.label}</span>
              ${req.required === 'yes' ? '<span class="badge badge-xs badge-outline badge-error">Required</span>' : ''}
            </div>
            ${req.description ? `<p class="text-xs text-base-content/50 mb-1">${esc(req.description)}</p>` : ''}
            <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-base-content/40">
              ${due ? `<span><i data-lucide="calendar" class="w-3 h-3 inline mr-0.5 align-middle"></i>${due}</span>` : ''}
              ${timestamps}
            </div>
            ${remarks}
          </div>
          <div class="shrink-0">${fileLink}</div>
        </div>
      </div>`;
  });

  lucide.createIcons();
}

// ── Utilities ─────────────────────────────────────────────────────────────────
function esc(str) {
  const d = document.createElement('div');
  d.textContent = str ?? '';
  return d.innerHTML;
}

function fmtDate(str) {
  if (!str) return '';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
}

function fmtDateTime(str) {
  if (!str) return '';
  const d = new Date(str);
  return isNaN(d) ? str : d.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function monthName(n) {
  return ['', 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][n] || n;
}

function exportProfile() {
  alert('Export coming soon!');
}

// ── Demo auto-open (remove in production) ─────────────────────────────────────
// openParticipantProfile('EMP-2024-0451');

lucide.createIcons();
</script>
