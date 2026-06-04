{{-- Competency Modal --}}
<div id="competency-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-3xl mx-4 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="poppins-semibold text-slate-700 dark:text-slate-100 text-[15px]">
                <i class="fa-regular fa-lightbulb text-yellow-500 mr-1"></i> Competencies
            </h2>
            <button onclick="closeCompetencyModal()" class="btn btn-xs btn-circle btn-ghost text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Two-column layout: Picker left, Added list right --}}
        <div class="flex divide-x divide-slate-200 dark:divide-slate-700" style="height: 420px;">

            {{-- LEFT: Picker --}}
            <div class="flex flex-col w-1/2">
                <div class="px-3 pt-3 pb-2">
                    <input id="comp-search" type="text" placeholder="Search..."
                        class="input input-sm input-bordered rounded-xl w-full text-[13px]"
                        oninput="filterCompetencies()">
                </div>

                <div id="comp-options" class="flex-1 overflow-y-auto px-3 pb-3 space-y-3">
                    {{-- Rendered by JS --}}
                </div>

                <div class="px-3 py-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span id="selected-count" class="text-[12px] text-slate-400">0 selected</span>
                    <button onclick="addCompetency()" class="btn btn-sm btn-primary rounded-xl px-4">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
            </div>

            {{-- RIGHT: Added list --}}
            <div class="flex flex-col w-1/2">
                <p class="px-3 pt-3 pb-2 text-[11px] poppins-semibold text-slate-400 uppercase tracking-wide">
                    Added <span id="added-count" class="ml-1 text-blue-500">{{ count($competencies) }}</span>
                </p>

                <div id="comp-list" class="flex-1 overflow-y-auto px-3 pb-3 space-y-2">
                    @forelse($competencies as $comp)
                        <div id="comp-row-{{ $comp->id }}" class="flex items-start justify-between gap-1 p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                            <div class="min-w-0">
                                <p class="poppins-medium text-[12px] text-slate-700 dark:text-slate-200 leading-4">{{ $comp->competency }}</p>
                                @if($comp->domain)
                                    <span class="text-[10px] text-blue-500 poppins-semibold">{{ $comp->domain }}</span>
                                @endif
                            </div>
                            <button onclick="deleteCompetency({{ $comp->id }})"
                                class="btn btn-xs btn-circle btn-error btn-soft shrink-0 mt-0.5">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    @empty
                        <p id="empty-msg" class="text-center text-[12px] text-slate-400 py-6">
                            <i class="fa-solid fa-triangle-exclamation text-red-400 block text-lg mb-1"></i>
                            Nothing added yet.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
const COMPETENCIES = [
  { domain: "Leadership", competency: "Practice Strategic and Critical Thinking (PSCT)" },
  { domain: "Leadership", competency: "Drive Performance for Integrity and Service (DPIS)" },
  { domain: "Leadership", competency: "Establish Linkages and Networking for Programs and Services (ELN)" },
  { domain: "Leadership", competency: "Plan and Organize for Greater Impact (POGI)" },
  { domain: "Leadership", competency: "Lead in a Continuously Changing Environment (LCCE)" },
  { domain: "Leadership", competency: "Develop and Empower Others to Establish Collective Accountability for Results (DEO)" },
  { domain: "Core", competency: "Exemplify Integrity" },
  { domain: "Core", competency: "Deliver Service Excellence (DSE)" },
  { domain: "Core", competency: "Solve Problems and Make Decisions (SPMD)" },
  { domain: "Core", competency: "Work Effectively in TVET (WETE)" },
  { domain: "Organizational", competency: "Deliver Programs and Services" },
  { domain: "Organizational", competency: "Develop Lifelong Learning and Career Development Interventions (DLLCDI)" },
  { domain: "Organizational", competency: "Write Effectively (WE)" },
  { domain: "Organizational", competency: "Speak Effectively (SE)" },
  { domain: "Organizational", competency: "Promote Learning and Innovation (PLI)" },
  { domain: "Organizational", competency: "Establish Teamwork (ET)" },
  { domain: "Technical", competency: "Financial Management - Accounting Competencies" },
  { domain: "Technical", competency: "Financial Management - Budgeting Competencies" },
  { domain: "Technical", competency: "Financial Management - Cash Management Competencies" },
  { domain: "Technical", competency: "Financial Management - Procurement Competencies" },
  { domain: "Technical", competency: "Financial Management - Financial Reporting and Analysis" },
  { domain: "Technical", competency: "HRM - Training and Development Competencies" },
  { domain: "Technical", competency: "HRM - Performance Management Competencies" },
  { domain: "Technical", competency: "HRM - Talent Acquisition Competencies" },
  { domain: "Technical", competency: "HRM - Presentation Skills" },
  { domain: "Technical", competency: "Information Technology" },
  { domain: "Technical", competency: "Effective Partnerships and Networking" },
  { domain: "Technical", competency: "Planning and Execution Competencies" },
  { domain: "Technical", competency: "Program Development and Management" },
  { domain: "Technical", competency: "Quality Management and Assurance" },
  { domain: "Technical", competency: "Standards Development" },
  { domain: "TTI", competency: "Conduct competency assessment" },
  { domain: "TTI", competency: "Develop learning materials" },
  { domain: "TTI", competency: "Develop learning materials for e-learning" },
  { domain: "TTI", competency: "Develop training curriculum" },
  { domain: "TTI", competency: "Implement enrolment systems and procedures" },
  { domain: "TTI", competency: "Evaluate training/learning effectiveness" },
  { domain: "TTI", competency: "Facilitate development of competency standards" },
  { domain: "TTI", competency: "Formulate institutional policies, guidelines and procedures" },
  { domain: "TTI", competency: "Facilitate learning sessions" },
  { domain: "TTI", competency: "Apply facilitation skills" },
  { domain: "TTI", competency: "Perform guidance services" },
  { domain: "TTI", competency: "Implement workplace health, safety, security practices and environmental requirements" },
  { domain: "TTI", competency: "Manage library" },
  { domain: "TTI", competency: "Manage training institution" },
  { domain: "TTI", competency: "Apply planning, organizing and delivering skills" },
  { domain: "TTI", competency: "Plan training sessions" },
  { domain: "TTI", competency: "Apply presentation skills" },
  { domain: "TTI", competency: "Generate resources" },
  { domain: "TTI", competency: "Supervise work-based learning" },
  { domain: "TTI", competency: "Conduct training needs assessment" },
];

const existingCompetencies = @json($competencies->pluck('competency')->toArray());
let dirty = false;
let filtered = [...COMPETENCIES];

function renderOptions() {
    const container = document.getElementById('comp-options');
    const groups = {};
    filtered.forEach((item, idx) => {
        if (!groups[item.domain]) groups[item.domain] = [];
        groups[item.domain].push({ ...item, idx });
    });

    if (Object.keys(groups).length === 0) {
        container.innerHTML = `<p class="text-center text-[12px] text-slate-400 py-4">No results.</p>`;
        return;
    }

    const domainColors = {
        'Leadership':    'text-purple-500',
        'Core':          'text-blue-500',
        'Organizational':'text-teal-500',
        'Technical':     'text-orange-500',
        'TTI':           'text-pink-500',
    };

    container.innerHTML = Object.entries(groups).map(([domain, items]) => `
        <div>
            <p class="text-[10px] poppins-semibold uppercase tracking-wide mb-1 ${domainColors[domain] ?? 'text-slate-400'}">${domain}</p>
            <div class="space-y-0.5">
                ${items.map(item => {
                    const added = existingCompetencies.includes(item.competency);
                    return `
                    <label class="flex items-start gap-2 px-2 py-1 rounded-lg
                        ${added ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-slate-700 cursor-pointer'}">
                        <input type="checkbox" class="comp-checkbox mt-0.5 accent-blue-600"
                            value="${item.idx}"
                            data-domain="${item.domain}"
                            data-competency="${item.competency}"
                            ${added ? 'disabled' : ''}
                            onchange="updateCount()">
                        <span class="text-[12px] text-slate-700 dark:text-slate-200 leading-4">
                            ${item.competency}
                            ${added ? '<i class="fa-solid fa-check text-green-500 ml-1 text-[10px]"></i>' : ''}
                        </span>
                    </label>`;
                }).join('')}
            </div>
        </div>
    `).join('');
}

function filterCompetencies() {
    const q = document.getElementById('comp-search').value.toLowerCase();
    filtered = COMPETENCIES.filter(i =>
        i.competency.toLowerCase().includes(q) || i.domain.toLowerCase().includes(q)
    );
    renderOptions();
    updateCount();
}

function updateCount() {
    const n = document.querySelectorAll('.comp-checkbox:checked').length;
    document.getElementById('selected-count').textContent = `${n} selected`;
}

function updateAddedCount() {
    const n = document.querySelectorAll('#comp-list [id^="comp-row-"]').length;
    document.getElementById('added-count').textContent = n;
}

function openCompetencyModal() {
    filtered = [...COMPETENCIES];
    renderOptions();
    document.getElementById('competency-modal').classList.remove('hidden');
    document.getElementById('competency-modal').classList.add('flex');
}

function closeCompetencyModal() {
    document.getElementById('competency-modal').classList.add('hidden');
    document.getElementById('competency-modal').classList.remove('flex');
    document.getElementById('comp-search').value = '';
    if (dirty) {
        dirty = false;
        window.location.reload();
    }
}

document.getElementById('competency-modal').addEventListener('click', function(e) {
    if (e.target === this) closeCompetencyModal();
});

function addCompetency() {
    const checked = [...document.querySelectorAll('.comp-checkbox:checked')];
    if (!checked.length) { alert('Please select at least one competency.'); return; }

    const selected = checked.map(el => ({
        domain: el.dataset.domain,
        competency: el.dataset.competency
    }));

    $.ajax({
        url: '/program/{{ $myprogram->id }}/competency/batch',
        type: 'POST',
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: JSON.stringify({ competencies: selected }),
        success: function(response) {
            dirty = true;
            document.getElementById('empty-msg')?.remove();

            response.forEach(comp => {
                existingCompetencies.push(comp.competency);
                const html = `
                    <div id="comp-row-${comp.id}" class="flex items-start justify-between gap-1 p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                        <div class="min-w-0">
                            <p class="poppins-medium text-[12px] text-slate-700 dark:text-slate-200 leading-4">${comp.competency}</p>
                            <span class="text-[10px] text-blue-500 poppins-semibold">${comp.domain}</span>
                        </div>
                        <button onclick="deleteCompetency(${comp.id})" class="btn btn-xs btn-circle btn-error btn-soft shrink-0 mt-0.5">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>`;
                document.getElementById('comp-list').insertAdjacentHTML('beforeend', html);
            });

            renderOptions();
            updateCount();
            updateAddedCount();
            refreshTicker();
        },
        error: function() { alert('Something went wrong.'); }
    });
}

function deleteCompetency(id) {
    if (!confirm('Delete this competency?')) return;
    $.ajax({
        url: '/program/competency/' + id,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            dirty = true;
            const row = document.getElementById('comp-row-' + id);
            const compName = row?.querySelector('p')?.textContent?.trim();
            const idx = existingCompetencies.indexOf(compName);
            if (idx > -1) existingCompetencies.splice(idx, 1);
            row?.remove();

            if (!document.querySelector('#comp-list [id^="comp-row-"]')) {
                document.getElementById('comp-list').innerHTML = `
                    <p id="empty-msg" class="text-center text-[12px] text-slate-400 py-6">
                        <i class="fa-solid fa-triangle-exclamation text-red-400 block text-lg mb-1"></i>
                        Nothing added yet.
                    </p>`;
            }
            renderOptions();
            updateAddedCount();
            refreshTicker();
        },
        error: function() { alert('Something went wrong.'); }
    });
}

function refreshTicker() {
    $.get(window.location.href, function(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTicker = doc.querySelector('.competency-ticker-wrap');
        const currentTicker = document.querySelector('.competency-ticker-wrap');
        if (newTicker && currentTicker) currentTicker.replaceWith(newTicker);
    });
}
</script>