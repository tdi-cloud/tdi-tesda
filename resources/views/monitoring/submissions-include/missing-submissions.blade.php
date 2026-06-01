<style>
  [data-theme="light"] {
    --surface:       #f8f7f4;
    --surface-card:  #ffffff;
    --border-subtle: #e8e5e0;
    --text-primary:  #1a1814;
    --text-muted:    #7a7570;
    --accent:        #c0392b;
    --accent-soft:   #fdf1f0;
    --accent-ring:   rgba(192,57,43,.18);
    --stripe:        #fafaf8;
  }
  [data-theme="dark"] {
    --surface:       #131211;
    --surface-card:  #1e1c1a;
    --border-subtle: #2e2b27;
    --text-primary:  #f0ede8;
    --text-muted:    #7a7570;
    --accent:        #e05c4b;
    --accent-soft:   #2a1a18;
    --accent-ring:   rgba(224,92,75,.22);
    --stripe:        #1a1815;
  }

  .btn-missing {
    background: var(--accent);
    color: #fff;
    border: none;
    font-weight: 600;
    letter-spacing: .01em;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .6rem 1.25rem;
    border-radius: .5rem;
    font-size: .875rem;
    cursor: pointer;
    box-shadow: 0 2px 8px var(--accent-ring), 0 1px 2px rgba(0,0,0,.08);
    transition: opacity .15s, transform .1s, box-shadow .15s;
  }
  .btn-missing:hover  { opacity:.88; transform:translateY(-1px); box-shadow: 0 4px 14px var(--accent-ring); }
  .btn-missing:active { transform:translateY(0); opacity:1; }

  dialog.modal-missing {
    background: transparent;
    border: none;
    outline: none;
    padding: 0;
    max-width: 100vw;
    width: 100%;
    max-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    inset: 0;
    height: 100vh;
  }
  dialog.modal-missing:not([open]) { display: none; }
  dialog.modal-missing::backdrop {
    background: rgba(10,8,6,.55);
    backdrop-filter: blur(3px);
  }

  /* ── MODAL CARD ── */
  .modal-card {
    background: var(--surface-card);
    border: 1px solid var(--border-subtle);
    border-radius: 1rem;
    box-shadow: 0 24px 64px rgba(0,0,0,.16), 0 4px 16px rgba(0,0,0,.08);
    width: min(960px, 95vw);
    margin: auto;
    overflow: hidden;
    animation: slideUp .22s cubic-bezier(.25,.46,.45,.94);
  }
  @keyframes slideUp {
    from { opacity:0; transform:translateY(18px) scale(.98); }
    to   { opacity:1; transform:translateY(0)    scale(1);   }
  }

  /* ── HEADER ── */
  .modal-header {
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modal-header-left { display: flex; align-items: center; gap: .75rem; }
  .header-icon {
    width: 2.25rem; height: 2.25rem;
    background: var(--accent-soft);
    border-radius: .5rem;
    display: flex; align-items: center; justify-content: center;
    color: var(--accent);
    flex-shrink: 0;
  }
  .modal-title {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: -.01em;
    color: var(--text-primary);
  }
  .modal-subtitle {
    font-size: .75rem;
    color: var(--text-muted);
    margin-top: .1rem;
  }
  .btn-close-modal {
    width: 2rem; height: 2rem;
    border-radius: .4rem;
    border: 1px solid var(--border-subtle);
    background: transparent;
    color: var(--text-muted);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
    flex-shrink: 0;
  }
  .btn-close-modal:hover { background: var(--border-subtle); color: var(--text-primary); }

  /* ── FILTER ROW ── */
  .filter-row {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
    align-items: center;
  }
  .search-wrap {
    position: relative;
    flex: 1 1 220px;
  }
  .search-wrap svg {
    position: absolute; left: .75rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); pointer-events: none;
  }
  .filter-input, .filter-select {
    width: 100%;
    background: var(--surface);
    border: 1px solid var(--border-subtle);
    border-radius: .5rem;
    color: var(--text-primary);
    font-size: .8125rem;
    height: 2.25rem;
    padding: 0 .75rem;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
  }
  .filter-input { padding-left: 2.25rem; }
  .filter-input::placeholder { color: var(--text-muted); }
  .filter-input:focus, .filter-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-ring);
  }
  .filter-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%237a7570' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right .75rem center;
    padding-right: 2rem;
    cursor: pointer;
  }
  .filter-select-wrap { flex: 1 1 160px; }

  /* ── BODY ── */
  .modal-body { padding: 0; }

  /* ── STATS ROW ── */
  .stats-row {
    padding: .875rem 1.5rem;
    display: hidden;
    gap: 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--stripe);
  }
  .stat-item { display: flex; align-items: center; gap: .4rem; font-size: .75rem; }
  .stat-dot  { width: .5rem; height: .5rem; border-radius: 50%; }
  .stat-label { color: var(--text-muted); }
  .stat-count {
    font-weight: 600;
    color: var(--text-primary);
    font-variant-numeric: tabular-nums;
    font-family: 'DM Mono', monospace;
  }

  /* ── TABLE ── */
  .table-wrap { overflow-x: auto; max-height: 420px; overflow-y: auto; }
  .table-wrap::-webkit-scrollbar       { width: 4px; height: 4px; }
  .table-wrap::-webkit-scrollbar-track { background: transparent; }
  .table-wrap::-webkit-scrollbar-thumb { background: var(--border-subtle); border-radius: 4px; }

  table.missing-table { width: 100%; border-collapse: collapse; }
  table.missing-table thead th {
    padding: .625rem 1rem;
    text-align: left;
    font-size: .6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-muted);
    background: var(--stripe);
    border-bottom: 1px solid var(--border-subtle);
    white-space: nowrap;
    position: sticky; top: 0; z-index: 1;
  }
  table.missing-table tbody tr {
    border-bottom: 1px solid var(--border-subtle);
    transition: background .12s;
  }
  table.missing-table tbody tr:last-child { border-bottom: none; }
  table.missing-table tbody tr:hover     { background: var(--stripe); }
  table.missing-table tbody td {
    padding: .75rem 1rem;
    font-size: .8125rem;
    color: var(--text-primary);
    vertical-align: middle;
  }

  /* ── BADGES ── */
  .badge-overdue {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25rem .625rem;
    border-radius: 2rem;
    background: #fef2f2;
    color: #b91c1c;
    font-size: .6875rem; font-weight: 600; letter-spacing: .02em;
  }
  [data-theme="dark"] .badge-overdue { background: #2d1414; color: #f87171; }

  .badge-pending {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25rem .625rem;
    border-radius: 2rem;
    background: #fffbeb;
    color: #92400e;
    font-size: .6875rem; font-weight: 600; letter-spacing: .02em;
  }
  [data-theme="dark"] .badge-pending { background: #27200a; color: #fbbf24; }

  /* ── LOADING SHIMMER ── */
  .loading-shimmer {
    width: 140px; height: .75rem; border-radius: .25rem;
    background: linear-gradient(90deg, var(--border-subtle) 25%, var(--stripe) 50%, var(--border-subtle) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
    margin: 0 auto;
  }
  @keyframes shimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }

  /* ── FOOTER ── */
  .modal-footer {
    padding: .875rem 1.5rem;
    border-top: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--stripe);
  }
  .footer-hint { font-size: .6875rem; color: var(--text-muted); }
</style>


{{-- ── TRIGGER BUTTON ── --}}
<button class="btn-missing" onclick="showMissingModal()">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
  </svg>
  View Missing Submissions
</button>


{{-- ── MODAL ── --}}
<dialog class="modal-missing" id="missingSubmissionModal">
  <div class="modal-card">

    {{-- Header --}}
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="header-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
        </div>
        <div>
          <div class="modal-title">Missing Requirements</div>
          <div class="modal-subtitle">Employees with pending or overdue submissions</div>
        </div>
      </div>
      <button class="btn-close-modal" onclick="document.getElementById('missingSubmissionModal').close()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    {{-- Filters --}}
    <div class="filter-row">
      <div class="search-wrap">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input
          type="text"
          id="searchInput"
          class="filter-input"
          placeholder="Search employee…">
      </div>

      <div class="filter-select-wrap">
        <select id="batchFilter" class="filter-select">
          <option value="all">All Batches</option>
          @foreach($batches as $batch)
            <option value="{{ $batch->id }}">{{ $batch->batch }}</option>
          @endforeach
        </select>
      </div>

      <div class="filter-select-wrap">
        <select id="requirementFilter" class="filter-select">
          <option value="all">All Requirements</option>
          @foreach($requirements as $requirement)
            <option value="{{ $requirement->id }}">{{ $requirement->title }}</option>
          @endforeach
        </select>
      </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row hidden" id="statsRow">
      <div class="stat-item">
        <span class="stat-dot" style="background:#ef4444"></span>
        <span class="stat-label">Overdue</span>
        <span class="stat-count" id="countOverdue">0</span>
      </div>
      <div class="stat-item">
        <span class="stat-dot" style="background:#f59e0b"></span>
        <span class="stat-label">Pending</span>
        <span class="stat-count" id="countPending">0</span>
      </div>
      <div class="stat-item">
        <span class="stat-dot" style="background:var(--text-muted)"></span>
        <span class="stat-label">Total</span>
        <span class="stat-count" id="countTotal">0</span>
      </div>
    </div>

    {{-- Table --}}
    <div class="modal-body">
      <div class="table-wrap">
        <table class="missing-table">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Office</th>
              <th>Batch</th>
              <th>Requirement</th>
              <th>Due Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="missingSubmissionTable">
            {{-- rows injected by JS --}}
          </tbody>
        </table>
      </div>
    </div>

    {{-- Footer --}}
    <div class="modal-footer">
      <span class="footer-hint">Last refreshed just now</span>
      <button class="btn-missing" onclick="downloadMissingCsv()" style="font-size:.75rem; padding:.45rem 1rem;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Download CSV
      </button>
    </div>

  </div>
</dialog>


<script>

  let searchTimeout;
  let lastMissingData = [];

  function showMissingModal() {
      loadMissingSubmissions();
      document.getElementById('missingSubmissionModal').showModal();
  }

  function loadMissingSubmissions() {
      const search         = $('#searchInput').val();
      const batch_id       = $('#batchFilter').val();
      const requirement_id = $('#requirementFilter').val();

      $.ajax({
          url:  '/programs/{{ $myprogram->program_code }}/missing-submissions',
          type: 'GET',
          data: { search, batch_id, requirement_id },

          beforeSend: function () {
              lastMissingData = [];
              $('#missingSubmissionTable').html(`
                  <tr>
                      <td colspan="6" class="text-center">
                          <div class="loading-shimmer"></div>
                      </td>
                  </tr>
              `);
          },

          success: function (response) {
              lastMissingData = response;

              let rows = '';

              if (response.length === 0) {
                  rows = `
                      <tr>
                          <td colspan="6" class="text-center" style="padding:3rem 1rem; color:var(--text-muted);">
                              No missing submissions found.
                          </td>
                      </tr>
                  `;
              } else {
                  response.forEach(item => {
                      rows += `
                          <tr>
                              <td>
                                  <div style="font-weight:500;">${item.employee}</div>
                                  <div style="font-size:.6875rem; color:var(--text-muted); font-family:'DM Mono',monospace;">${item.empcode}</div>
                              </td>
                              <td>${item.office ?? '-'}</td>
                              <td>${item.batch}</td>
                              <td>${item.requirement}</td>
                              <td style="font-family:'DM Mono',monospace; font-size:.75rem;">${item.due_date ?? '-'}</td>
                              <td>
                                  ${item.is_overdue
                                      ? `<span class="badge-overdue">
                                            <span style="width:.375rem;height:.375rem;border-radius:50%;background:#ef4444;flex-shrink:0;display:inline-block;"></span>
                                            Overdue
                                         </span>`
                                      : `<span class="badge-pending">
                                            <span style="width:.375rem;height:.375rem;border-radius:50%;background:#f59e0b;flex-shrink:0;display:inline-block;"></span>
                                            Pending
                                         </span>`
                                  }
                              </td>
                          </tr>
                      `;
                  });
              }

              $('#missingSubmissionTable').html(rows);
          },

          error: function () {
              $('#missingSubmissionTable').html(`
                  <tr>
                      <td colspan="6" class="text-center" style="padding:3rem 1rem; color:var(--text-muted);">
                          Failed to load data. Please try again.
                      </td>
                  </tr>
              `);
          }
      });
  }

  function downloadMissingCsv() {
      if (!lastMissingData.length) {
          alert('No data to export.');
          return;
      }

      const headers = ['Employee', 'Employee Code', 'Office', 'Batch', 'Requirement', 'Due Date', 'Status'];

      const csvRows = lastMissingData.map(item => [
          escapeCsvField(item.employee),
          escapeCsvField(item.empcode),
          escapeCsvField(item.office),
          escapeCsvField(item.batch),
          escapeCsvField(item.requirement),
          escapeCsvField(item.due_date ?? '-'),
          item.is_overdue ? 'Overdue' : 'Pending'
      ].join(','));

      const csvContent = [headers.join(','), ...csvRows].join('\n');

      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url  = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href     = url;
      link.download = `missing_submissions_${new Date().toISOString().slice(0, 10)}.csv`;
      link.click();
      URL.revokeObjectURL(url);
  }

  function escapeCsvField(value) {
      if (value == null) return '';
      const str = String(value);
      if (str.includes(',') || str.includes('"') || str.includes('\n')) {
          return '"' + str.replace(/"/g, '""') + '"';
      }
      return str;
  }

  // Event listeners
  $('#requirementFilter').on('change', loadMissingSubmissions);
  $('#batchFilter').on('change', loadMissingSubmissions);
  $('#searchInput').on('keyup', function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(loadMissingSubmissions, 500);
  });

</script>