{{-- === MODAL: No Completers Warning === --}}
<dialog id="modal_no_completers" class="modal">
    <div class="modal-box max-w-sm">
        <div class="flex flex-col items-center gap-3 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-warning" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71
                         3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <h3 class="font-bold text-lg text-center">Feature Unavailable</h3>
            <p class="text-center text-sm text-base-content/70">
                The <strong>Declaration of Completers</strong> can only be generated
                when at least one participant has a <strong>Complete</strong> attendance status.
            </p>
        </div>
        <div class="modal-action justify-center">
            <form method="dialog">
                <button class="btn btn-neutral btn-sm">Understood</button>
            </form>
        </div>
    </div>
</dialog>


{{-- === MODAL: Signatory Selection === --}}
<dialog id="modal_signatory" class="modal">
    <div class="modal-box max-w-lg">
        <h3 class="font-bold text-lg mb-4">Declaration of Completers</h3>
        <p class="text-sm text-base-content/70 mb-4">
            Search and select the signatory for this document.
        </p>

        {{-- Signatory Search --}}
        <div class="form-control mb-2">
            <label class="label">
                <span class="label-text font-semibold">Search Signatory</span>
            </label>
            <input
                type="text"
                id="signatory_search"
                placeholder="Type name or employee code..."
                class="input input-bordered w-full"
                autocomplete="off"
            />
            {{-- Dropdown results --}}
            <div id="signatory_results"
                 class="bg-base-100 border border-base-300 rounded-box shadow-lg mt-1 hidden z-50 max-h-48 overflow-y-auto">
            </div>
        </div>

        {{-- Selected Signatory Display --}}
        <div id="signatory_selected" class="hidden">
            <div class="divider text-xs">Selected Signatory</div>
            <div class="bg-base-200 rounded-box p-3 mb-4">
                <p class="font-bold text-sm" id="display_signatory_name">—</p>
                <p class="text-xs text-base-content/70" id="display_signatory_position">—</p>
            </div>
        </div>

        {{-- Hidden inputs --}}
        <input type="hidden" id="signatory_name" />
        <input type="hidden" id="signatory_position" />
        <input type="hidden" id="declaration_batch_id" />

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost btn-sm">Cancel</button>
            </form>
            <button
                id="btn_generate_pdf"
                class="btn btn-primary btn-sm"
                disabled
                onclick="generateDeclarationPdf()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4
                             4m0 0l-4-4m4 4V4"/>
                </svg>
                Generate PDF
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>


<script>
    // ─── STEP 1: Button click — check if batch has completers ───────────────
    function openDeclarationModal(batchId) {
        fetch(`/batches/${batchId}/check-completers`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.has_completers) {
                document.getElementById('modal_no_completers').showModal();
            } else {
                document.getElementById('declaration_batch_id').value = batchId;
                resetSignatoryFields();
                document.getElementById('modal_signatory').showModal();
            }
        })
        .catch(() => {
            alert('An error occurred. Please try again.');
        });
    }

    // ─── STEP 2: Signatory search with debounce ──────────────────────────────
    let searchTimeout = null;

    document.getElementById('signatory_search').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const q = this.value.trim();

        if (q.length < 2) {
            document.getElementById('signatory_results').classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/employees/declaration/search?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(employees => {
                renderSignatoryResults(employees);
            })
            .catch(() => {
                document.getElementById('signatory_results').classList.add('hidden');
            });
        }, 300);
    });

    function renderSignatoryResults(employees) {
        const container = document.getElementById('signatory_results');

        if (!employees.length) {
            container.innerHTML = `
                <div class="p-3 text-sm text-base-content/50 text-center">
                    No employees found.
                </div>`;
            container.classList.remove('hidden');
            return;
        }

        container.innerHTML = employees.map(emp => `
            <div class="px-4 py-2 hover:bg-base-200 cursor-pointer text-sm border-b border-base-200 last:border-0"
                 onclick="selectSignatory('${escapeHtml(emp.fullname)}', '${escapeHtml(emp.position)}')">
                <p class="font-semibold">${escapeHtml(emp.fullname)}</p>
                <p class="text-xs text-base-content/60">${escapeHtml(emp.position)}</p>
            </div>
        `).join('');

        container.classList.remove('hidden');
    }

    function selectSignatory(name, position) {
        document.getElementById('signatory_name').value     = name;
        document.getElementById('signatory_position').value = position;
        document.getElementById('display_signatory_name').textContent     = name;
        document.getElementById('display_signatory_position').textContent = position;
        document.getElementById('signatory_selected').classList.remove('hidden');
        document.getElementById('signatory_results').classList.add('hidden');
        document.getElementById('signatory_search').value  = name;
        document.getElementById('btn_generate_pdf').disabled = false;
    }

    function resetSignatoryFields() {
        document.getElementById('signatory_search').value       = '';
        document.getElementById('signatory_name').value         = '';
        document.getElementById('signatory_position').value     = '';
        document.getElementById('signatory_results').classList.add('hidden');
        document.getElementById('signatory_selected').classList.add('hidden');
        document.getElementById('btn_generate_pdf').disabled    = true;
    }

    // ─── STEP 3: Open PDF in new tab ─────────────────────────────────────────
    function generateDeclarationPdf() {
        const batchId  = document.getElementById('declaration_batch_id').value;
        const name     = document.getElementById('signatory_name').value;
        const position = document.getElementById('signatory_position').value;

        if (!name || !position) {
            alert('Please select a signatory first.');
            return;
        }

        const url = `/batches/${batchId}/declaration-pdf?signatory_name=${encodeURIComponent(name)}&signatory_position=${encodeURIComponent(position)}`;
        window.open(url, '_blank');

        document.getElementById('modal_signatory').close();
    }

    // ─── Utility ─────────────────────────────────────────────────────────────
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#signatory_search') && !e.target.closest('#signatory_results')) {
            document.getElementById('signatory_results').classList.add('hidden');
        }
    });
</script>