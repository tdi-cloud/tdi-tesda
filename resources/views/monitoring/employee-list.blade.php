<style>

    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { transform: translateY(-1px); }
    .filter-chip.active { box-shadow: 0 0 0 2px currentColor; }
    .row-enter { animation: rowIn 0.3s ease forwards; opacity: 0; }
    @keyframes rowIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .table-row { transition: background 0.15s ease; }
    .table-row:hover { background: rgba(0,0,0,0.03); }
    input:focus, select:focus { outline: none; box-shadow: 0 0 0 2px #1a1a2e; }
    .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
    .page-btn { transition: all 0.15s ease; }
    .page-btn:hover:not(:disabled) { transform: translateY(-1px); }
    .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    
  </style>


<div class="p-4 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-2xl m-0 overflow-auto   overflow-hidden " >

    <main class="w-full flex flex-col justify-between gap-3  ">
    
    <!-- Controls Bar -->
    <div class="CONT-BAR flex flex-col lg:flex-row gap-4  ">
        
        
        <!-- Search -->
     <div class="relative flex-1 ">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2" style="width:18px;height:18px;"></i> 
    
        <input id="searchInput" type="text" placeholder="Search by Name, Office, or Empcode..." class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-sm bg-slate-200 dark:bg-slate-700 poppins-regular" >
     </div>
     
     <!-- Department Filter -->
     <div class="relative hidden">
        
        <label for="deptFilter" class="sr-only">Department</label> <select id="deptFilter" class="appearance-none pl-4 pr-10 py-3 rounded-xl border-0 text-sm cursor-pointer" style="background:#fff;"> <option value="">All Departments</option> </select> <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" style="width:16px;height:16px;color:#888;"></i>
     </div>
     <!-- Page Size -->
     
     <div class="flex items-center gap-2"><span class="text-xs font-medium opacity-60">Show</span> 
        <label for="pageSize" class="sr-only">Page size</label> 
        <select id="pageSize" class="appearance-none px-3 py-3 rounded-xl border-0 text-sm cursor-pointer bg-slate-200 dark:bg-slate-700"> 
            <option value="5">5</option> <option value="10" selected>10</option> 
            <option value="20">20</option> 
            <option value="50">50</option> 
        </select>
     </div>
    </div>
    
    <!-- Status Filter Chips -->
    <div class="flex flex-wrap gap-2 " role="group" aria-label="Employment status filters"><button class="filter-chip active badge py-2 px-4 cursor-pointer border-0 text-xs" data-status="all" style="background:#1a1a2e; color:#f0ede6;">All</button> <button class="filter-chip badge py-2 px-4 cursor-pointer border-0 text-xs" data-status="PERMANENT" style="background:#d4edda; color:#155724;"><span class="w-2 h-2 rounded-full mr-2" style="background:#28a745;display:inline-block;"></span>Permanent</button> <button class="filter-chip badge py-2 px-4 cursor-pointer border-0 text-xs" data-status="JOB ORDER" style="background:#fff3cd; color:#856404;"><span class="w-2 h-2 rounded-full mr-2" style="background:#ffc107;display:inline-block;"></span>Job Order</button> <button class="filter-chip badge py-2 px-4 cursor-pointer border-0 text-xs" data-status="CTO" style="background:#cce5ff; color:#004085;"><span class="w-2 h-2 rounded-full mr-2" style="background:#007bff;display:inline-block;"></span>CTO</button>
    </div><!-- Table Card -->

    <div class="rounded-2xl overflow-x-auto shadow-sm  h-[60vh]  " >

     

      <table class="w-full text-sm">
       <thead class="sticky top-0">
        <tr style="background:#1a1a2e; color:#f0ede6;">
         <th class="text-left px-5 py-3 font-semibold text-xs tracking-wider uppercase opacity-80">EMPCODE</th>
         <th class="text-left px-5 py-3 font-semibold text-xs tracking-wider uppercase opacity-80">Employee</th>
         <th class="text-left px-5 py-3 font-semibold text-xs tracking-wider uppercase opacity-80">PLANTILLA</th>
         <th class="text-left px-5 py-3 font-semibold text-xs tracking-wider uppercase opacity-80 hidden lg:table-cell">Action</th>
        </tr>
       </thead>
       <tbody id="tableBody">
        
       </tbody>
      </table>


     

     
     
     <!-- Empty State -->
     <div id="emptyState" class="hidden text-center py-5 px-4"><i data-lucide="users" style="width:48px;height:48px;color:#ccc;margin:0 auto 12px;display:block;"></i>
      <p class="poppins-semibold text-lg" style="color:#555;">No employees found</p>
      <p class="text-sm opacity-50 mt-1">Try adjusting your search or filters</p>
     </div>

     
    </div>


   </main>

   
   

   

</div>

<!-- Pagination -->
    <div class="mt-2 flex flex-col sm:flex-row items-center justify-between gap-4 ">
     <p id="pageInfo" class="text-xs mono text-slate-500"></p>
     <div class="flex items-center gap-1" id="paginationControls"></div>
    </div>



<script>



let currentPage = 1;
let activeStatus = 'all';

/* =========================
   LOAD DATA FROM LARAVEL
========================= */
function loadEmployees(page = 1) {

    let search = document.getElementById('searchInput').value;
    let dept = document.getElementById('deptFilter').value;
    let perPage = document.getElementById('pageSize').value;

    $.ajax({
        url: '/employees-data',
        type: 'GET',
        data: {
            page: page,
            search: search,
            dept: dept,
            per_page: perPage,
            status: activeStatus
        },
        success: function (res) {

            renderTable(res.data);
            renderPagination(res);
            renderPageInfo(res);

            currentPage = res.current_page;
        }
    });
}

/* =========================
   RENDER TABLE (GRID ROWS)
========================= */
function renderTable(data) {

    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    if (!data.length) {
        document.getElementById('emptyState').classList.remove('hidden');
        return;
    } else {
        document.getElementById('emptyState').classList.add('hidden');
    }

    data.forEach((emp, i) => {
        const s = statusStyles[emp['PLANTILLA STATUS']];
        const ac = avatarColors[emp.FIRSTNAME.length % avatarColors.length];
        const tr = document.createElement('tr');

        tr.className = 'border-b border-slate-400 dark:border-slate-600';
        tr.innerHTML = `
            <td class="px-5 py-4 mono text-xs poppins-regular">${emp.EMPCODE}</td>

            <td class="px-5 py-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold"
                        style="background:${ac};">
                        ${emp.FIRSTNAME.substring(0,2).toUpperCase()}
                    </div>

                    <div>
                        <p class="poppins-semibold">${emp.FIRSTNAME} ${emp.MI} ${emp.LASTNAME}</p>
                        <p class="text-xs ">${emp['OFFICE/DIVISION']}</p>
                    </div>
                </div>
            </td>


            <td class="px-5 py-4">
                <span class="badge ${emp['PLANTILLA STATUS']}" style="background:${s.bg};color:${s.color};">
                    ${emp['PLANTILLA STATUS']}
                </span>
            </td>

            <td class="px-5 py-4 hidden lg:table-cell mono text-xs">
                <button  class="btn btn-sm rounded-lg btn-default"><i class="fa-regular fa-user"></i> View Details<button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

/* =========================
   RENDER PAGINATION
========================= */
function renderPagination(res) {

    const container = document.getElementById('paginationControls');
    container.innerHTML = '';

    const totalPages = res.last_page;
    const current = res.current_page;

    const createBtn = (label, page, disabled = false) => {
        const btn = document.createElement('button');

        btn.textContent = label;
        btn.disabled = disabled;

        btn.className = 'px-3 py-2 text-xs border rounded';

        if (page === current) {
            btn.style.background = '#1a1a2e';
            btn.style.color = '#fff';
        }

        btn.onclick = () => {
            loadEmployees(page);
        };

        return btn;
    };

    // Previous
    container.appendChild(createBtn('‹', current - 1, current === 1));

    // Page numbers (max 5)
    let start = Math.max(1, current - 2);
    let end = Math.min(totalPages, start + 4);
    start = Math.max(1, end - 4);

    for (let i = start; i <= end; i++) {
        container.appendChild(createBtn(i, i));
    }

    // Next
    container.appendChild(createBtn('›', current + 1, current === totalPages));
}

/* =========================
   PAGE INFO
========================= */
function renderPageInfo(res) {

    const info = document.getElementById('pageInfo');

    if (res.total === 0) {
        info.textContent = 'No results';
        return;
    }

    info.textContent = `Showing ${res.from}–${res.to} of ${res.total}`;
}

/* =========================
   SEARCH
========================= */
document.getElementById('searchInput').addEventListener('input', () => {
    loadEmployees(1);
});

/* =========================
   DEPARTMENT FILTER
========================= */
document.getElementById('deptFilter').addEventListener('change', () => {
    loadEmployees(1);
});

/* =========================
   PAGE SIZE
========================= */
document.getElementById('pageSize').addEventListener('change', () => {
    loadEmployees(1);
});

/* =========================
   STATUS FILTER (CHIPS)
========================= */
document.querySelectorAll('.filter-chip').forEach(btn => {

    btn.addEventListener('click', () => {

        document.querySelectorAll('.filter-chip')
            .forEach(b => b.classList.remove('active'));

        btn.classList.add('active');

        activeStatus = btn.dataset.status;

        loadEmployees(1);
    });
});

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', () => {
    loadEmployees();
});

const statusStyles = {
  "PERMANENT": { bg: "#d4edda", color: "#155724" },
  "JOB ORDER": { bg: "#fff3cd", color: "#856404" },
  "CTI":       { bg: "#cce5ff", color: "#004085" },
};
const avatarColors = ["#e0a458","#5b8a72","#7b6fa6","#c0695c","#4a8db7","#9b8e5e"];





lucide.createIcons();
</script>
