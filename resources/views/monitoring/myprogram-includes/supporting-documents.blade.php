
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Header --}}
    <header class="max-w-full px-5 items-center mx-auto mb-8 flex justify-between md:flex-row md:items-center border-t border-slate-300 pt-4  gap-4">

        <div>
            <h1 class="poppins-bold text-slate-600 dark:text-slate-200 ">Supporting Documents</h1>
            <p class="text-xs poppins-regular text-slate-500">List of submitted supporting documents for this program</p>
        </div>
      
        <button id="add-btn"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shrink-0 inline-flex items-center gap-2 transition btn btn-sm">
            <i data-lucide="plus" style="width:16px;height:16px;"></i> Add Sup Document
        </button>
    </header>

    {{-- Alert --}}
    <div class="max-w-4xl mx-auto">
        <div id="alert-box" class="hidden mb-4 px-4 py-3 rounded-lg text-sm font-medium"></div>
    </div>

    {{-- Document List --}}
    <main id="doc-list" class="max-w-full mx-auto space-y-4 px-5 pb-5">
    </main>

    <div id="empty-state" class="hidden text-center py-16 text-gray-400 px-5">
        <i data-lucide="file-x" style="width:40px;height:40px;" class="mx-auto mb-3 opacity-40"></i>
        <p class="text-sm">No supporting documents yet. Click <strong>Add Sup Document</strong> to get started.</p>
    </div>

    {{-- Loading skeleton --}}
    <div id="loading-state" class="max-w-full px-5 mx-auto space-y-4">
        @for ($i = 0; $i < 2; $i++)
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm animate-pulse">
            <div class="grid grid-cols-3 gap-4">
                @for ($j = 0; $j < 6; $j++)
                <div>
                    <div class="h-2.5 bg-gray-200 rounded w-1/2 mb-2"></div>
                    <div class="h-3.5 bg-gray-200 rounded w-3/4"></div>
                </div>
                @endfor
            </div>
        </div>
        @endfor
    </div>

    {{-- ===================== ADD MODAL ===================== --}}
    <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/50 z-40"></div>
    <div id="add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800">Add Supporting Document</h2>
                <button id="close-modal" type="button"
                        class="text-gray-400 hover:text-gray-600 transition p-1" aria-label="Close modal">
                    <i data-lucide="x" style="width:20px;height:20px;"></i>
                </button>
            </div>

            <form id="add-form" class="space-y-3" novalidate>

                {{-- Subject --}}
                <div>
                    <label for="form-subject" class="text-sm font-medium text-gray-700 block mb-1">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input id="form-subject" type="text" placeholder="Enter document subject"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-subject"></p>
                </div>

                {{-- Document Type --}}
                <div>
                    <label for="form-type" class="text-sm font-medium text-gray-700 block mb-1">
                        Document Type <span class="text-red-500">*</span>
                    </label>
                    <select id="form-type"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">— Select type —</option>
                        <option>Memorandum</option>
                        <option>Memorandum Circular</option>
                        <option>TESDA Order</option>
                        <option>Office Order</option>
                        <option>Circular</option>
                        <option>Bulletin</option>
                        <option>Cluster Order</option>
                        <option>Advisory</option>
                    </select>
                    <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-document_type"></p>
                </div>

                {{-- Document Number + Series --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="form-number" class="text-sm font-medium text-gray-700 block mb-1">
                            Document No. <span class="text-red-500">*</span>
                        </label>
                        <input id="form-number" type="text" placeholder="e.g. 001"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-document_number"></p>
                    </div>
                    <div>
                        <label for="form-series" class="text-sm font-medium text-gray-700 block mb-1">
                            Series (Year) <span class="text-red-500">*</span>
                        </label>
                        <input id="form-series" type="number" placeholder="{{ date('Y') }}"
                               min="1900" max="{{ date('Y') + 5 }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-document_series"></p>
                    </div>
                </div>

                {{-- Origin + Date Issued --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="form-origin" class="text-sm font-medium text-gray-700 block mb-1">Origin</label>
                        <input id="form-origin" type="text" placeholder="e.g. CSC, DBM"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-origin"></p>
                    </div>
                    <div>
                        <label for="form-date" class="text-sm font-medium text-gray-700 block mb-1">Date Issued</label>
                        <input id="form-date" type="date"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-date_issued"></p>
                    </div>
                </div>

                {{-- Link --}}
                <div>
                    <label for="form-link" class="text-sm font-medium text-gray-700 block mb-1">Link to Document</label>
                    <input id="form-link" type="url" placeholder="https://example.com/document.pdf"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-red-500 text-xs mt-0.5 hidden err" id="err-link"></p>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 pt-2">
                    <button type="button" id="modal-cancel"
                            class="flex-1 px-4 py-2 rounded-lg font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-sm">
                        Cancel
                    </button>
                    <button type="submit" id="modal-save"
                            class="flex-1 px-4 py-2 rounded-lg font-medium bg-blue-600 hover:bg-blue-700 text-white transition text-sm inline-flex items-center justify-center gap-2">
                        <span id="save-text">Save Document</span>
                        <i data-lucide="loader-2" id="save-spinner" style="width:14px;height:14px;" class="hidden animate-spin "></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== DELETE CONFIRM MODAL ===================== --}}
    <div id="delete-backdrop" class="hidden fixed inset-0 bg-black/50 z-40"></div>
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm">
            <div class="flex items-start gap-3 mb-4">
                <div class="bg-red-100 p-2 rounded-full shrink-0">
                    <i data-lucide="trash-2" style="width:18px;height:18px;" class="text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Remove Document?</h3>
                    <p class="text-gray-500 text-sm mt-1">This action cannot be undone.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="delete-cancel"
                        class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-gray-600 font-medium text-sm hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button id="delete-confirm"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition">
                    Delete
                </button>
            </div>
        </div>
    </div>




<script>
lucide.createIcons();

// ── Config ───────────────────────────────────────────────────────────────
const PROGRAM_ID   = {{ $myprogram->id }};
const PROGRAM_CODE = "{{ $myprogram->program_code ?? '' }}";
const STORE_URL    = "{{ route('supporting-documents.store') }}";
const INDEX_URL    = "{{ route('supporting-documents.index', $myprogram->id) }}";
const DELETE_BASE  = "{{ url('supporting-documents') }}";
const CSRF = "{{ csrf_token() }}";

// ── State ────────────────────────────────────────────────────────────────
let pendingDeleteId = null;

// ── Helpers ──────────────────────────────────────────────────────────────
function esc(str) {
    const d = document.createElement('div');
    d.textContent = str ?? '';
    return d.innerHTML;
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', {
        year:  'numeric',
        month: 'long',
        day:   'numeric',
    });
}

function showAlert(type, message) {
    const box = document.getElementById('alert-box');
    box.className = type === 'success'
        ? 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-green-50 text-green-700 border border-green-200'
        : 'mb-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-50 text-red-700 border border-red-200';
    box.textContent = message;
    box.classList.remove('hidden');
    setTimeout(() => box.classList.add('hidden'), 4000);
}

function clearErrors() {
    document.querySelectorAll('.err').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    document.querySelectorAll('#add-form input, #add-form select').forEach(el => {
        el.classList.remove('border-red-400');
    });
}

function setFieldError(field, message) {
    const err = document.getElementById('err-' + field);
    if (!err) return;
    err.textContent = message;
    err.classList.remove('hidden');
}

// ── Build card HTML ───────────────────────────────────────────────────────
function buildCard(doc) {
    const linkHtml = doc.link
        ? `<a href="${esc(doc.link)}" target="_blank" rel="noopener noreferrer"
              class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition shrink-0 no-underline">
                <i data-lucide="external-link" style="width:12px;height:12px;"></i> View
           </a>`
        : '';

    return `
    <div data-doc-id="${doc.id}" class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3 text-sm">
                <div class="col-span-2 md:col-span-3">
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Subject</span>
                    <p class="font-semibold text-gray-800 mt-0.5">${esc(doc.subject)}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Type</span>
                    <p class="mt-0.5">
                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-medium">
                            ${esc(doc.document_type)}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Doc No.</span>
                    <p class="text-gray-700 mt-0.5">${esc(doc.document_number)}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Series</span>
                    <p class="text-gray-700 mt-0.5">${esc(doc.document_series)}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Origin</span>
                    <p class="text-gray-700 mt-0.5">${doc.origin ? esc(doc.origin) : '<span class="text-gray-300">—</span>'}</p>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-400 text-xs uppercase tracking-wide">Date Issued</span>
                    ${doc.date_issued ? formatDate(doc.date_issued) : '<span class="text-gray-300">—</span>'}
                </div>
            </div>
            <div class="flex items-center gap-2 justify-end w-30">
                ${linkHtml}
                <button onclick="confirmDelete(${doc.id})"
                        class="text-gray-300 hover:text-red-500 transition p-1 rounded" aria-label="Delete document">
                    <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                </button>
            </div>
        </div>
    </div>`;
}

// ── Append a single card to the list ─────────────────────────────────────
function appendCard(doc) {
    const list  = document.getElementById('doc-list');
    const empty = document.getElementById('empty-state');
    empty.classList.add('hidden');

    const div = document.createElement('div');
    div.innerHTML = buildCard(doc);
    const card = div.firstElementChild;
    list.appendChild(card);
    lucide.createIcons();
}

// ── Render full list ──────────────────────────────────────────────────────
function renderDocs(docs) {
    const list  = document.getElementById('doc-list');
    const empty = document.getElementById('empty-state');

    if (!docs.length) {
        list.innerHTML = '';
        list.appendChild(empty);
        empty.classList.remove('hidden');
    } else {
        empty.classList.add('hidden');
        list.innerHTML = docs.map(buildCard).join('');
        lucide.createIcons();
    }
}

// ── Fetch docs (initial load only) ───────────────────────────────────────
async function fetchDocs() {
    try {
        const res = await fetch(INDEX_URL, { headers: { Accept: 'application/json' } });
        if (!res.ok) throw new Error('Bad response ' + res.status);
        const json = await res.json();
        document.getElementById('loading-state').style.display = 'none';
        renderDocs(json.data);
    } catch (e) {
        document.getElementById('loading-state').style.display = 'none';
        // showAlert('error', 'Failed to load documents.');
        showToast('Failed to load documents.', 'error');
        console.error('fetchDocs error:', e);
    }
}

// ── Modal open/close ──────────────────────────────────────────────────────
function openModal() {
    document.getElementById('add-modal').classList.remove('hidden');
    document.getElementById('modal-backdrop').classList.remove('hidden');
    document.getElementById('add-form').reset();
    clearErrors();
    setTimeout(() => document.getElementById('form-subject').focus(), 100);
}

function closeModal() {
    document.getElementById('add-modal').classList.add('hidden');
    document.getElementById('modal-backdrop').classList.add('hidden');
    document.getElementById('add-form').reset();
    clearErrors();
}

// ── Save document (AJAX POST) — append card directly, no re-fetch ─────────
document.getElementById('add-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    clearErrors();

    const saveBtn     = document.getElementById('modal-save');
    const saveText    = document.getElementById('save-text');
    const saveSpinner = document.getElementById('save-spinner');

    saveBtn.disabled     = true;
    saveText.textContent = 'Saving…';
    saveSpinner.classList.remove('hidden');

    const body = new FormData();
    body.append('program_id',      PROGRAM_ID);
    body.append('program_code',    PROGRAM_CODE);
    body.append('subject',         document.getElementById('form-subject').value);
    body.append('document_type',   document.getElementById('form-type').value);
    body.append('document_number', document.getElementById('form-number').value);
    body.append('document_series', document.getElementById('form-series').value);
    body.append('origin',          document.getElementById('form-origin').value);
    body.append('date_issued',     document.getElementById('form-date').value);
    body.append('link',            document.getElementById('form-link').value);

    try {
        const res = await fetch(STORE_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
            body,
        });

        // DEBUG — remove after fixing
        const raw = await res.text();
        // console.log('STORE RAW RESPONSE:', raw);
        saveSpinner.classList.add('hidden');

        let json;
        try {
            json = JSON.parse(raw);
        } catch (e) {
            // showAlert('error', 'Server returned non-JSON: ' + raw.substring(0, 100));
            showToast('Server returned non-JSON: ' + raw.substring(0, 100), 'error');
            return;
        }

        if (res.status === 201 || res.status === 200) {
            closeModal();
            showToast( json.message ?? 'Document saved successfully.','success');
            appendCard(json.document);
        } else if (res.status === 422) {
            Object.entries(json.errors).forEach(([field, msgs]) => setFieldError(field, msgs[0]));
            // showAlert('error', 'Please fix the highlighted errors.');
            showToast('Please fix the highlighted errors.', 'error');
        } else {
            // showAlert('error', json.message ?? 'Something went wrong.');
            showToast(json.message ?? 'Something went wrong.', 'error');
        }
    } catch (err) {
        // showAlert('error', 'Network error. Please try again.');
        showToast('Network error. Please try again.', 'error');
        console.error('store error:', err);
    } finally {
        saveBtn.disabled     = false;
        saveText.textContent = 'Save Document';
        saveSpinner.classList.add('hidden');
    }
});

// ── Delete ────────────────────────────────────────────────────────────────
function confirmDelete(id) {
    pendingDeleteId = id;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-backdrop').classList.remove('hidden');
}

function closeDeleteModal() {
    pendingDeleteId = null;
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-backdrop').classList.add('hidden');
}

document.getElementById('delete-confirm').addEventListener('click', async function () {
    if (!pendingDeleteId) return;

    this.disabled    = true;
    this.textContent = 'Deleting…';

    try {
        const res  = await fetch(`${DELETE_BASE}/${pendingDeleteId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
        });
        const json = res.status !== 204 ? await res.json() : {};

        if (res.ok) {
            const card = document.querySelector(`[data-doc-id="${pendingDeleteId}"]`);
            if (card) card.remove();

            const remaining = document.querySelectorAll('#doc-list [data-doc-id]');
            if (!remaining.length) {
                const empty = document.getElementById('empty-state');
                document.getElementById('doc-list').appendChild(empty);
                empty.classList.remove('hidden');
            }

            closeDeleteModal();
            // showAlert('success', json.message ?? 'Document deleted.');
            showToast('Document deleted.', 'success');
        } else {
            // showAlert('error', json.message ?? 'Failed to delete.');
            showToast('Failed to delete.', 'error');
            closeDeleteModal();
        }
    } catch (err) {
        // showAlert('error', 'Network error.');
        showToast('Network error.', 'error');
        console.error('delete error:', err);
        closeDeleteModal();
    } finally {
        this.disabled    = false;
        this.textContent = 'Delete';
    }
});

// ── Event listeners ───────────────────────────────────────────────────────
document.getElementById('add-btn').addEventListener('click', openModal);
document.getElementById('close-modal').addEventListener('click', closeModal);
document.getElementById('modal-cancel').addEventListener('click', closeModal);
document.getElementById('modal-backdrop').addEventListener('click', closeModal);
document.getElementById('delete-cancel').addEventListener('click', closeDeleteModal);
document.getElementById('delete-backdrop').addEventListener('click', closeDeleteModal);

// ── Init ──────────────────────────────────────────────────────────────────
fetchDocs();
</script>

