<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Program Monitoring</title>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>


  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700;900&amp;family=Outfit:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
  html, body { height: 100%; }
  body { font-family: 'Outfit', system-ui, sans-serif; background: #faf7f2; color: #1a1a1a; }
  .font-display { font-family: 'Fraunces', Georgia, serif; }

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


  <style>body { box-sizing: border-box; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>


 
 <body class="h-full w-full">


  <main class="h-full w-full overflow-auto grid-pattern">
   <div class="max-w-7xl mx-auto px-6 py-8"><!-- Header -->
    <header class="mb-8 flex items-start justify-between flex-wrap gap-4">
     <div>
      <div class="flex items-center gap-2 mb-2"><span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> <span class="text-xs tracking-widest uppercase text-stone-500 font-medium">Compliance Dashboard</span>
      </div>
      <h1 id="app-title" class="font-display text-4xl md:text-5xl font-black tracking-tight text-stone-900">Training Program Monitoring</h1>
      <p id="app-subtitle" class="text-stone-600 mt-2 text-sm md:text-base">Monthly regional submission tracker for training reports</p>
     </div>
     <div class="flex items-center gap-3">
      <div class="bg-white border border-stone-200 rounded-xl px-4 py-3 shadow-sm">
       <div class="text-[10px] uppercase tracking-wider text-stone-500">
        Year
       </div>
       <div id="year-display" class="font-display text-2xl font-bold text-stone-900">
        2025
       </div>
      </div><button id="new-submission-btn" class="bg-stone-900 text-white px-5 py-3 rounded-xl font-medium text-sm hover:bg-stone-700 transition flex items-center gap-2 shadow-sm"> <i data-lucide="upload-cloud" class="w-4 h-4"></i> New Submission </button>
     </div>
    </header><!-- Stats -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
     <div class="bg-white border border-stone-200 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-stone-500 mb-1">
       Total Required
      </div>
      <div class="flex items-baseline gap-1"><span id="stat-total" class="font-display text-3xl font-bold">0</span> <span class="text-xs text-stone-400">slots</span>
      </div>
     </div>
     <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-emerald-700 mb-1">
       Compliant
      </div>
      <div class="flex items-baseline gap-1"><span id="stat-complete" class="font-display text-3xl font-bold text-emerald-900">0</span> <span class="text-xs text-emerald-600">submitted</span>
      </div>
     </div>
     <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-amber-700 mb-1">
       Pending
      </div>
      <div class="flex items-baseline gap-1"><span id="stat-pending" class="font-display text-3xl font-bold text-amber-900">0</span> <span class="text-xs text-amber-600">overdue</span>
      </div>
     </div>
     <div class="bg-stone-900 text-white border border-stone-900 rounded-xl p-4">
      <div class="text-[10px] uppercase tracking-wider text-stone-400 mb-1">
       Compliance Rate
      </div>
      <div class="flex items-baseline gap-1"><span id="stat-rate" class="font-display text-3xl font-bold">0%</span>
      </div>
     </div>
    </section><!-- Compliance Matrix -->
    <section class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-6">
     <div class="px-5 py-4 border-b border-stone-200 flex items-center justify-between">
      <div>
       <h2 class="font-display text-xl font-bold">Regional Compliance Matrix</h2>
       <p class="text-xs text-stone-500 mt-0.5">Each cell represents a regional admin's monthly submission status</p>
      </div>
      <div class="hidden md:flex items-center gap-3 text-xs"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded cell-complete border border-emerald-300"></span> Submitted</span> <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-white border border-stone-300"></span> Pending</span> <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded cell-future border border-stone-300"></span> Future</span>
      </div>
     </div>
     <div class="overflow-x-auto hide-scroll">
      <table class="w-full text-sm" id="matrix-table">
       <thead class="bg-stone-50">
        <tr id="matrix-header"></tr>
       </thead>
       <tbody id="matrix-body"></tbody>
      </table>
     </div>
    </section><!-- Recent Submissions -->
    <section class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden mb-10">
     <div class="px-5 py-4 border-b border-stone-200">
      <h2 class="font-display text-xl font-bold">Recent Submissions</h2>
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
      <h3 class="font-display text-2xl font-bold">Submit Report</h3>
      <p class="text-xs text-stone-500 mt-0.5">Upload the Training Program Monitoring PDF</p>
     </div><button id="close-modal" class="w-8 h-8 rounded-lg hover:bg-stone-100 flex items-center justify-center"> <i data-lucide="x" class="w-4 h-4"></i> </button>
    </div>
    <form id="upload-form" class="px-6 py-5 space-y-4">
     <div class="grid grid-cols-2 gap-3">
      <div><label for="region-select" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Region</label> <select id="region-select" required class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent bg-white"> <option value="">Select region…</option> </select>
      </div>
      <div><label for="month-select" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Month</label> <select id="month-select" required class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent bg-white"> <option value="">Select month…</option> </select>
      </div>
     </div>
     <div><label for="employee-count" class="block text-xs font-medium text-stone-700 mb-1.5 uppercase tracking-wide">Employees Trained</label> <input id="employee-count" type="number" min="0" required placeholder="e.g. 24" class="w-full border border-stone-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-transparent">
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



  <script>
  // ---------- Reference Data ----------
  const REGIONS = [
    { id: 'NCR', name: 'National Capital Region', admin: 'Maria Santos' },
    { id: 'N-LUZ', name: 'Northern Luzon', admin: 'Carlo Reyes' },
    { id: 'S-LUZ', name: 'Southern Luzon', admin: 'Ana Dela Cruz' },
    { id: 'VIS', name: 'Visayas', admin: 'Jomar Tan' },
    { id: 'MIN', name: 'Mindanao', admin: 'Liza Abad' },
  ];
  const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const MONTHS_FULL = ['January','February','March','April','May','June','July','August','September','October','November','December'];

  let currentData = [];
  let currentYear = 2025;

  // ---------- Element SDK ----------
  const defaultConfig = {
    app_title: 'Training Program Monitoring',
    app_subtitle: 'Monthly regional submission tracker for training reports',
    current_year: '2025',
  };

  window.elementSdk && window.elementSdk.init({
    defaultConfig,
    onConfigChange: async (config) => {
      document.getElementById('app-title').textContent = config.app_title || defaultConfig.app_title;
      document.getElementById('app-subtitle').textContent = config.app_subtitle || defaultConfig.app_subtitle;
      const y = parseInt(config.current_year || defaultConfig.current_year, 10);
      currentYear = isNaN(y) ? 2025 : y;
      document.getElementById('year-display').textContent = currentYear;
      renderAll();
    },
    mapToCapabilities: () => ({ recolorables: [], borderables: [], fontEditable: undefined, fontSizeable: undefined }),
    mapToEditPanelValues: (config) => new Map([
      ['app_title', config.app_title || defaultConfig.app_title],
      ['app_subtitle', config.app_subtitle || defaultConfig.app_subtitle],
      ['current_year', config.current_year || defaultConfig.current_year],
    ]),
  });

  // ---------- UI Helpers ----------
  function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    const inner = document.getElementById('toast-inner');
    inner.className = 'toast px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm ' +
      (type === 'error' ? 'bg-red-600 text-white' : 'bg-stone-900 text-white');
    inner.innerHTML = `<i data-lucide="${type === 'error' ? 'alert-circle' : 'check-circle-2'}" class="w-4 h-4"></i><span>${msg}</span>`;
    t.classList.remove('hidden');
    lucide.createIcons();
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => t.classList.add('hidden'), 3000);
  }

  function populateSelects() {
    const rs = document.getElementById('region-select');
    rs.innerHTML = '<option value="">Select region…</option>' +
      REGIONS.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
    const ms = document.getElementById('month-select');
    ms.innerHTML = '<option value="">Select month…</option>' +
      MONTHS_FULL.map((m, i) => `<option value="${m}">${m}</option>`).join('');
  }

  // ---------- Matrix ----------
  function renderMatrix() {
    const header = document.getElementById('matrix-header');
    header.innerHTML = `
      <th class="text-left px-4 py-3 text-[10px] uppercase tracking-wider text-stone-500 font-semibold sticky left-0 bg-stone-50 border-r border-stone-200">Region / Admin</th>
      ${MONTHS.map(m => `<th class="px-2 py-3 text-center text-[10px] uppercase tracking-wider text-stone-500 font-semibold">${m}</th>`).join('')}
      <th class="px-3 py-3 text-center text-[10px] uppercase tracking-wider text-stone-500 font-semibold">Rate</th>
    `;

    const currentMonthIdx = new Date().getFullYear() === currentYear ? new Date().getMonth() : 11;
    const body = document.getElementById('matrix-body');
    body.innerHTML = REGIONS.map(region => {
      let submitted = 0;
      const required = currentMonthIdx + 1;
      const cells = MONTHS_FULL.map((monthName, idx) => {
        const rec = currentData.find(d => d.region === region.id && d.month === monthName && parseInt(d.year) === currentYear);
        if (rec) {
          submitted++;
          return `<td class="p-1.5">
            <button onclick="viewSubmission('${rec.id || rec.__backendId}')" class="cell-complete border border-emerald-300 w-full h-10 rounded-md flex items-center justify-center hover:scale-105 transition" title="${monthName} — ${rec.file_name}">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-800"></i>
            </button>
          </td>`;
        }
        const isFuture = idx > currentMonthIdx;
        if (isFuture) {
          return `<td class="p-1.5"><div class="cell-future border border-stone-300 border-dashed w-full h-10 rounded-md"></div></td>`;
        }
        return `<td class="p-1.5">
          <button onclick="quickSubmit('${region.id}','${monthName}')" class="cell-pending border border-amber-300 w-full h-10 rounded-md flex items-center justify-center hover:bg-amber-50 transition group" title="Pending — click to submit">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-600 group-hover:scale-110 transition"></i>
          </button>
        </td>`;
      }).join('');
      const rate = required > 0 ? Math.round((submitted / required) * 100) : 0;
      return `<tr class="border-t border-stone-100 hover:bg-stone-50/50">
        <td class="px-4 py-2 sticky left-0 bg-white border-r border-stone-200">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-stone-900 text-white flex items-center justify-center text-[10px] font-bold">${region.id}</div>
            <div>
              <div class="font-semibold text-stone-900 text-sm leading-tight">${region.name}</div>
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
    const required = REGIONS.length * (currentMonthIdx + 1);
    const completed = currentData.filter(d => {
      const mIdx = MONTHS_FULL.indexOf(d.month);
      return d.year === currentYear && mIdx <= currentMonthIdx;
    }).length;
    const pending = Math.max(0, required - completed);
    const rate = required > 0 ? Math.round((completed / required) * 100) : 0;
    document.getElementById('stat-total').textContent = required;
    document.getElementById('stat-complete').textContent = completed;
    document.getElementById('stat-pending').textContent = pending;
    document.getElementById('stat-rate').textContent = rate + '%';
  }

  // ---------- Submissions List ----------
  function renderSubmissions() {
    const list = document.getElementById('submissions-list');
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
      const region = REGIONS.find(r => r.id === item.region);
      const date = new Date(item.submitted_at);
      const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      const sizeKB = Math.max(1, Math.round((item.file_size || 0) / 1024));
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
          <div class="text-xs text-stone-500 truncate mt-0.5">${item.file_name} · ${sizeKB} KB · ${item.employee_count} employees trained</div>
          ${item.notes ? `<div class="text-xs text-stone-600 mt-1 italic truncate">"${item.notes}"</div>` : ''}
        </div>
        <div class="text-right flex-shrink-0">
          <div class="text-xs text-stone-500">Submitted</div>
          <div class="text-xs font-medium text-stone-900">${dateStr}</div>
        </div>
        <button onclick="removeSubmission('${item.id}')" class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-stone-400 hover:text-red-600 transition" title="Delete">
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
      document.getElementById('month-select').value = prefill.month || '';
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }
  function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); }

  document.getElementById('new-submission-btn').onclick = () => openModal();
  document.getElementById('close-modal').onclick = closeModal;
  document.getElementById('cancel-btn').onclick = closeModal;
  modal.onclick = (e) => { if (e.target === modal) closeModal(); };

  window.quickSubmit = (region, month) => openModal({ region, month });
  window.viewSubmission = (id) => {
    const rec = currentData.find(d => d.id === id);
    if (rec) showToast(`${rec.file_name} — submitted ${new Date(rec.submitted_at).toLocaleDateString()}`);
  };
  window.removeSubmission = async (id) => {
    const rec = currentData.find(d => d.id === id);
    if (!rec) return;
    const result = await deleteSubmission(id);
    if (result.success) showToast('Submission removed');
    else showToast('Could not remove submission', 'error');
  };

  // File input
  const fileInput = document.getElementById('file-input');
  fileInput.addEventListener('change', () => {
    const f = fileInput.files[0];
    if (!f) return;
    document.getElementById('file-label').textContent = f.name + ' (' + Math.max(1, Math.round(f.size / 1024)) + ' KB)';
  });

  // Submit
  document.getElementById('upload-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const region = document.getElementById('region-select').value;
    const month = document.getElementById('month-select').value;
    const employeeCount = parseInt(document.getElementById('employee-count').value, 10) || 0;
    const notes = document.getElementById('notes-input').value.trim();
    const file = fileInput.files[0];

    if (!region || !month) return showToast('Please select region and month', 'error');
    if (!file) return showToast('Please choose a PDF file', 'error');
    if (!file.name.toLowerCase().endsWith('.pdf')) return showToast('Only PDF files are allowed', 'error');

    const duplicate = currentData.find(d => d.region === region && d.month === month && d.year === currentYear);
    if (duplicate) return showToast('This region already submitted for ' + month, 'error');

    const btn = document.getElementById('submit-btn');
    const label = document.getElementById('submit-label');
    btn.disabled = true;
    btn.classList.add('opacity-70');
    label.innerHTML = '<span class="spinner"></span> Submitting…';

    // Create FormData for file upload
    const formData = new FormData();
    formData.append('region', region);
    formData.append('month', month);
    formData.append('year', currentYear);
    formData.append('employee_count', employeeCount);
    formData.append('notes', notes);
    formData.append('pdf', file); // Laravel expects 'pdf' field for file

    const result = await uploadPDF(formData);
    btn.disabled = false;
    btn.classList.remove('opacity-70');
    label.textContent = 'Submit Report';

    if (result.success) {
      closeModal();
      showToast(`Report submitted for ${month} · ${region}`);
    } else {
      showToast('Submission failed: ' + (result.error || 'Unknown error'), 'error');
    }
  });

  // ---------- Laravel API Configuration ----------
  const API_BASE = 'http://your-laravel-app.local/api'; // Update this URL to your Laravel backend
  const API_TOKEN = localStorage.getItem('api_token') || ''; // Store your Bearer token in localStorage or pass it via Canva

  // Fetch submissions from Laravel
  async function loadSubmissions() {
    try {
      const response = await fetch(`${API_BASE}/training-submissions`, {
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
        }
      });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      const data = await response.json();
      currentData = data.data || data || [];
      renderAll();
    } catch (err) {
      console.error('Failed to load submissions:', err);
      showToast('Could not load submissions. Check API connection.', 'error');
    }
  }

  // Upload PDF to Laravel
  async function uploadPDF(formData) {
    try {
      const response = await fetch(`${API_BASE}/training-submissions`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Accept': 'application/json',
        },
        body: formData, // FormData with file
      });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      await loadSubmissions(); // Reload after upload
      return { success: true };
    } catch (err) {
      console.error('Upload failed:', err);
      return { success: false, error: err.message };
    }
  }

  // Delete submission from Laravel
  async function deleteSubmission(submissionId) {
    try {
      const response = await fetch(`${API_BASE}/training-submissions/${submissionId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
        }
      });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      await loadSubmissions(); // Reload after delete
      return { success: true };
    } catch (err) {
      console.error('Delete failed:', err);
      return { success: false, error: err.message };
    }
  }

  // Initialize on page load
  (async () => {
    populateSelects();
    lucide.createIcons();
    if (!API_TOKEN) {
      showToast('Warning: API token not found. Set your Bearer token to enable submissions.', 'error');
    }
    await loadSubmissions();
  })();
</script>


 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9f323412b1a64679',t:'MTc3NzMzODc5Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>


</body>
</html>