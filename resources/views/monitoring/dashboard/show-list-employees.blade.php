{{-- ===================== MODAL ===================== --}}
<div id="trainingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl mx-4 flex flex-col max-h-[85vh]">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white poppins-bold"></h2>
                <p id="modalSubtitle" class="text-sm text-gray-400 mt-0.5"></p>
            </div>
            <button onclick="closeTrainingModal()" class="text-gray-400 hover:text-red-500 transition">
                <i data-lucide="x" style="width:22px;height:22px;"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-700">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="width:16px;height:16px;"></i>
                <input 
                    type="text" 
                    id="modalSearch" 
                    placeholder="Search by name, position, office..." 
                    class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-400"
                    oninput="filterModalTable()"
                >
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-y-auto flex-1 px-6 py-2">
            <div id="modalLoading" class="flex justify-center items-center py-10 hidden">
                <span class="loading loading-spinner loading-md text-emerald-500"></span>
                <span class="ml-2 text-sm text-gray-400">Loading...</span>
            </div>

            <table class="w-full text-sm" id="modalTable">
                <thead class="sticky top-0 bg-white dark:bg-gray-900">
                    <tr class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                        <th class="py-2 pr-4">#</th>
                        <th class="py-2 pr-4">Name</th>
                        <th class="py-2 pr-4">Position</th>
                        <th class="py-2 pr-4">Office/Division</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody id="modalTableBody" class="divide-y divide-gray-50 dark:divide-gray-800">
                </tbody>
            </table>

            <p id="modalNoResults" class="text-center text-sm text-gray-400 py-6 hidden">No results found.</p>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between" id="modalCount">
            <span id="modalCountText" class="text-xs text-gray-400"></span>
            <button 
                onclick="downloadModalCSV()" 
                id="downloadCSVBtn"
                class="flex items-center gap-2 px-4 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold transition disabled:opacity-50"
            >
                <i data-lucide="download" style="width:14px;height:14px;"></i>
                Download CSV
            </button>
        </div>
    </div>
</div>


<script>
    let _modalAllRows = []; // store all rows for client-side search

function openTrainingModal(type) {
    const isWith = type === 'with';

    // Set modal title
    document.getElementById('modalTitle').innerHTML = isWith
        ? `<span class="text-emerald-600">With Training</span> Employees`
        : `<span class="text-cyan-500">No Training</span> Employees`;

    document.getElementById('modalSubtitle').textContent = 'Based on current filter selection';
    document.getElementById('modalSearch').value = '';
    document.getElementById('modalTableBody').innerHTML = '';
    document.getElementById('modalNoResults').classList.add('hidden');
    document.getElementById('modalCountText').textContent = '';
    document.getElementById('modalLoading').classList.remove('hidden');
    document.getElementById('modalTable').classList.add('hidden');
    document.getElementById('trainingModal').classList.remove('hidden');

    // Re-use current filters
    const region = $('#region_select').val();
    const officeFilter = $('#office_filter').val();
    const types = [];
    $('.type_checkbox:checked').each(function () {
        types.push($(this).val());
    });

    $.ajax({
        url: '/employee-trainings/list',
        type: 'GET',
        data: {
            type: type,          // 'with' or 'no'
            region: region,
            types: types,
            office_filter: officeFilter
        },
        success: function (res) {
            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalTable').classList.remove('hidden');

            _modalAllRows = res.employees;
            renderModalRows(_modalAllRows);
            lucide.createIcons(); // re-render lucide icons if needed
        },
        error: function () {
            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalTableBody').innerHTML = `
                <tr><td colspan="5" class="text-center py-6 text-red-400 text-sm">Failed to load data.</td></tr>
            `;
            document.getElementById('modalTable').classList.remove('hidden');
        }
    });
}

function renderModalRows(employees) {
    const tbody = document.getElementById('modalTableBody');
    const noResults = document.getElementById('modalNoResults');
    const countText = document.getElementById('modalCountText'); // ✅ correct element

    if (employees.length === 0) {
        tbody.innerHTML = '';
        noResults.classList.remove('hidden');
        countText.textContent = '0 employees found';
        return;
    }

    noResults.classList.add('hidden');
    countText.textContent = `Showing ${employees.length} employee${employees.length !== 1 ? 's' : ''}`;

    tbody.innerHTML = employees.map((emp, i) => `
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <td class="py-2 pr-4 text-gray-400">${i + 1}</td>
            <td class="py-2 pr-4 font-medium text-gray-800 dark:text-white">
                ${emp.LASTNAME}, ${emp.FIRSTNAME} ${emp.MI ?? ''}
            </td>
            <td class="py-2 pr-4 text-gray-600 dark:text-gray-300">${emp.POSITION}</td>
            <td class="py-2 pr-4 text-gray-500 dark:text-gray-400 text-xs">${emp['OFFICE/DIVISION']}</td>
            <td class="py-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    ${emp['PLANTILLA STATUS'] === 'Permanent' 
                        ? 'bg-emerald-100 text-emerald-700' 
                        : 'bg-blue-100 text-blue-600'}">
                    ${emp['PLANTILLA STATUS']}
                </span>
            </td>
        </tr>
    `).join('');
}

function filterModalTable() {
    const search = document.getElementById('modalSearch').value.toLowerCase().trim();

    if (!search) {
        renderModalRows(_modalAllRows);
        return;
    }

    const filtered = _modalAllRows.filter(emp => {
        const fullname = `${emp.LASTNAME} ${emp.FIRSTNAME} ${emp.MI ?? ''}`.toLowerCase();
        const position = (emp.POSITION ?? '').toLowerCase();
        const office = (emp['OFFICE/DIVISION'] ?? '').toLowerCase();
        const status = (emp['PLANTILLA STATUS'] ?? '').toLowerCase();
        return fullname.includes(search) || position.includes(search) || office.includes(search) || status.includes(search);
    });

    renderModalRows(filtered);
}

function closeTrainingModal() {
    document.getElementById('trainingModal').classList.add('hidden');
    _modalAllRows = [];
}

// Close on backdrop click
document.getElementById('trainingModal').addEventListener('click', function (e) {
    if (e.target === this) closeTrainingModal();
});

function downloadModalCSV() {
    if (!_modalAllRows || _modalAllRows.length === 0) return;

    // Use currently filtered rows if search is active
    const search = document.getElementById('modalSearch').value.toLowerCase().trim();
    const rows = search
        ? _modalAllRows.filter(emp => {
            const fullname = `${emp.LASTNAME} ${emp.FIRSTNAME} ${emp.MI ?? ''}`.toLowerCase();
            const position = (emp.POSITION ?? '').toLowerCase();
            const office = (emp['OFFICE/DIVISION'] ?? '').toLowerCase();
            const status = (emp['PLANTILLA STATUS'] ?? '').toLowerCase();
            return fullname.includes(search) || position.includes(search)
                || office.includes(search) || status.includes(search);
        })
        : _modalAllRows;

    // Build CSV
    const headers = ['#', 'EMPCODE', 'Last Name', 'First Name', 'MI', 'Position', 'Office/Division', 'Plantilla Status', 'Region'];

    const csvRows = [
        headers.join(','),
        ...rows.map((emp, i) => [
            i + 1,
            `"${emp.EMPCODE ?? ''}"`,
            `"${emp.LASTNAME ?? ''}"`,
            `"${emp.FIRSTNAME ?? ''}"`,
            `"${emp.MI ?? ''}"`,
            `"${emp.POSITION ?? ''}"`,
            `"${(emp['OFFICE/DIVISION'] ?? '').replace(/"/g, '""')}"`,
            `"${(emp['PLANTILLA STATUS'] ?? '').replace(/"/g, '""')}"`,
            `"${emp.REGION ?? ''}"`,
        ].join(','))
    ];

    const csvContent = csvRows.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    // Filename: e.g. "with_training_employees_2025-06-03.csv"
    const modalType = document.getElementById('modalTitle').textContent.includes('With') ? 'with_training' : 'no_training';
    const date = new Date().toISOString().split('T')[0];
    const filename = `${modalType}_employees_${date}.csv`;

    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
</script>