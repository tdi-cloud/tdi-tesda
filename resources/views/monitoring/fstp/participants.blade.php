<x-layout>
<x-slot:title>FSTP Nomination</x-slot:title>
<x-monitoring-layout>



<div class="px-4 sm:px-6 lg:px-8 py-6">
 
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
        <a href="{{ route('foreign-programs.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
            Foreign Programs
        </a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 dark:text-gray-200 font-medium truncate max-w-xs">{{ $program->program_title }}</span>
    </nav>
 
    {{-- Program Summary Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-5 mb-6 text-white shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold leading-tight">{{ $program->program_title }}</h1>
                <p class="text-blue-100 text-sm mt-1">{{ $program->organizing_sponsor }}</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                @php
                    $modalityColor = match($program->modality) {
                        'hybrid'    => 'bg-purple-500/30 text-purple-100 border-purple-400/40',
                        'online'    => 'bg-sky-500/30 text-sky-100 border-sky-400/40',
                        default     => 'bg-emerald-500/30 text-emerald-100 border-emerald-400/40',
                    };
                @endphp
                <span class="px-2.5 py-1 rounded-full border font-medium {{ $modalityColor }}">
                    {{ ucfirst($program->modality) }}
                </span>
                <span class="px-2.5 py-1 rounded-full bg-white/20 border border-white/30 font-medium">
                    {{ $program->status_label }}
                </span>
            </div>
        </div>
        <div class="mt-3 flex flex-wrap gap-x-6 gap-y-1 text-sm text-blue-100">
            <span>
                <span class="opacity-70">Period:</span>
                {{ $program->program_start?->format('M d, Y') }} – {{ $program->program_end?->format('M d, Y') }}
            </span>
            <span>
                <span class="opacity-70">Slots:</span> {{ $program->slots }}
            </span>
            <span>
                <span class="opacity-70">Participants:</span>
                <strong class="text-white">{{ $participants->total() }}</strong>
            </span>
            @if($program->submission_date)
            <span>
                <span class="opacity-70">Submission:</span> {{ $program->submission_date->format('M d, Y') }}
            </span>
            @endif
        </div>
    </div>
 
    {{-- Page actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Participants</h2>
        <button onclick="openAddParticipantModal()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Participant
        </button>
    </div>
 
    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
 
    {{-- Search --}}
    <form method="GET" action="{{ route('foreign-programs.participants.index', $program) }}"
          class="flex gap-3 mb-5">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name, agency, position…"
                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400"/>
        </div>
        <button type="submit"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg border border-gray-300 transition dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('foreign-programs.participants.index', $program) }}"
           class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-500 text-sm rounded-lg border border-gray-300 transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
            Clear
        </a>
        @endif
    </form>
 
    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3 text-left font-semibold">#</th>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Sex</th>
                        <th class="px-4 py-3 text-left font-semibold">Position</th>
                        <th class="px-4 py-3 text-left font-semibold">Agency</th>
                        <th class="px-4 py-3 text-left font-semibold">Contact No.</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($participants as $i => $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                        <td class="px-4 py-3 text-gray-400 dark:text-gray-500">
                            {{ $participants->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            {{ $p->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300 capitalize">
                            {{ $p->sex }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $p->position }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $p->agency }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $p->contact_no ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            @if($p->email)
                                <a href="mailto:{{ $p->email }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $p->email }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $pStatusColor = match($p->status) {
                                    'endorsed'       => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                                    'waiting_result' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                    'not_endorsed'   => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                    'accepted'       => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                    'regret'         => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                    'cancelled'      => 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                    default          => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $pStatusColor }}">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditParticipantModal({{ $program->id }}, {{ $p->id }})"
                                    class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/30"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST"
                                      action="{{ route('foreign-programs.participants.destroy', [$program, $p]) }}"
                                      onsubmit="return confirm('Remove this participant?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/30"
                                        title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm">No participants yet. Add one!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participants->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
            {{ $participants->links() }}
        </div>
        @endif
    </div>
</div>
 
{{-- ============ ADD PARTICIPANT MODAL ============ --}}
<div id="addParticipantModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAddParticipantModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Add Participant</h2>
                <button onclick="closeAddParticipantModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('foreign-programs.participants.store', $program) }}">
                @csrf
                @include('monitoring.fstp._participant_form', ['participant' => null])
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeAddParticipantModal()"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                        Add Participant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
{{-- ============ EDIT PARTICIPANT MODAL ============ --}}
<div id="editParticipantModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditParticipantModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Edit Participant</h2>
                <button onclick="closeEditParticipantModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editParticipantForm" method="POST">
                @csrf @method('PUT')
                @include('monitoring.fstp._participant_form', ['participant' => null])
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeEditParticipantModal()"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                        Update Participant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
@push('scripts')
<script>
    const programId = {{ $program->id }};
 
    function openAddParticipantModal() {
        document.getElementById('addParticipantModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeAddParticipantModal() {
        document.getElementById('addParticipantModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    function openEditParticipantModal(progId, partId) {
        fetch(`/foreign-programs/${progId}/participants/${partId}`)
            .then(r => r.json())
            .then(data => {
                const form = document.getElementById('editParticipantForm');
                form.action = `/foreign-programs/${progId}/participants/${partId}`;
                ['name','sex','position','agency','contact_no','email','status'].forEach(f => {
                    const el = form.querySelector(`[name="${f}"]`);
                    if (el) el.value = data[f] ?? '';
                });
                document.getElementById('editParticipantModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
    }
    function closeEditParticipantModal() {
        document.getElementById('editParticipantModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush



</x-monitoring-layout>
</x-layout>