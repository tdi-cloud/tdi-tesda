


  
  <style>
    * { font-family: 'DM Sans', sans-serif; }
    .modal-backdrop { background: rgba(10, 10, 10, 0.6); backdrop-filter: blur(6px); }
    .modal-enter { animation: modalIn 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes modalIn {
      from { opacity: 0; transform: translateY(12px) scale(0.97); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .emp-item { transition: background 0.15s, transform 0.1s; }
    .emp-item:hover { background: #f0ece4; }
    .emp-item.selected { background: #e8e0d0; box-shadow: inset 3px 0 0 #8b7355; }
    .confirm-btn { transition: all 0.2s; }
    .confirm-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    input::placeholder { color: #a8a093; }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: #c4b9a6; border-radius: 9px; }
  </style>







   
  

  
  
  
  
 <!-- DaisyUI Modal -->
<dialog id="employee_modal" class="modal z-[9999]">

  <div class="modal-box bg-[#faf8f4] rounded-2xl shadow-2xl w-full max-w-lg border border-[#e0d9cb] p-0">

    <!-- Header -->
    <div class="p-5 pb-3">
      <div class="flex items-center justify-between mb-4">
        <h2 id="modalTitle" class="text-lg font-700 text-[#2c2418]">
          Search Employees
        </h2>

        <button onclick="closeModal()" class="text-[#8b7a60] hover:text-[#2c2418] transition-colors">
          ✕
        </button>
      </div>

      <!-- Search -->
      <div class="relative">
        <input id="searchInput"
          type="text"
          placeholder="Search by name, role, or department..."
          class="w-full bg-white border border-[#ddd5c6] rounded-xl pl-4 pr-4 py-2.5 text-sm text-[#2c2418] focus:outline-none focus:border-[#8b7355]"
          oninput="filterEmployees()">
      </div>
    </div>

    <!-- List -->
    <div id="empList" class="max-h-72 overflow-y-auto px-2 pb-2"></div>

    <!-- No Results -->
    <div id="noResults" class="hidden px-5 py-8 text-center text-[#a8a093] text-sm">
      <p>No employees found</p>
    </div>

    <!-- Loading -->
    <div id="loadingState" class="hidden px-5 py-8 text-center text-[#a8a093] text-sm">
      <p>Loading employees...</p>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-[#e8e0d0] flex justify-end gap-3">
      <button onclick="closeModal()" class="btn btn-ghost text-[#8b7a60]">
        Cancel
      </button>

      <button id="confirmBtn" disabled onclick="confirmSelection()" class="btn bg-[#2c2418] text-[#faf8f4] hover:bg-[#453a28] border-0">
        Confirm
      </button>
    </div>

  </div>

  <!-- Backdrop -->
  <form method="dialog" class="modal-backdrop">
    <button onclick="closeModal()">close</button>
  </form>

</dialog>


  
  <style>
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>


  <script>
    let employees = [];
    let tempSelected = null;
    let confirmed = null;
    let loading = false;

    // Update this to your Laravel API endpoint
    const API_URL = "/employees";

 

    function initials(name) { return name.split(' ').map(w => w[0]).join(''); }

    function getColor(index) {
      const colors = ["#6b8f71", "#7b6fa0", "#c27853", "#5a8a9f", "#b5844f", "#8b7355", "#a06b7a", "#5f7f6e"];
      return colors[index % colors.length];
    }

    function renderList(list) {
      const container = document.getElementById('empList');
      const noResults = document.getElementById('noResults');
      if (!list.length) { container.innerHTML = ''; noResults.classList.remove('hidden'); return; }
      noResults.classList.add('hidden');
      container.innerHTML = list.map((e, i) => `
        <div class="emp-item rounded-xl px-4 py-3 cursor-pointer flex items-center gap-3 ${tempSelected?.id === e.id ? 'selected' : ''}"
          onclick="selectEmployee(${e.id})" role="option" aria-selected="${tempSelected?.id === e.id}">
          <span class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-600 text-white shrink-0" style="background:${e.color || getColor(i)}">${e.FIRSTNAME.split(' ').map(n => n[0]).join('').toUpperCase()}</span>
          <div class="min-w-0">
            <div class="text-sm font-600 text-[#2c2418] truncate">${e.FIRSTNAME} ${e.MI} ${e.LASTNAME}</div>
            <div class="text-xs text-[#8b7a60] truncate">${e.POSITION || 'N/A'} · ${e['OFFICE/DIVISION'] || 'N/A'}</div>
          </div>
          ${tempSelected?.id === e.id ? '<i data-lucide="check" style="width:18px;height:18px;color:#8b7355;margin-left:auto;flex-shrink:0"></i>' : ''}
        </div>
      `).join('');
      lucide.createIcons();
    }

    function filterEmployees() {
      const q = document.getElementById('searchInput').value.toLowerCase();
      renderList(employees.filter(e => (e.FIRSTNAME + (e.LASTNAME || '') + (e.POSITION || '')).toLowerCase().includes(q)));
    }

    function selectEmployee(id) {
      tempSelected = employees.find(e => e.id === id);
      document.getElementById('confirmBtn').disabled = false;
      filterEmployees();
    }

    async function fetchEmployees() {

    // Set loading flag and show loading UI
    loading = true;
    document.getElementById('loadingState').classList.remove('hidden');

    // Clear current employee list
    document.getElementById('empList').innerHTML = '';

    try {

        // Call API with JSON accept header
        const response = await fetch(API_URL, {
        headers: { 'Accept': 'application/json' }
        });

        // Throw error if response is not successful
        if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse JSON response
        const data = await response.json();
        // console.log(data);

        /**
         * Normalize response:
         * - If API returns array مباشرة → use it
         * - If Laravel-style { data: [...] } → use data.data
         */
        employees = Array.isArray(data) ? data : (data.data || []);

        // Render employee list
        renderList(employees);

    } catch (error) {

        // Log error for debugging
        console.error('Failed to fetch employees:', error);

        // Show error message in UI
        document.getElementById('empList').innerHTML = `
        <div class="px-5 py-8 text-center text-red-600 text-sm">
            <i data-lucide="alert-circle" style="width:32px;height:32px;margin:0 auto 8px"></i>
            <p>Failed to load employees. Please check your API endpoint and try again.</p>
        </div>
        `;

        // Re-render Lucide icons
        lucide.createIcons();

    } finally {

        // Always remove loading state
        loading = false;
        document.getElementById('loadingState').classList.add('hidden');

    }
    }

    function openSignatoryModal() {
      tempSelected = confirmed;
      const modal = document.getElementById('modal');
      employee_modal.showModal();
      document.getElementById('searchInput').value = '';
      document.getElementById('confirmBtn').disabled = !tempSelected;
      if (!employees.length && !loading) {
        fetchEmployees();
      } else {
        renderList(employees);
      }
      setTimeout(() => document.getElementById('searchInput').focus(), 100);
    }

    function closeModal() {
      employee_modal.close();
      tempSelected = null;
    }

    function confirmSelection() {
      if (!tempSelected) return;

      confirmed = tempSelected;
   
      $('#signatory_name').val(`${confirmed.FIRSTNAME} ${confirmed.MI} ${confirmed.LASTNAME}`)
      $('#signatory_position').val(confirmed.POSITION)
      closeModal();
      lucide.createIcons();

    }

    function clearSelection() {
      confirmed = null;
      document.getElementById('selectedInput').value = '';
      document.getElementById('selectionBadge').classList.add('hidden');
    }

    lucide.createIcons();
  </script>

