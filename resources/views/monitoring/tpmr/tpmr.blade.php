<x-layout>
<x-slot:title>View Program</x-slot:title>
<x-monitoring-layout>
    @include('components.loading')
    
    <style>

    .grid-pattern {
        background-image:
        linear-gradient(to right, rgba(20,20,20,.04) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(20,20,20,.04) 1px, transparent 1px);
        background-size: 32px 32px;
    }
    .fade-in { animation: fadeIn .4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .cell-complete { background: linear-gradient(135deg, #d4f1c5 0%, #b8e4a3 100%); }
    .cell-pending { background: #fff; }
    .cell-future { background: #f4efe6; }
    .spinner { border: 2px solid #e5e5e5; border-top-color: #1a1a1a; border-radius: 50%; width: 16px; height: 16px; animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .toast { animation: slideIn .3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .hide-scroll::-webkit-scrollbar { display: none; }
    </style>




    <div class=" flex-1  overflow-auto ">

    <main class="h-full w-full overflow-auto grid-pattern">
   <div class="max-w-7xl mx-auto px-6 py-8"><!-- Header -->
    <header class="mb-8 flex items-start justify-between flex-wrap gap-4">
     <div>
      <div class="flex items-center gap-2 mb-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 
        <span class="text-xs tracking-widest uppercase text-stone-500 poppins-medium dark:text-slate-100">Compliance Dashboard</span>
      </div>
      <h1 id="app-title" class="poppins-bold text-lg md:text-5xl  tracking-tight text-stone-900 dark:text-white">Training Program Monitoring Reports</h1>
      <p id="app-subtitle" class="text-stone-600 poppins-regular  mt-2 text-sm md:text-base dark:text-slate-100">Monthly regional submission tracker for training reports</p>
     </div>

     <div class="flex items-center gap-3">

      <div class="bg-white border border-stone-200 dark:bg-slate-700 dark:border-slate-700 rounded-xl px-4 py-3 shadow-sm">
       <div class="text-[10px] uppercase tracking-wider text-stone-500 dark:text-slate-200">
        Year
       </div>
       <div id="year-display" class="poppins-bold text-2xl font-bold text-stone-900 dark:text-white ">
        2025
       </div>
      </div><button id="new-submission-btn" class="bg-stone-900 text-white px-5 py-3 rounded-xl font-medium text-sm hover:bg-stone-700 transition flex items-center gap-2 shadow-sm"> <i data-lucide="upload-cloud" class="w-4 h-4"></i> New Submission </button>
     </div>
    </header>
    

    <!-- Stats -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">

     <div class="bg-white border border-stone-200 dark:bg-slate-700 dark:border-slate-600 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-stone-500 mb-1 dark:text-slate-100">
       Total Required
      </div>

      <div class="flex items-baseline gap-1">
        <span id="stat-total" class="poppins-bold text-3xl font-bold">0</span> 
        <span class="text-xs text-stone-400 poppins-regular dark:text-slate-100">slots</span>
      </div>
     </div>

     <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-emerald-700 mb-1">
       Compliant
      </div>

      <div class="flex items-baseline gap-1">
        <span id="stat-complete" class="poppins-bold text-3xl font-bold text-emerald-900">0</span> 
        <span class="text-xs text-emerald-600">submitted</span>
      </div>
     </div>

     <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-amber-700 mb-1">
       Pending
      </div>

      <div class="flex items-baseline gap-1">
        <span id="stat-pending" class="poppins-bold text-3xl font-bold text-amber-900">0</span> 
        <span class="text-xs text-amber-600">overdue</span>
      </div>
     </div>

     <div class="bg-stone-900 text-white border border-stone-900 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-stone-400 mb-1 dark:text-slate-200">
       Compliance Rate
      </div>

      <div class="flex items-baseline gap-1"><span id="stat-rate" class="poppins-bold text-3xl font-bold">0%</span>
      </div>
     </div>

    </section>
    
    <!-- Compliance Matrix -->
    <section class="bg-white dark:bg-slate-700 dark:border-slate-600 border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-6">

     <div class="px-5 py-4 border-b border-stone-200 flex items-center justify-between">
      <div>
       <h2 class="poppins-bold text-xl font-bold">Regional Compliance Matrix</h2>
       <p class="text-xs text-stone-500 mt-0.5 poppins-regular dark:text-slate-200">Each cell represents a regional admin's monthly submission status</p>
      </div>
      <div class="hidden md:flex items-center gap-3 text-xs"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded cell-complete border border-emerald-300"></span> Submitted</span> <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-white border border-stone-300"></span> Pending</span> <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded cell-future border border-stone-300"></span> Future</span>
      </div>
     </div>


     <div class="overflow-x-auto hide-scroll dark:bg-slate-700">
      <table class="w-full text-sm dark:bg-slate-700 dark:border-slate-600" id="matrix-table">

       <thead class="bg-stone-50 dark:bg-slate-700  dark:text-white">
        <tr id="matrix-header" class=""></tr>
       </thead>

       <tbody id="matrix-body"></tbody>
      </table>
     </div>
    </section>
    
    
    
    
    <!-- Recent Submissions -->
    <section class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-10">
     <div class="px-5 py-4 border-b border-stone-200">
      <h2 class="poppins-bold text-xl font-bold">Recent Submissions</h2>
      <p class="text-xs text-stone-500 mt-0.5">Latest uploaded training monitoring reports</p>
     </div>
     <div id="submissions-list" class="divide-y divide-stone-100">
      <div class="px-5 py-12 text-center text-stone-400 text-sm" id="empty-state"><i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-stone-300"></i>
       <p>No submissions yet. Click "New Submission" to upload a report.</p>
      </div>
     </div>
    </section>
   </div>
  </main>


  <!-- Upload Modal -->
  <div id="upload-modal" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
   <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg fade-in">
    <div class="px-6 py-5 border-b border-stone-200 flex items-center justify-between">
     <div>
      <h3 class="font-display text-2xl font-bold">Submit Signed TPMR</h3>
      <p class="text-xs text-stone-500 mt-0.5">Upload the Training Program Monitoring PDF</p>
     </div><button id="close-modal" class="w-8 h-8 rounded-lg hover:bg-stone-100 flex items-center justify-center"> <i data-lucide="x" class="w-4 h-4"></i> </button>
    </div>
    <form id="upload-form" class="px-6 py-5 space-y-4">
     <div class="grid grid-cols-2 gap-3">
      <div><label for="region-select" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Region</label> <select id="region-select" required class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent bg-white"> <option value="">Select region…</option> </select>
      </div>
      <div><label for="month-select" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Month</label> 
        <select id="month-select" required class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent bg-white"> <option value="">Select month…</option> </select>
      </div>
     </div>
     
     <div class="hidden"><label for="employee-count" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Employees Trained</label> 
      <input id="employee-count" type="number" min="0"  placeholder="e.g. 24" class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent">
     </div>

     
     <div><label for="notes-input" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Notes (optional)</label> <textarea id="notes-input" rows="2" placeholder="Brief summary of training activities…" class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent resize-none"></textarea>
     </div>


     <div><label class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">PDF Report</label> <label for="file-input" id="file-drop" class="block border-2 border-dashed border-stone-300 rounded-lg p-6 text-center cursor-pointer hover:border-stone-900 hover:bg-stone-50 transition"> <i data-lucide="file-up" class="w-7 h-7 mx-auto mb-2 text-stone-400"></i>
       <div id="file-label" class="text-sm text-stone-600">
        Click to choose a PDF file
       </div>
       <div class="text-[10px] text-stone-400 mt-1">
        PDF only, max ~10MB
       </div><input id="file-input" type="file" accept="application/pdf,.pdf" class="hidden"> </label>
     </div>
     <div class="flex items-center justify-end gap-2 pt-2"><button type="button" id="cancel-btn" class="px-4 py-2.5 rounded-lg text-sm font-medium text-stone-700 hover:bg-stone-100">Cancel</button> <button type="submit" id="submit-btn" class="px-5 py-2.5 rounded-lg text-sm font-medium bg-stone-900 text-white hover:bg-stone-700 flex items-center gap-2"> <span id="submit-label">Submit Report</span> </button>
     </div>
    </form>
   </div>
  </div><!-- Toast -->
  <div id="toast" class="fixed top-6 right-6 z-50 hidden">
   <div id="toast-inner" class="toast bg-stone-900 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm"></div>
  </div>

    

       

    </div>



<script>
  // ---------- Reference Data ----------
  const REGIONS = [
    { id: 'NCR',   name: 'National Capital Region', admin: 'Angelina M. Carreon' },
    { id: 'R1', name: 'Ilocos Region',           admin: 'Ramon Evan T. Ruiz' },
    { id: 'R2', name: 'Cagayan Valley Region',           admin: 'Ashary A. Banto' },
    { id: 'R3',   name: 'Central Luzon',                  admin: 'Balmyrzon M. Valdez' },
    { id: 'R4A',   name: 'Calabarzon',                 admin: 'Liza Abad' },
    { id: 'R4B',   name: 'Mimaropa',                 admin: 'Baron Jose L. Lagran' },
    { id: 'R5',   name: 'Bicol Region',                 admin: 'Archie A. Grande' },
    { id: 'R6',   name: 'Western Visayas',                 admin: 'Esther B. Babalo' },
    { id: 'NIR',   name: 'Negros Island Region',                 admin: 'Niña Connie Dodd' },
    { id: 'R7',   name: 'Central Visayas',                 admin: 'Gamaliel B. Vicente, Jr.' },
    { id: 'R8',   name: 'Eastern Visayas',                 admin: 'Dan M. Navarro' },
    { id: 'R9',   name: 'Zamboanga Peninsula',                 admin: 'Alan T. Bacatan' },
    { id: 'R10',   name: 'Northern Mindanao',                 admin: 'Rafael Y. Abrogar' },
    { id: 'R11',   name: 'Davao Region',                 admin: 'Tarhata S. Mapandi' },
    { id: 'R12',   name: 'SOCCSKSARGEN',                 admin: 'Remegias G. Timonio' },
    { id: 'CAR',   name: 'Cordillera Administrative Region',                 admin: 'Glenn N. Murphy' },
    { id: 'CARAGA',   name: 'CARAGA',                 admin: 'florencio F. Sunico, Jr.' },
  ];
  const MONTHS      = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const MONTHS_FULL = ['January','February','March','April','May','June','July','August','September','October','November','December'];

  let currentData = [];
  let currentYear = {{ date('Y') }}; // Laravel injects the current year

  const API_BASE = '/api';

  function authHeaders() {
      return { 'Accept': 'application/json' };
  }
  // ---------- UI Helpers ----------
  function showToast(msg, type = 'success') {
    const t     = document.getElementById('toast');
    const inner = document.getElementById('toast-inner');
    inner.className = 'toast px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm ' +
      (type === 'error' ? 'bg-red-600 text-white' : 'bg-stone-900 text-white');
    inner.innerHTML = `<i data-lucide="${type === 'error' ? 'alert-circle' : 'check-circle-2'}" class="w-4 h-4"></i><span>${msg}</span>`;
    t.classList.remove('hidden');
    lucide.createIcons();
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => t.classList.add('hidden'), 3500);
  }

  function populateSelects() {
    const rs = document.getElementById('region-select');
    rs.innerHTML = '<option value="">Select region…</option>' +
      REGIONS.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
    const ms = document.getElementById('month-select');
    ms.innerHTML = '<option value="">Select month…</option>' +
      MONTHS_FULL.map(m => `<option value="${m}">${m}</option>`).join('');
  }

  // ---------- Matrix ----------
  function renderMatrix() {
    const header = document.getElementById('matrix-header');
    header.innerHTML = `
      <th class="text-left px-4 py-3 text-[10px] dark:bg-slate-700 uppercase tracking-wider text-stone-500 font-semibold sticky left-0 bg-stone-50 border-r border-stone-200 dar:border-slate-600 dark:text-white">Region / Admin</th>
      ${MONTHS.map(m => `<th class="px-2 py-3 text-center text-[10px] uppercase tracking-wider text-stone-500 font-semibold">${m}</th>`).join('')}
      <th class="px-3 py-3 text-center text-[10px] uppercase tracking-wider text-stone-500 font-semibold">Rate</th>
    `;

    const currentMonthIdx = new Date().getFullYear() === currentYear ? new Date().getMonth() : 11;
    const body = document.getElementById('matrix-body');
    body.innerHTML = REGIONS.map(region => {
      let submitted = 0;
      const required = currentMonthIdx + 1;
      const cells = MONTHS_FULL.map((monthName, idx) => {
        const rec = currentData.find(
          d => d.region === region.id && d.month === monthName && parseInt(d.year) === currentYear
        );
        if (rec) {
          submitted++;
          return `<td class="p-1.5">
            <button onclick="viewSubmission(${rec.id})"
              class="cell-complete border border-emerald-300 dark:border-slate-600 w-full h-10 rounded-md flex items-center justify-center hover:scale-105 transition"
              title="${monthName} — ${rec.file_name}">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-800"></i>
            </button>
          </td>`;
        }
        if (idx > currentMonthIdx) {
          return `<td class="p-1.5"><div class="cell-future border border-stone-300 border-dashed w-full h-10 rounded-md"></div></td>`;
        }
        return `<td class="p-1.5">
          <button onclick="quickSubmit('${region.id}','${monthName}')"
            class="cell-pending border border-amber-300 w-full h-10 rounded-md flex items-center justify-center hover:bg-amber-50 transition group"
            title="Pending — click to submit">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-600 group-hover:scale-110 transition"></i>
          </button>
        </td>`;
      }).join('');
      const rate = required > 0 ? Math.round((submitted / required) * 100) : 0;
      return `<tr class="border-t border-stone-100 hover:bg-stone-50/50 dark:border-slate-600 ">
        <td class="px-4 py-2 sticky left-0 bg-white border-r border-stone-200 dark:border-slate-600 dark:bg-slate-700 ">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-stone-900 text-white flex items-center justify-center text-[10px] font-bold">${region.id}</div>
            <div>
              <div class="font-semibold text-stone-900 text-sm leading-tight dark:text-white dark:text-white">${region.name}</div>
              <div class="text-[11px] text-stone-500">${region.admin}</div>
            </div>
          </div>
        </td>
        ${cells}
        <td class="px-3 py-2 text-center">
          <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold ${rate >= 80 ? 'bg-emerald-100 text-emerald-800' : rate >= 50 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800'}">${rate}%</span>
        </td>
      </tr>`;
    }).join('');
    lucide.createIcons();
  }

  // ---------- Stats ----------
  function renderStats() {
    const currentMonthIdx = new Date().getFullYear() === currentYear ? new Date().getMonth() : 11;
    const required  = REGIONS.length * (currentMonthIdx + 1);
    const completed = currentData.filter(d => {
      const mIdx = MONTHS_FULL.indexOf(d.month);
      return parseInt(d.year) === currentYear && mIdx <= currentMonthIdx;
    }).length;
    const pending = Math.max(0, required - completed);
    const rate    = required > 0 ? Math.round((completed / required) * 100) : 0;
    document.getElementById('stat-total').textContent    = required;
    document.getElementById('stat-complete').textContent = completed;
    document.getElementById('stat-pending').textContent  = pending;
    document.getElementById('stat-rate').textContent     = rate + '%';
  }

  // ---------- Submissions List ----------
  function renderSubmissions() {
    const list  = document.getElementById('submissions-list');
    const items = [...currentData]
      .filter(d => parseInt(d.year) === currentYear)
      .sort((a, b) => new Date(b.submitted_at) - new Date(a.submitted_at))
      .slice(0, 8);

    if (items.length === 0) {
      list.innerHTML = `<div class="px-5 py-12 text-center text-stone-400 text-sm">
        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-stone-300"></i>
        <p>No submissions for ${currentYear}. Click "New Submission" to upload a report.</p>
      </div>`;
      lucide.createIcons();
      return;
    }

    list.innerHTML = items.map(item => {
      const region  = REGIONS.find(r => r.id === item.region);
      const dateStr = item.submitted_at
        ? new Date(item.submitted_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
        : '—';
      const sizeKB  = Math.max(1, Math.round((item.file_size || 0) / 1024));
      return `<div class="px-5 py-4 flex items-center gap-4 hover:bg-stone-50 transition">
        <div class="w-11 h-11 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
          <i data-lucide="file-text" class="w-5 h-5 text-red-600"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="font-semibold text-stone-900 text-sm">${region ? region.name : item.region}</span>
            <span class="text-stone-300">·</span>
            <span class="text-xs text-stone-600">${item.month} ${item.year}</span>
            
            <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide rounded bg-emerald-100 text-emerald-800">Compliant</span>
            
          </div>
          
          <div class="text-xs text-stone-500 truncate mt-0.5"> 
            <a href="/storage/${item.file_path}" target="_blank" rel="noopener noreferrer" class="text-blue-500">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> ${item.file_name}
             </a>
            · ${sizeKB} KB · by ${item.added_by}
          </div>
          
          ${item.notes ? `<div class="text-xs text-stone-600 mt-1 italic truncate">"${item.notes}"</div>` : ''}
        </div>
        <div class="text-right flex-shrink-0">
          <div class="text-xs text-stone-500">Submitted</div>
          <div class="text-xs font-medium text-stone-900">${dateStr}</div>
        </div>
        <button onclick="removeSubmission(${item.id})"
          class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-stone-400 hover:text-red-600 transition"
          title="Delete">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </div>`;
    }).join('');
    lucide.createIcons();
  }

  function renderAll() {
    renderMatrix();
    renderStats();
    renderSubmissions();
  }

  // ---------- Modal ----------
  const modal = document.getElementById('upload-modal');
  function openModal(prefill) {
    document.getElementById('upload-form').reset();
    document.getElementById('file-label').textContent = 'Click to choose a PDF file';
    if (prefill) {
      document.getElementById('region-select').value = prefill.region || '';
      document.getElementById('month-select').value  = prefill.month  || '';
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }
  function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); }

  document.getElementById('new-submission-btn').onclick = () => openModal();
  document.getElementById('close-modal').onclick        = closeModal;
  document.getElementById('cancel-btn').onclick         = closeModal;
  modal.onclick = (e) => { if (e.target === modal) closeModal(); };

  window.quickSubmit = (region, month) => openModal({ region, month });

  window.viewSubmission = (id) => {
    const rec = currentData.find(d => d.id === id);
    if (!rec) {
        return showToast('File not found.', 'error');
    }

    // Open PDF in new tab
    window.open(`/storage/${rec.file_path}`, '_blank');
  };

  window.removeSubmission = async (id) => {
      if (!confirm('Delete this submission? This cannot be undone.')) return;
      try {
        const res = await fetch(`/training-submissions/${id}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        currentData = currentData.filter(d => d.id !== id);
        renderAll();
        showToast('Submission deleted.');
      } catch (err) {
        console.error(err);
        showToast('Could not delete submission.', 'error');
      }
  };

  // ---------- File Input ----------
  const fileInput = document.getElementById('file-input');
  fileInput.addEventListener('change', () => {
    const f = fileInput.files[0];
    if (!f) return;
    document.getElementById('file-label').textContent =
      f.name + ' (' + Math.max(1, Math.round(f.size / 1024)) + ' KB)';
  });

  // ---------- Form Submit ----------
  document.getElementById('upload-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const region = document.getElementById('region-select').value;
    const month  = document.getElementById('month-select').value;
    const notes  = document.getElementById('notes-input').value.trim();
    const file   = fileInput.files[0];

    if (!region || !month) return showToast('Please select region and month.', 'error');
    if (!file) return showToast('Please choose a PDF file.', 'error');
    if (!file.name.toLowerCase().endsWith('.pdf')) {
        return showToast('Only PDF files are allowed.', 'error');
    }

    const MAX_SIZE = 2 * 1024 * 1024 * 1024; // 2GB
    if (file.size > MAX_SIZE) {
        return showToast('File exceeds 2GB limit.', 'error');
    }

    const duplicate = currentData.find(
        d => d.region === region &&
             d.month === month &&
             parseInt(d.year) === currentYear
    );

    if (duplicate) {
        return showToast(`${region} already submitted for ${month}.`, 'error');
    }

    const btn   = document.getElementById('submit-btn');
    const label = document.getElementById('submit-label');

    btn.disabled = true;
    btn.classList.add('opacity-70');
    label.innerHTML = '<span class="spinner"></span> Submitting…';

    try {
        const fd = new FormData();
        fd.append('region', region);
        fd.append('month', month);
        fd.append('year', currentYear);
        fd.append('notes', notes);
        fd.append('pdf', file);

        const headers = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        };

        const res = await fetch('/training-submissions', {
            method: 'POST',
            headers,
            body: fd,
        });

        let data = null;
        let errorText = null;

        const contentType = res.headers.get('content-type');

        try {
            if (contentType && contentType.includes('application/json')) {
                data = await res.json();
            } else {
                errorText = await res.text();
            }
        } catch (e) {
            errorText = 'Unexpected server response';
        }

        if (!res.ok) {
            let msg = `HTTP ${res.status}`;

            if (data?.message) {
                msg = data.message;
            } else if (data?.errors) {
                msg = Object.values(data.errors).flat().join(' ');
            } else if (errorText) {
                if (res.status === 413 || errorText.includes('413')) {
                    msg = 'The uploaded file is too large.';
                } else {
                    msg = errorText;
                }
            }

            throw new Error(msg);
        }

        currentData.push(data);
        renderAll();
        closeModal();
        showToast(`Report submitted for ${month} · ${region}`);

    } catch (err) {
        console.error(err);
        showToast('Submission failed: ' + err.message, 'error');

    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-70');
        label.textContent = 'Submit Report';
    }
});

  // ---------- Load Submissions ----------
  async function loadSubmissions() {
    try {
      const res = await fetch(`/training-submissions?year=${currentYear}`, {
        headers: authHeaders(),
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      currentData = Array.isArray(data) ? data : (data.data ?? []);
      renderAll();
    } catch (err) {
      console.error('Failed to load submissions:', err);
      showToast('Could not load data from the server.', 'error');
    }
  }

  // ---------- Boot ----------
  (async () => {
    document.getElementById('year-display').textContent = currentYear;
    populateSelects();
    lucide.createIcons();
    await loadSubmissions();
  })();
</script>

    
    
    

</x-monitoring-layout>
</x-layout>