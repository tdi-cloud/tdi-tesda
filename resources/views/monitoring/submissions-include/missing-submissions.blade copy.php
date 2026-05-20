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
  .btn-missing:hover { opacity:.88; transform:translateY(-1px); box-shadow: 0 4px 14px var(--accent-ring); }
  .btn-missing:active { transform:translateY(0); opacity:1; }

  dialog.modal-missing {
    background: transparent;
    border: none;
    outline: none;
    padding: 0;
    max-width: 100vw;
    width: 100%;
    max-height: 100vh;
  }
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
    width: min(900px, 95vw);
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
    display: flex;
    gap: 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
    background: var(--stripe);
  }
  .stat-item { display: flex; align-items: center; gap: .4rem; font-size: .75rem; }
  .stat-dot { width: .5rem; height: .5rem; border-radius: 50%; }
  .stat-label { color: var(--text-muted); }
  .stat-count { font-weight: 600; color: var(--text-primary); font-variant-numeric: tabular-nums; font-family: 'DM Mono', monospace; }
 
  /* ── TABLE ── */
  .table-wrap { overflow-x: auto; max-height: 420px; overflow-y: auto; }
  .table-wrap::-webkit-scrollbar { width: 4px; height: 4px; }
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
  table.missing-table tbody tr:hover { background: var(--stripe); }
  table.missing-table tbody td {
    padding: .75rem 1rem;
    font-size: .8125rem;
    color: var(--text-primary);
    vertical-align: middle;
  }
 
  /* ── EMPLOYEE CELL ── */
  .emp-name { font-weight: 500; }
  .emp-code { font-family: 'DM Mono', monospace; font-size: .6875rem; color: var(--text-muted); margin-top: .125rem; }
 
  /* ── BATCH CHIP ── */
  .batch-chip {
    display: inline-block;
    padding: .2rem .55rem;
    border-radius: .3rem;
    background: var(--border-subtle);
    color: var(--text-muted);
    font-size: .6875rem;
    font-weight: 500;
    letter-spacing: .02em;
  }
 
  /* ── REQUIREMENT ── */
  .req-text { font-size: .8125rem; color: var(--text-primary); }
 
  /* ── DUE DATE ── */
  .due-date { font-family: 'DM Mono', monospace; font-size: .75rem; color: var(--text-muted); }
 
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
 
  .badge-dot { width: .375rem; height: .375rem; border-radius: 50%; flex-shrink: 0; }
  .badge-overdue .badge-dot { background: #ef4444; }
  .badge-pending .badge-dot  { background: #f59e0b; }
 
  /* ── EMPTY / LOADING STATE ── */
  .state-row td { padding: 3rem 1rem !important; text-align: center; color: var(--text-muted); font-size: .875rem; }
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
  .btn-close-footer {
    padding: .45rem 1rem;
    border-radius: .4rem;
    border: 1px solid var(--border-subtle);
    background: transparent;
    color: var(--text-primary);
    font-size: .8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: background .15s;
    font-family: inherit;
  }
  .btn-close-footer:hover { background: var(--border-subtle); }
 
  /* ── THEME TOGGLE (demo only) ── */
  .theme-toggle {
    position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999;
    display: flex; align-items: center; gap: .5rem;
    background: var(--surface-card);
    border: 1px solid var(--border-subtle);
    border-radius: 2rem;
    padding: .3rem .75rem;
    cursor: pointer;
    font-size: .75rem;
    color: var(--text-muted);
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
    transition: background .2s, border-color .2s;
    font-family: inherit;
  }
  .theme-toggle:hover { color: var(--text-primary); }
 
  /* ── DEMO CENTERING ── */
  .demo-stage {
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 1rem;
  }
  .demo-label { font-size: .75rem; color: var(--text-muted); }
 
  /* ── MODAL WRAPPER CENTERING ── */
  dialog.modal-missing {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    inset: 0;
    height: 100vh;
  }
  dialog.modal-missing:not([open]) { display: none; }
</style>

<button
    class="btn-missing"
    onclick="showMissingModal()">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    View Missing Submissions
  </button>


<dialog  class="modal modal-missing fade" id="missingSubmissionModal">

    <div class="modal-box modal-card modal-xl">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Missing Requirements
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <div class="modal-body">

                <div class="row mb-3">

                    <!-- SEARCH -->
                    <div class="col-md-6">

                        <input
                            type="text"
                            id="searchInput"
                            class="form-control"
                            placeholder="Search employee..."
                        >

                    </div>

                    <!-- BATCH FILTER -->
                    <div class="col-md-4">

                        <select
                            id="batchFilter"
                            class="form-select"
                        >

                            <option value="all">
                                All Batches
                            </option>

                            @foreach($batches as $batch)

                                <option value="{{ $batch->id }}">
                                    {{ $batch->batch }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <select
                            id="requirementFilter"
                            class="form-select"
                        >

                            <option value="all">
                                All Requirements
                            </option>

                            @foreach($requirements as $requirement)

                                <option value="{{ $requirement->id }}">
                                    {{ $requirement->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                </div>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                            <tr>
                                <th>Employee</th>
                                <th>Batch</th>
                                <th>Requirement</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody id="missingSubmissionTable">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</dialog>


<script>

let searchTimeout;

function showMissingModal(){
    loadMissingSubmissions();
    missingSubmissionModal.showModal();
}

function loadMissingSubmissions()
{
    let search = $('#searchInput').val();
    let batch_id = $('#batchFilter').val();
    let requirement_id = $('#requirementFilter').val();

    $.ajax({

        url: '/programs/{{ $myprogram->program_code }}/missing-submissions',

        type: 'GET',

        data: {
            search: search,
            batch_id: batch_id,
            requirement_id: requirement_id
        },

        beforeSend: function () {

            $('#missingSubmissionTable').html(`
                <tr>
                    <td colspan="5" class="text-center">
                        Loading...
                    </td>
                </tr>
            `);

        },

        success: function (response) {

            let rows = '';

            if (response.length === 0) {

                rows = `
                    <tr>
                        <td colspan="5" class="text-center">
                            No missing submissions found.
                        </td>
                    </tr>
                `;

            } else {

                response.forEach(item => {

                    rows += `
                        <tr>

                            <td>
                                ${item.employee}
                                <br>
                                <small>${item.empcode}</small>
                            </td>

                            <td>${item.batch}</td>

                            <td>${item.requirement}</td>

                            <td>${item.due_date ?? '-'}</td>

                            <td>

                                ${
                                    item.is_overdue
                                    ?
                                    `<span class="badge bg-danger">
                                        Overdue
                                    </span>`
                                    :
                                    `<span class="badge bg-warning">
                                        Pending
                                    </span>`
                                }

                            </td>

                        </tr>
                    `;

                });

            }

            $('#missingSubmissionTable').html(rows);

        }

    });
}

$('#requirementFilter').on('change', function () {

    loadMissingSubmissions();

});

$('#searchInput').on('keyup', function () {

    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(function () {

        loadMissingSubmissions();

    }, 500);

});

$('#batchFilter').on('change', function () {

    loadMissingSubmissions();

});

</script>