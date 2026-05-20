<x-layout>
<x-slot:title>Resource Speakers</x-slot:title>

<x-monitoring-layout>
    @include('components.loading')

    {{-- ── HEADER ── --}}
    <div class="flex gap-4 p-5 border-b border-slate-300 dark:border-slate-600 items-center justify-between bg-white dark:bg-slate-800">
        <div class="flex-1">
            <a href="/programs/{{ $program->id }}" class="poppins-regular text-blue-500 text-[13px]">
                <i class="fa-solid fa-arrow-left-long"></i> Back to program
            </a>
            <h1 class="text-lg poppins-bold text-slate-900 dark:text-yellow-400">{{ $program->title }}</h1>
            <p class="leading-5 poppins-regular text-slate-500 text-[13px]">{{ $program->description }}</p>
        </div>

        <div class="flex gap-2">
            <button onclick="editProgModal({{ $program->id }})" class="btn btn-default shadow-xl rounded-box">
                <i class="fa-solid fa-pen"></i> Edit
            </button>
        </div>
    </div>

    {{-- ── TABS ── --}}
    <div class="TABS flex px-5 py-2 border-b border-slate-300 bg-slate-200 dark:bg-slate-800 dark:border-slate-600 gap-2">
        <a href="/programs/{{ $program->id }}"
           class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-solid fa-circle-info"></i> Details
        </a>
        <a href="/programs/{{ $program->id }}/participants"
           class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-solid fa-user-group"></i> Participants
        </a>
        <a href="/programs/{{ $program->id }}/submissions"
           class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-regular fa-file"></i> Submissions
        </a>
        <a href="/programs/{{ $program->id }}/requirements"
           class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-regular fa-file"></i> Requirements
        </a>
        
        <a href="/programs/{{ $program->id }}/resource-speakers"
           class="btn-default bg-white dark:bg-slate-600 hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-solid fa-chalkboard-teacher"></i> Resource Speakers
        </a>

        @if($program->tesdaOrders->isNotEmpty())
        <a href="/programs/{{ $program->id }}/tesda-order"
           class="btn-ghost hover:bg-white dark:hover:bg-slate-600 btn btn-sm poppins-semibold rounded-2xl shadow-none">
           <i class="fa-solid fa-file-lines text-indigo-600"></i> TESDA Order
        </a>
        @endif
    </div>

    {{-- ── RESOURCE SPEAKERS CONTENT ── --}}
    <div class="flex-1 overflow-auto px-5 py-4">

        {{-- No-batch warning --}}
        <div id="no-batch-alert" class="hidden mb-4 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-300 poppins-regular text-sm">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            This program has no existing batch yet. Please create a batch before adding a resource speaker.
        </div>

        {{-- Section header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="poppins-bold text-slate-700 dark:text-slate-200">Resource Speakers</h1>
                <p class="poppins-regular text-slate-500 dark:text-slate-400 text-[13px]">Manage speakers assigned to this program.</p>
            </div>
            <button id="btn-add-speaker"
                onclick="openAddModal()"
                class="btn btn-sm btn-info bg-blue-600 text-white rounded-lg shadow-none poppins-semibold">
                <i class="fa-solid fa-plus"></i> Add Speaker
            </button>
        </div>

        {{-- Cards grid --}}
        <div id="speakers-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            {{-- Skeleton loader shown while fetching --}}
            <div id="speakers-loading" class="col-span-full flex justify-center py-10 text-slate-400 poppins-regular text-sm">
                <span class="loading loading-spinner loading-sm mr-2"></span> Loading speakers...
            </div>
        </div>

    </div>

    {{-- ── ADD / EDIT MODAL ── --}}
    <dialog id="speakerModal" class="modal">
        <div class="modal-box p-0">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-slate-500">✕</button>
            </form>

            <div class="w-full bg-gradient-to-r from-sky-700 to-sky-800 text-white p-5">
                <h1 class="poppins-bold text-md" id="speakerModalLabel">Add Resource Speaker</h1>
            </div>

            <div class="p-5 flex flex-col gap-3">
                <input type="hidden" id="speaker-id">

                <fieldset class="fieldset p-0">
                    <legend class="fieldset-legend poppins-semibold">Name <span class="text-red-500">*</span></legend>
                    <input type="text" id="speaker-name"
                        class="input input-bordered w-full rounded-lg poppins-regular text-sm"
                        placeholder="Full name">
                    <p class="fieldset-label text-red-500 poppins-regular text-xs hidden" id="err-name"></p>
                </fieldset>

                <fieldset class="fieldset p-0">
                    <legend class="fieldset-legend poppins-semibold">Position</legend>
                    <input type="text" id="speaker-position"
                        class="input input-bordered w-full rounded-lg poppins-regular text-sm"
                        placeholder="e.g. Senior Trainer">
                    <p class="fieldset-label text-red-500 poppins-regular text-xs hidden" id="err-position"></p>
                </fieldset>

                <fieldset class="fieldset p-0">
                    <legend class="fieldset-legend poppins-semibold">Organization</legend>
                    <input type="text" id="speaker-organization"
                        class="input input-bordered w-full rounded-lg poppins-regular text-sm"
                        placeholder="e.g. TESDA Region IV">
                    <p class="fieldset-label text-red-500 poppins-regular text-xs hidden" id="err-organization"></p>
                </fieldset>

                <div class="grid grid-cols-2 gap-3">
                    <fieldset class="fieldset p-0">
                        <legend class="fieldset-legend poppins-semibold">Email</legend>
                        <input type="email" id="speaker-email"
                            class="input input-bordered w-full rounded-lg poppins-regular text-sm"
                            placeholder="email@example.com">
                        <p class="fieldset-label text-red-500 poppins-regular text-xs hidden" id="err-email"></p>
                    </fieldset>

                    <fieldset class="fieldset p-0">
                        <legend class="fieldset-legend poppins-semibold">Phone</legend>
                        <input type="text" id="speaker-phone"
                            class="input input-bordered w-full rounded-lg poppins-regular text-sm"
                            placeholder="09XXXXXXXXX">
                        <p class="fieldset-label text-red-500 poppins-regular text-xs hidden" id="err-phone"></p>
                    </fieldset>
                </div>
            </div>

            <div class="flex justify-end gap-2 px-5 pb-5">
                <button onclick="speakerModal.close()" class="btn btn-sm btn-ghost rounded-lg poppins-semibold">Cancel</button>
                <button id="btn-save-speaker" onclick="saveSpeaker()"
                    class="btn btn-sm btn-info bg-blue-600 text-white rounded-lg shadow-none poppins-semibold">
                    <span id="btn-save-text">Save Speaker</span>
                    <span id="btn-save-spinner" class="loading loading-spinner loading-xs hidden"></span>
                </button>
            </div>
        </div>
    </dialog>

    {{-- ── DELETE CONFIRM MODAL ── --}}
    <dialog id="deleteModal" class="modal">
        <div class="modal-box p-0 max-w-sm">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-slate-500">✕</button>
            </form>

            <div class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white p-5">
                <h1 class="poppins-bold text-md"><i class="fa-solid fa-trash mr-2"></i>Delete Speaker</h1>
            </div>

            <div class="p-5 text-center">
                <p class="poppins-regular text-slate-700 dark:text-slate-200 text-[14px]">
                    Are you sure you want to delete <strong id="delete-speaker-name"></strong>?
                </p>
                <p class="poppins-regular text-slate-400 text-xs mt-1">This action cannot be undone.</p>
            </div>

            <div class="flex justify-end gap-2 px-5 pb-5">
                <button onclick="deleteModal.close()" class="btn btn-sm btn-ghost rounded-lg poppins-semibold">Cancel</button>
                <button id="btn-confirm-delete" onclick="confirmDelete()"
                    class="btn btn-sm btn-error text-white rounded-lg shadow-none poppins-semibold">
                    <span id="btn-delete-text">Delete</span>
                    <span id="btn-delete-spinner" class="loading loading-spinner loading-xs hidden"></span>
                </button>
            </div>
        </div>
    </dialog>

    {{-- ── TOAST ── --}}
    <div id="toast-container" class="fixed bottom-5 right-5 flex flex-col gap-2 z-50"></div>

</x-monitoring-layout>
</x-layout>

{{-- ── JAVASCRIPT ── --}}
<script>
const PROGRAM_ID = {{ $program->id }};
const BASE_URL   = `/programs/${PROGRAM_ID}/resource-speakers`;
const CSRF       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentDeleteId = null;

// ── On load ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => loadSpeakers());

// ── Fetch speakers ────────────────────────────────────────────────
function loadSpeakers() {
    fetch(`${BASE_URL}/list`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        renderSpeakers(data.speakers);

        // No-batch warning + disable button
        const noBatch = !data.has_batches;
        document.getElementById('no-batch-alert').classList.toggle('hidden', !noBatch);

        const addBtn = document.getElementById('btn-add-speaker');
        addBtn.disabled = noBatch;
        if (noBatch) {
            addBtn.classList.add('btn-disabled');
            addBtn.title = 'Create a batch first before adding a speaker.';
        }
    });
}

// ── Render cards ──────────────────────────────────────────────────
function renderSpeakers(speakers) {
    const container = document.getElementById('speakers-container');
    container.innerHTML = '';

    if (speakers.length === 0) {
        container.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                <i class="fa-solid fa-user-slash text-4xl mb-3"></i>
                <p class="poppins-regular text-sm">No resource speakers added yet.</p>
            </div>`;
        return;
    }

    speakers.forEach(s => {
        const card = document.createElement('div');
        card.className = 'progitem border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 shadow-sm overflow-hidden';
        card.innerHTML = `
            <div class="border-l-4 border-blue-500 p-4 flex flex-col gap-1 h-full">
                <p class="poppins-bold text-slate-800 dark:text-slate-100 text-[14px]">
                    <i class="fa-solid fa-user-tie text-blue-500 mr-1"></i>${esc(s.name)}
                </p>
                ${s.position     ? `<p class="poppins-regular text-slate-500 dark:text-slate-400 text-[13px]">${esc(s.position)}</p>` : ''}
                ${s.organization ? `<p class="poppins-regular text-slate-500 dark:text-slate-400 text-[13px]"><i class="fa-solid fa-building mr-1"></i>${esc(s.organization)}</p>` : ''}
                ${s.email        ? `<p class="poppins-regular text-slate-500 dark:text-slate-400 text-[13px]"><i class="fa-solid fa-envelope mr-1"></i>${esc(s.email)}</p>` : ''}
                ${s.phone        ? `<p class="poppins-regular text-slate-500 dark:text-slate-400 text-[13px]"><i class="fa-solid fa-phone mr-1"></i>${esc(s.phone)}</p>` : ''}
                <div class="flex gap-2 mt-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                    <button onclick="openEditModal(${s.id})"
                        class="btn btn-xs btn-default rounded-lg poppins-semibold flex-1">
                        <i class="fa-solid fa-pen"></i> Edit
                    </button>
                    <button onclick="openDeleteModal(${s.id}, '${esc(s.name)}')"
                        class="btn btn-xs btn-error btn-soft rounded-lg poppins-semibold flex-1">
                        <i class="fa-regular fa-trash-can"></i> Delete
                    </button>
                </div>
            </div>`;
        container.appendChild(card);
    });
}

// ── Open Add Modal ────────────────────────────────────────────────
function openAddModal() {
    resetForm();
    document.getElementById('speakerModalLabel').textContent = 'Add Resource Speaker';
    document.getElementById('btn-save-text').textContent     = 'Save Speaker';
    document.getElementById('speaker-id').value              = '';
    speakerModal.showModal();
}

// ── Open Edit Modal ───────────────────────────────────────────────
function openEditModal(id) {
    resetForm();
    fetch(`${BASE_URL}/${id}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(({ speaker }) => {
        document.getElementById('speaker-id').value           = speaker.id;
        document.getElementById('speaker-name').value         = speaker.name         ?? '';
        document.getElementById('speaker-position').value     = speaker.position     ?? '';
        document.getElementById('speaker-organization').value = speaker.organization ?? '';
        document.getElementById('speaker-email').value        = speaker.email        ?? '';
        document.getElementById('speaker-phone').value        = speaker.phone        ?? '';
        document.getElementById('speakerModalLabel').textContent = 'Edit Resource Speaker';
        document.getElementById('btn-save-text').textContent     = 'Update Speaker';
        speakerModal.showModal();
    });
}

// ── Save ──────────────────────────────────────────────────────────
function saveSpeaker() {
    clearErrors();
    const speakerId = document.getElementById('speaker-id').value;
    const isEdit    = speakerId !== '';

    const payload = {
        _token:       CSRF,
        name:         document.getElementById('speaker-name').value.trim(),
        position:     document.getElementById('speaker-position').value.trim(),
        organization: document.getElementById('speaker-organization').value.trim(),
        email:        document.getElementById('speaker-email').value.trim(),
        phone:        document.getElementById('speaker-phone').value.trim(),
    };
    if (isEdit) payload._method = 'PUT';

    setLoading('save', true);

    fetch(isEdit ? `${BASE_URL}/${speakerId}` : BASE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        setLoading('save', false);
        if (data.success) {
            speakerModal.close();
            loadSpeakers();
            showToast(data.message, 'success');
        } else if (data.errors) {
            Object.entries(data.errors).forEach(([field, msgs]) => {
                const err = document.getElementById(`err-${field}`);
                if (err) { err.textContent = msgs[0]; err.classList.remove('hidden'); }
            });
        } else {
            showToast(data.message ?? 'Something went wrong.', 'error');
        }
    });
}

// ── Delete ────────────────────────────────────────────────────────
function openDeleteModal(id, name) {
    currentDeleteId = id;
    document.getElementById('delete-speaker-name').textContent = name;
    deleteModal.showModal();
}

function confirmDelete() {
    if (!currentDeleteId) return;
    setLoading('delete', true);

    fetch(`${BASE_URL}/${currentDeleteId}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ _token: CSRF }),
    })
    .then(r => r.json())
    .then(data => {
        setLoading('delete', false);
        deleteModal.close();
        if (data.success) { loadSpeakers(); showToast(data.message, 'success'); }
        else showToast(data.message ?? 'Failed to delete.', 'error');
        currentDeleteId = null;
    });
}

// ── Helpers ───────────────────────────────────────────────────────
function resetForm() {
    ['speaker-name','speaker-position','speaker-organization','speaker-email','speaker-phone']
        .forEach(id => document.getElementById(id).value = '');
    clearErrors();
}

function clearErrors() {
    ['err-name','err-position','err-organization','err-email','err-phone'].forEach(id => {
        const el = document.getElementById(id);
        el.textContent = '';
        el.classList.add('hidden');
    });
}

function setLoading(action, loading) {
    const textEl = document.getElementById(`btn-${action}-text`);
    const spinEl = document.getElementById(`btn-${action}-spinner`);
    const btnEl  = document.getElementById(action === 'save' ? 'btn-save-speaker' : 'btn-confirm-delete');
    textEl.classList.toggle('hidden', loading);
    spinEl.classList.toggle('hidden', !loading);
    btnEl.disabled = loading;
}

function esc(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str ?? ''));
    return d.innerHTML;
}

function showToast(message, type = 'success') {
    const colors = {
        success: 'alert-success',
        error:   'alert-error',
    };
    const toast = document.createElement('div');
    toast.className = `alert ${colors[type] ?? 'alert-info'} poppins-regular text-sm shadow-lg max-w-xs`;
    toast.innerHTML = `<span>${message}</span>`;
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}
</script>