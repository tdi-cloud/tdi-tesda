<input type="hidden" id="programCode" value="{{ $myprogram->program_code }}">
@include('monitoring.create-submission-modal')
<style>
    /* Per Page Selector */
    .per-page-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }
    .per-page-wrap label {
      font-size: 0.85rem;
      color: var(--muted);
    }
    .per-page-select {
      padding: 10px 14px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.85rem;
      outline: none;
      cursor: pointer;
      transition: border-color var(--transition);
    }
    .per-page-select:focus { border-color: var(--accent); }
 
    /* Total Results Badge */
    .results-badge {
      font-size: 0.82rem;
      color: var(--muted);
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 6px 14px;
      white-space: nowrap;
    }
    .results-badge span {
      color: var(--accent);
      font-weight: 600;
    }
 

    .batch-card {
      
      transition: border-color var(--transition);
      animation: fadeUp 0.4s ease both;
    }
 
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
 
    /* Batch Header */
    .batch-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
      user-select: none;
      gap: 12px;
    }
    .batch-header:hover { background: rgba(255,255,255,0.02); }
 
    .batch-left { display: flex; align-items: center; gap: 14px; }
 
    .batch-number {
      width: 42px; height: 42px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1rem;
      color: #3b1f1f;
      flex-shrink: 0;
    }

    .batch-right { display: flex; align-items: center; gap: 10px; }
 
    .badge {
      font-size: 0.75rem;
      font-weight: 500;
      padding: 4px 10px;
      border-radius: 20px;
    }
    .badge-blue  { background: rgba(79,142,247,0.12); color: var(--accent); }
    .badge-green { background: rgba(52,211,153,0.12); color: var(--success); }
    .badge-red   { background: rgba(248,113,113,0.12); color: var(--danger); }
 
    .toggle-icon {
      color: var(--muted);
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }
    .toggle-icon.open { transform: rotate(180deg); }
 
    /* Batch Body */
    .batch-body {
      display: none;
    }
    .batch-body.open { display: block; }
 

 
    /* Status badge */
    .status {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.78rem;
      font-weight: 500;
      padding: 3px 10px;
      border-radius: 20px;
    }
    .status::before {
      content: '';
      width: 6px; height: 6px;
      border-radius: 50%;
      background: currentColor;
    }
    .status-active   { background: rgba(52,211,153,0.1);  color: var(--success); }
    .status-inactive { background: rgba(248,113,113,0.1); color: var(--danger); }
    .status-pending  { background: rgba(251,191,36,0.1);  color: #fbbf24; }
 
    /* ─── Pagination ─────────────────────────────────────────── */
    .pagination {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }
    .pagination-info {
      font-size: 0.82rem;
      color: var(--muted);
    }
    .pagination-info strong { color: var(--text); }
    .pagination-buttons {
      display: flex;
      gap: 4px;
    }
    .page-btn {
      min-width: 34px; height: 34px;
      padding: 0 10px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.83rem;
      cursor: pointer;
      transition: all var(--transition);
      display: flex; align-items: center; justify-content: center;
    }
    .page-btn:hover:not(:disabled) {
      border-color: var(--accent);
      color: var(--accent);
    }
    .page-btn.active {
      background: var(--accent);
      border-color: var(--accent);
      color: #049ee6;
      font-weight: 600;
    }
    .page-btn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
    }
 
  
    @keyframes spin { to { transform: rotate(360deg); } }
 
    /* ─── Empty State ────────────────────────────────────────── */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
    }
    .empty-state svg { opacity: 0.3; margin-bottom: 12px; }
    .empty-state p { font-size: 0.9rem; }
 
    /* ─── No Results (search) ────────────────────────────────── */
    .no-results {
      text-align: center;
      padding: 30px;
      color: var(--muted);
      font-size: 0.88rem;
    }
 
    /* ─── Highlighted Search Text ────────────────────────────── */
    mark {
      background: rgba(79,142,247,0.25);
      color: var(--accent);
      border-radius: 3px;
      padding: 0 2px;
    }
 
    /* ─── Error Banner ───────────────────────────────────────── */
    .error-banner {
      background: rgba(248,113,113,0.08);
      border: 1px solid rgba(248,113,113,0.25);
      border-radius: var(--radius-sm);
      padding: 14px 18px;
      color: var(--danger);
      font-size: 0.88rem;
      margin-bottom: 20px;
      display: none;
    }
    .error-banner.show { display: block; }
 
    
  </style>

<section class="p-5 space-y-4">

  <div class="page-header flex gap-4 items-center">
    <h1 class="poppins-semibold text-slate-700 dark:text-yellow-500">Program Batches</h1>
    <p class="poppins-regular text-slate-500 text-sm">Browse all batches and their enrolled participants.</p>
  </div>
 
  <div class="controls flex gap-4 items-center">
 
    <!-- Search -->
    <div class="search-wrap flex-1">

        <label class="input w-full rounded-lg bg-slate-200 dark:bg-slate-700 dark:border-slate-600 poppins-regular text-sm">
        <svg class="h-[1em]  opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <g
            stroke-linejoin="round"
            stroke-linecap="round"
            stroke-width="2.5"
            fill="none"
            stroke="currentColor"
            >
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.3-4.3"></path>
            </g>
        </svg>
        
        <input type="search" class="grow w-full" placeholder="Search participant name, code..." id="searchInput"/>
        </label>


    </div>


 
    <!-- Per Page -->
    <div class="per-page-wrap">
      <label for="perPageSelect">Show</label>
      <select class="select rounded-lg bg-slate-200 dark:bg-slate-700 dark:border-slate-600 poppins-regular text-sm" id="perPageSelect">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        </select>

      <label class="text-sm poppins-regular">per page</label>
    </div>
 
    <!-- Result Count -->
    <div class="results-badge" id="resultsBadge">
      <span class="loading loading-bars loading-sm"></span>
    </div>
 
  </div>
 
  <!-- ─── Error Banner ────────────────────────────────────────── -->
  <div class="container">
    <div class="error-banner" id="errorBanner"></div>
  </div>
 
  <!-- ─── Batch List ──────────────────────────────────────────── -->
  <div class=" w-full flex-1 space-y-4" id="batchContainer">
    <!-- Loading Spinner (shown on first load) -->
    <div class="spinner-wrap" id="loadingSpinner">
      <div class="spinner"></div>
    </div>
  </div>
 
 </section>



  <script>
function editBatchModal(id){
  create_batch_modal.showModal();
  $('#batch_submit_btn').addClass('hidden')
  $('#batch_edit_btn').removeClass('hidden')
  $('#createBatchTitle').html('Edit Batch');
  $('#batch_edit_btn').addClass('hidden');

  $.ajax({
    url: `/batch/${id}/edit`, // your JSON file or API endpoint
    type: 'GET',
    dataType: 'json',
    success: function(r) {
        if(r.data.length >= 1){
          $('#idForEditBatch').val(r.data[0].id);   
          $('#batch_name').val(r.data[0].batch);    
          $('#batch_status').val(r.data[0].status);    
          $('#batch_modality').val(r.data[0].modality);    
          $('#batch_venue').val(r.data[0].venue);    
          $('#batch_date_start').val(r.data[0].date_start);    
          $('#batch_date_end').val(r.data[0].date_end);    
          $('#batch_time_start').val(r.data[0].time_start);    
          $('#batch_time_end').val(r.data[0].time_end);    
          $('#batch_hours').val(r.data[0].hours);    
          $('#batch_days').val(r.data[0].days);    
          $('#batch_edit_btn').removeClass('hidden'); 
        }
        
    },
    error: function(xhr, status, error) {
        console.error('Error:', error);
    }
});

}

function deleteParticipantModal(id){
  lucide.createIcons();
  $('#delete_part_id').val(id);
  delete_part_modal.showModal();
}

function deleteBatchModal(id){
  lucide.createIcons();
  $('#delete_batch_id').val(id);
  delete_batch_modal.showModal();
}


function clearPartModal(id){
  lucide.createIcons();
  $('#clear_part_id').val(id);
  clear_part_modal.showModal();
}

function clearParticipants(){
  $('#yes_clear_part_btn').prop('disabled', true);
  const id = $('#clear_part_id').val();
  fetch(`/participants/${id}/clear`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
      delete_part_modal.close();
      $('#yes_clear_part_btn').prop('disabled', false);
      if(data.success){
          fetchBatches();
          showToast('Participants Cleared', type = 'success')
          clear_part_modal.close();
      }else console.log(data);

    
        
    })
    .catch(error => console.error('Error:', error));
}

function deleteBatch(){
  $('#yes_delete_batch_btn').prop('disabled', true);
  const id = $('#delete_batch_id').val();
  fetch(`/batch/${id}/delete`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
      delete_part_modal.close();
      $('#yes_delete_batch_btn').prop('disabled', false);
      if(data.success){
          fetchBatches();
          showToast('Batch Deleted', type = 'success')
          delete_batch_modal.close();
      }else console.log(data);

    
        
    })
    .catch(error => console.error('Error:', error));
}


function deleteParticipant() {
  $('#yes_del_part_btn').prop('disabled', true);
  const id = $('#delete_part_id').val();

  fetch(`/participants/${id}/delete`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
      delete_part_modal.close();
      $('#yes_del_part_btn').prop('disabled', false);
      if(data.success){
          fetchBatches();
          showToast('Participant deleted successfully', type = 'success')
      }else console.log(data);

    
        
    })
    .catch(error => console.error('Error:', error));
}






  const state = {
    batches     : [],   // All batches fetched from the API
    searchQuery : '',   // Current search string
    perPage     : 10,   // Participants per page per batch
    pages       : {},   // { batchId: currentPage } — tracks page per batch
    openBatches : {},   // { batchId: true/false }  — tracks collapse state
    loading     : true,
  };
 
  const searchInput    = document.getElementById('searchInput');
  const perPageSelect  = document.getElementById('perPageSelect');
  const batchContainer = document.getElementById('batchContainer');
  const loadingSpinner = document.getElementById('loadingSpinner');
  const resultsBadge   = document.getElementById('resultsBadge');
  const errorBanner    = document.getElementById('errorBanner');

      const programCode = document.getElementById('programCode').value;
  async function fetchBatches() {
    try {
      showError('');                          // Clear any previous error
      loadingSpinner.style.display = 'flex';  // Show spinner
 
      const response = await fetch(`/batches/${programCode}/participants`, {
        method  : 'GET',
        headers : {
          'Content-Type' : 'application/json',
          'Accept'       : 'application/json',
        },
      });
 
      // If the server returned an error status
      if (!response.ok) {
        throw new Error(`Server error: ${response.status} ${response.statusText}`);
      }
 
      const json = await response.json();
      // console.log(json);
 
      // Support both { data: [...] } and plain array responses
      state.batches = Array.isArray(json) ? json : json.data ?? [];
 
      // Initialize each batch to page 1 and collapsed by default
      state.batches.forEach(batch => {
        state.pages[batch.id]       = 1;
        state.openBatches[batch.id] = true;
      });
 
    } catch (error) {
      console.error('Fetch error:', error);
      showError(`Failed to load batches. ${error.message}`);
 
      // ── DEMO DATA (remove this block when connected to real API) ──
      state.batches = generateDemoData();
      state.batches.forEach(batch => {
        state.pages[batch.id]       = 1;
        state.openBatches[batch.id] = true; // Open first batch by default
      });
      state.openBatches[state.batches[0]?.id] = true;
      showError('⚠️ Using demo data — please check database connection');
      // ── END DEMO DATA ──
 
    } finally {
      state.loading = false;
      loadingSpinner.style.display = 'none'; // Hide spinner
      render(); // Draw everything
    }
  }
 
  /* ════════════════════════════════════════════════════════════
     FILTER — Filter participants by search query
     Applied per-batch on the participants array
     ════════════════════════════════════════════════════════════ */
  function filterParticipants(participants) {
    const q = state.searchQuery.trim().toLowerCase();
    if (!q) return participants;
 
    return participants.filter(p =>
      (p.employee.FIRSTNAME           || '').toLowerCase().includes(q) ||
      (p.employee.LASTNAME            || '').toLowerCase().includes(q) ||
      (p.employee['OFFICE/DIVISION']  || '').toLowerCase().includes(q) ||
      (p.empcode                      || '').toLowerCase().includes(q) 
    );
  }
 
  /* ════════════════════════════════════════════════════════════
     PAGINATE — Slice filtered participants for current page
     ════════════════════════════════════════════════════════════ */
  function paginate(participants, batchId) {
    const currentPage = state.pages[batchId] || 1;
    const start       = (currentPage - 1) * state.perPage;
    const end         = start + state.perPage;
    return {
      items      : participants.slice(start, end),
      total      : participants.length,
      totalPages : Math.ceil(participants.length / state.perPage),
      currentPage,
      start      : start + 1,
      end        : Math.min(end, participants.length),
    };
  }
 
  /* ════════════════════════════════════════════════════════════
     HIGHLIGHT — Wrap matched search text in <mark> tags
     ════════════════════════════════════════════════════════════ */
  function highlight(text) {
    const q = state.searchQuery.trim();
    if (!q || !text) return text ?? '';
 
    // Escape special regex characters in the search query
    const escaped = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex   = new RegExp(`(${escaped})`, 'gi');
    return String(text).replace(regex, '<mark>$1</mark>');
  }
 
  /* ════════════════════════════════════════════════════════════
     RENDER — Build and inject all batch cards into the DOM
     ════════════════════════════════════════════════════════════ */
  function render() {
    // Count total visible participants across all batches
    let totalVisible = 0;
    state.batches.forEach(b => {
      totalVisible += filterParticipants(b.participants || []).length;
    });
 
    // Update the results badge
    resultsBadge.innerHTML = state.searchQuery
      ? `<p class="poppins-regular"><span class="!text-blue-600">${totalVisible}</span> result${totalVisible !== 1 ? 's' : ''} found <p>`
      : `<p class="poppins-regular"><span class="!text-blue-600">${state.batches.length}</span> batch${state.batches.length !== 1 ? 'es' : ''} <p>`;
 
    // If no batches at all
    if (!state.batches.length) {
      batchContainer.innerHTML = `
        <div class="empty-state">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Z"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
          </svg>
          <p>No batches found.</p>
        </div>`;
      return;
    }
 
    // Build each batch card
    batchContainer.innerHTML = state.batches.map((batch, index) => {
      const filtered    = filterParticipants(batch.participants || []);
      const paged       = paginate(filtered, batch.id);
      const isOpen      = state.openBatches[batch.id];
      const batchStatus = (batch.status || 'active').toLowerCase();
     
 
      return `
        <div class="batch-card dark:bg-slate-800 dark:border-slate-600 border  rounded-2xl bg-white border-slate-300 radius-2xl overflow-hidden " style="animation-delay: ${index * 0.06}s">
 
          <!-- Batch Header (click to expand/collapse) -->
          <div class="batch-header" >
 
            <div class="batch-left flex-1 flex gap-3" >
              <div class="w-12 h-12 bg-violet-100 rounded-2xl flex items-center justify-center dark:bg-violet-400">
                <i class="fa-solid fa-layer-group text-violet-500 text-2xl dark:text-white"></i>
              </div>

              <!-- Batch number circle -->
              {{--<div class="batch-number">${index + 1}</div>--}}
 
              <!-- Batch name and program -->
              <div class="space-y-2 ">

                
                  <div class="flex gap-2 items-center">
                    <h2 class ="poppins-semibold ">${escapeHtml(batch.batch)}</h2>
                    <span class="badge  ${statusClass(batchStatus)}">
                    ${capitalize(batchStatus)}
                  </span>

                    <!-- Participant count badge -->
                  <span class="badge badge-soft badge-primary">
                    ${filtered.length} participant${filtered.length !== 1 ? 's' : ''}
                  </span>

                  </div>
                

                <div class="flex gap-2 items-center">

                  <p class="text-[13px] poppins-regular text-slate-500 dark:text-slate-200">
                    <i class="fa-regular fa-clock"></i>
                   ${batch.hours} hours
                  </p>


                  <p class="text-[13px] poppins-regular text-slate-500 dark:text-slate-200">
                    <i class="fa-regular fa-calendar text-sky-500"></i>
                   ${formatDate(batch.date_start)} – ${formatDate(batch.date_end)}
                  </p>

                  <p class="text-[13px] poppins-regular text-slate-500 dark:text-slate-200">
                    <i class="fa-solid fa-location-dot text-green-500"></i>
                    ${batch.venue}
                  </p>

                  
                </div>

                
              </div>

            </div>
 
            <div class="batch-right">
              
              <button onclick="editBatchModal(${batch.id})" class="btn  btn-sm poppins-semibold  rounded-2xl btn-default"><i class="fa-solid fa-pen"></i> Edit Batch</button>

              <button onclick="addParticipantsSelect(${batch.id})" class="btn btn-ghost btn-sm poppins-semibold bg-blue-600 rounded-2xl text-white"><i class="fa-regular fa-plus"></i> Add Participants</button>

              <div class="dropdown dropdown-end">
              <button tabindex="0" role="button" class="btn btn-default btn-sm poppins-semibold  rounded-2xl bg-indigo-500 text-white">Generate <i class="fa-solid fa-angle-down"></i></button>
              <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-80 p-2 shadow-sm">
                <li onclick="openDeclarationModal(${batch.id})"><a><i class="fa-regular fa-file-lines"></i> Declaration of Completers</a></li>
              </ul>
            </div>

              <button onclick="deleteBatchModal(${batch.id})" class="btn btn-xs btn-circle btn-error btn-soft rounded-full"><i class="fa-regular fa-trash-can"></i></button>
 
              <!-- Chevron icon --> 
              <!-- <svg onclick="toggleBatch(${batch.id})" class="toggle-icon ${isOpen ? 'open' : ''}" width="18" height="18"
                   fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m6 9 6 6 6-6"/>
              </svg> --> 

              
            </div>
          </div>
 
          <!-- Batch Body (participants table + pagination) -->
          <div class="batch-body ${isOpen ? 'open' : ''} border-t border-slate-200 dark:border-slate-600 !p-0">
 
            ${filtered.length === 0
              ? `<div class="no-results poppins-regular">
                   No participants match "<strong>${escapeHtml(state.searchQuery)}</strong>"
                 </div>`
              : `
              <!-- Participants Table -->

              <div class="grid grid-cols-7 items-center text-sm border-b border-slate-300 dark:border-slate-600 dark:text-slate-400 text-slate-700">

                  <div class="col-span-2 poppins-semibold p-2 pl-5">
                      <h1>Participant</h1>
                  </div>

                  <div class=" poppins-semibold p-2">
                      <h1>Office </h1>
                  </div>

                  <div class=" poppins-semibold p-2 text-center">
                      <h1>Sallary Grade</h1>
                  </div>

                  <div class=" poppins-semibold p-2 text-left ">
                      <h1>Attendance</h1>
                  </div>

                  <div class=" poppins-semibold p-2 text-left ">
                      <h1>Submission</h1>
                  </div>

                  <div class=" poppins-semibold p-2 text-center pr-5">
                      <h1 class="text-slate-400">Action</h1>
                  </div>
              </div>

              ${paged.items.map((p, i) => { 
                
                const canSubmit = p.attendance?.toLowerCase() !== 'absent';
                
                return `<div class="grid grid-cols-7 hover:bg-slate-100 dark:hover:bg-slate-600 items-center  border-b border-slate-300 dark:border-slate-600">


                    <div class="col-span-2 poppins-regular p-2 pl-5 flex items-center gap-2 " >
                        <img 
                            src="${`https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(p.employee.FIRSTNAME)}&backgroundColor=53CBF3`}" 
                            alt="${p.employee.FIRSTNAME}"
                            class="object-cover min-w-8 min-h-8 max-w-8 max-h-8 rounded-full overflow-hidden"
                            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(p.employee.FIRSTNAME)}&background=53CBF3&color=fff&size=64';">

                        <div>
                          <!-- Employee Fullname -->
                        <h1 class="leading-4 poppins-semibold text-sm whitespace-nowrap ">${p.employee.FIRSTNAME} ${p.employee.MI} ${p.employee.LASTNAME}</h1>
                        <p class="leading-5 poppins-regular text-xs text-slate-500 dark:text-slate-200">${highlight(p.empcode || '—')}</p>
                        </div>
                    </div>

                    <div class=" poppins-regular p-2">
                        <h1 class="poppins-regular text-sm text-slate-400">${p.employee['OFFICE/DIVISION']}</h1>
                    </div>



                    <div class=" poppins-regular p-2 text-center ">
                        <h1 class="poppins-regular  text-sm">${p.employee.SG}</h1>
                    </div>

                    <div class=" poppins-regular p-2 text-center flex gap-2 justify-left items-center">
                        <button onclick='setAtt(${JSON.stringify(p)})' class="btn btn-soft btn-sm btn-circle btn-primary dark:bg-indigo-500 text-white">
                          <i data-lucide="pencil" class="w-4 dark:text-white  text-sky-900 hover:text-white"></i>
                          </button>
                        <h1 class="poppins-bold  text-sm ${getAttendanceBadge(p.attendance)}">${p.attendance} • ${p.hours + 0}/${batch.hours}h</h1>

                    </div>


                    <div class="poppins-regular p-2 text-left space-y-1 flex gap-2 items-center">
                        
                        

                        ${canSubmit ? `
                            <button onclick="openSubmissionModal(${p.id}, ${batch.id})"
                                class="btn btn-sm btn-circle btn-success btn-soft ">
                                <i data-lucide="file-plus" class="w-5 text-green-500"></i>
                            </button>
                        ` : `
                            <span class="text-xs text-red-400"></span>
                        `}

                        <!-- submission progress -->
                        <div class="text-xs text-slate-500">
                            ${p.submitted_count ?? 0}/${p.required_count ?? 0} Submission
                        </div>
                    </div>
                    

                    <div class="poppins-regular p-2 text-center">
                        
                        
                        <!-- UP -->
                        <button onclick="moveParticipant(${p.id}, 'up', ${batch.id})"
                            class="btn btn-sm btn-ghost btn-circle">
                            <i class="fa-solid fa-angle-up"></i>
                        </button>

                        <!-- DOWN -->
                        <button onclick="moveParticipant(${p.id}, 'down', ${batch.id})"
                            class="btn btn-sm btn-ghost btn-circle">
                            <i class="fa-solid fa-angle-down"></i>
                        </button>

                        <button onclick="deleteParticipantModal(${p.id})" class="btn btn-xs btn-ghost btn-circle btn-error"><i class="fa-solid fa-trash-can"></i></button>

                        
                    </div>
                </div>
                      
              `}).join('')}

              <!-- Pagination Controls -->
              <div class="pagination px-5 py-3">
 
                <!-- Info text: "Showing 1–10 of 45" -->
                <div class="pagination-info poppins-regular">
                  Showing <strong>${paged.start}–${paged.end}</strong>
                  of <strong>${paged.total}</strong> participants

                  <button onclick="clearPartModal(${batch.id})" class="btn btn-soft rounded-2xl btn-xs btn-error"><i class="fa-solid fa-trash-can"></i>Clear Participants</button>
                </div>

                
 
                <!-- Page Buttons -->
                <div class="pagination-buttons">
 
                  <!-- Previous Button -->
                  <button class="page-btn"
                          onclick="changePage(${batch.id}, ${paged.currentPage - 1})"
                          ${paged.currentPage === 1 ? 'disabled' : ''}>
                    ‹
                  </button>
 
                  <!-- Numbered Page Buttons -->
                  ${buildPageButtons(paged.totalPages, paged.currentPage, batch.id)}
 
                  <!-- Next Button -->
                  <button class="page-btn"
                          onclick="changePage(${batch.id}, ${paged.currentPage + 1})"
                          ${paged.currentPage === paged.totalPages ? 'disabled' : ''}>
                    ›
                  </button>
 
                </div>
              </div>
            `}
          </div>
        </div>
      `;
    }).join('');
    lucide.createIcons();
  }
  
  function showExistingFile(path) {
    filePrompt.classList.add('hidden');
    fileInfo.classList.remove('hidden');
    fileInfo.classList.add('flex');

    fileName.textContent = path.split('/').pop();
    fileSize.textContent = 'Existing file';

    // optional: open file when clicked
    fileInfo.onclick = () => {
      window.open('/storage/' + path, '_blank');
    };
  }


  function setAtt(p){
      $('#participant_id').val(p.id);
      $('#batch_id').val(p.batch_id);
      $('#part_name').text(`${p.employee.FIRSTNAME} ${p.employee.MI} ${p.employee.LASTNAME}`);
      $('#part_empcode').text(p.employee.EMPCODE);
      console.log()
      // set status
      statusSelect.value = p.attendance || '';

      // trigger UI (IMPORTANT)
      statusSelect.dispatchEvent(new Event('change'));

      // set hours (if exists)
      hoursInput.value = p.hours || '';

      // handle file (requirements)
      if (p.justification && p.justification.file_path) {
        showExistingFile(p.justification.file_path);


        // trick so validation won't block save
        selectedAbsentFile = 'existing';
      } else {
        // resetFileUI();
        selectedAbsentFile = null;
      }

      // re-check validation
      validateForm();

      // open modal
      attendanceModal.showModal();
  }

  function buildPageButtons(totalPages, currentPage, batchId) {
    if (totalPages <= 1) return '';
 
    const pages  = [];
    const window = 2; // pages shown around current
 
    for (let i = 1; i <= totalPages; i++) {
      const nearCurrent = Math.abs(i - currentPage) <= window;
      const isEdge      = i === 1 || i === totalPages;
 
      if (nearCurrent || isEdge) {
        pages.push(i);
      } else if (pages[pages.length - 1] !== '…') {
        pages.push('…');
      }
    }
 
    return pages.map(p =>
      p === '…'
        ? `<button class="page-btn" disabled>…</button>`
        : `<button class="page-btn ${p === currentPage ? 'active' : ''}"
                   onclick="changePage(${batchId}, ${p})">
             ${p}
           </button>`
    ).join('');
  }
 
  /* ════════════════════════════════════════════════════════════
     EVENTS
     ════════════════════════════════════════════════════════════ */
 
  // Toggle batch collapse/expand
  function toggleBatch(batchId) {
    render();
  }

  function addParticipant(batchId){
    $('#batchIdBulkInput').val(batchId);
    bulk_modal.showModal();
  }

  function addParticipantsSelect(batchId){
    $('#batchIdBulkInput').val(batchId);
    $('#batchId').val(batchId);
    openEmployeeModal();
  }
 
  // Change page for a specific batch
  function changePage(batchId, page) {
    const filtered   = filterParticipants(
      state.batches.find(b => b.id === batchId)?.participants || []
    );
    const totalPages = Math.ceil(filtered.length / state.perPage);
 
    // Guard against out-of-range pages
    if (page < 1 || page > totalPages) return;
 
    state.pages[batchId] = page;
    render();
 
    // Scroll to the batch card smoothly
    const card = batchContainer.querySelector(`[data-batch-id="${batchId}"]`);
    if (card) card.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
 
  // Search input — debounced so it doesn't fire on every keystroke
  let searchDebounce;
  searchInput.addEventListener('input', () => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
      state.searchQuery = searchInput.value;
 
      // Reset all batches to page 1 when search changes
      state.batches.forEach(b => { state.pages[b.id] = 1; });
 
      // Auto-open all batches when searching
      if (state.searchQuery) {
        state.batches.forEach(b => { state.openBatches[b.id] = true; });
      }
 
      render();
    }, 250); // 250ms debounce delay
  });
 
  // Per-page selector
  perPageSelect.addEventListener('change', () => {
    state.perPage = parseInt(perPageSelect.value);
 
    // Reset all batches to page 1
    state.batches.forEach(b => { state.pages[b.id] = 1; });
 
    render();
  });
 
  /* ════════════════════════════════════════════════════════════
     HELPERS
     ════════════════════════════════════════════════════════════ */
 
  // Get initials from a full name (e.g. "Juan Dela Cruz" → "JD")
  function initials(name) {
    if (!name) return '?';
    return name.split(' ')
               .slice(0, 2)
               .map(w => w[0]?.toUpperCase() ?? '')
               .join('');
  }
 
  // Capitalize first letter
  function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
  }
 
  // Format date string (e.g. "2024-01-15" → "Jan 15, 2024")
  function formatDate(dateStr) {
    if (!dateStr) return 'TBD';
    try {
      return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
      });
    } catch { return dateStr; }
  }
 
  // Escape HTML to prevent XSS
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }
 
  // Batch status badge class
  function statusClass(status) {
    switch (status) {
      case 'active'   : return 'badge-green';
      case 'Completed'   : return 'badge-success';
      case 'Upcoming' : return 'badge-warning';
      case 'Rescheduled' : return 'badge-red';
      default         : return 'badge-blue';
    }
  }
 
  // Participant status badge class
  function participantStatusClass(status) {
    switch ((status || '').toLowerCase()) {
      case 'active'   : return 'status-active';
      case 'inactive' : return 'status-inactive';
      default         : return 'status-pending';
    }
  }
 
  // Show/hide error banner
  function showError(message) {
    errorBanner.textContent = message;
    errorBanner.classList.toggle('show', !!message);
  }
 
  /* ════════════════════════════════════════════════════════════
     DEMO DATA — Remove this when connected to real Laravel API
     ════════════════════════════════════════════════════════════ */
  function generateDemoData() {
    const departments = ['IT', 'HR', 'Finance', 'Operations', 'Marketing'];
    const statuses    = ['active', 'inactive', 'pending'];
    const programs    = ['Web Development', 'Data Analytics', 'Leadership 101', 'UX Design'];
 
    const randomName = () => {
      const first = ['Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Carlos', 'Liza', 'Mark', 'Grace'];
      const last  = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Gonzales', 'Ramos', 'Dela Cruz', 'Torres'];
      return `${first[Math.floor(Math.random() * first.length)]} ${last[Math.floor(Math.random() * last.length)]}`;
    };
 
    return Array.from({ length: 4 }, (_, bi) => ({
      id         : bi + 1,
      name       : `Batch ${2024 + bi}-${String.fromCharCode(65 + bi)}`,
      program    : programs[bi % programs.length],
      start_date : `${2024 + bi}-01-15`,
      end_date   : `${2024 + bi}-04-15`,
      status     : bi === 1 ? 'inactive' : 'active',
      participants: Array.from({ length: 10 + bi * 8 }, (_, pi) => ({
        id            : bi * 100 + pi + 1,
        name          : randomName(),
        employee_code : `EMP-${String(bi * 100 + pi + 1).padStart(4, '0')}`,
        email         : `employee${bi * 100 + pi + 1}@company.com`,
        department    : departments[pi % departments.length],
        status        : statuses[pi % statuses.length],
      })),
    }));
  }
 
  /* ════════════════════════════════════════════════════════════
     INIT — Start fetching on page load
     ════════════════════════════════════════════════════════════ */
  fetchBatches();


  function getAttendanceBadge(status) {
  let baseClass = 'badge badge-soft';

  switch ((status || '')) {
    case 'Complete':
      return `${baseClass} badge-success text-green-600`; // green

    case 'Pending':
      return `${baseClass} badge-warning text-yellow-600`; // yellow

    case 'Absent':
      return `${baseClass} badge-primary `; // red (optional)

    default:
      return `${baseClass} badge-neutral`; // fallback
  }
}

function moveParticipant(participantId, direction, batchId) {
  fetch(`/participants/${participantId}/move-order`, {
    method: 'POST',
    headers: {
      'Accept'       : 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      direction: direction,
      batch_id: batchId
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      fetchBatches(); // refresh UI
      showToast('Order updated', 'success');
    } else {
      showToast('Failed to update order', 'error');
    }
  })
  .catch(err => console.error(err));
}

document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
});

  </script>



   
    





</section>
