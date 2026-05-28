{{-- resources/views/monitoring/fstp/_form.blade.php --}}
{{-- Used inside both Add and Edit modals --}}

@php
    $isEdit = isset($editMode) && $editMode;
    $old = fn($field, $default = '') => old($field, $program?->{$field} ?? $default);
    $statusOptions = \App\Models\ForeignProgram::statusOptions();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- Program Title --}}
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Program Title <span class="text-red-500">*</span>
        </label>
        <input type="text" name="program_title" value="{{ $old('program_title') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="Enter program title"/>
    </div>

    {{-- Program Start & End --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Program Start <span class="text-red-500">*</span>
        </label>
        <input type="date" name="program_start"
               value="{{ $program?->program_start?->format('Y-m-d') ?? old('program_start') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Program End <span class="text-red-500">*</span>
        </label>
        <input type="date" name="program_end"
               value="{{ $program?->program_end?->format('Y-m-d') ?? old('program_end') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
    </div>

    {{-- Slots & Modality --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Slot(s) <span class="text-red-500">*</span>
        </label>
        <input type="number" name="slots" value="{{ $old('slots') }}" min="1" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. 5"/>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Modality <span class="text-red-500">*</span>
        </label>
        <select name="modality" required
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="">-- Select --</option>
            <option value="in-person" @selected($old('modality') === 'in-person')>In-Person</option>
            {{-- <option value="online" @selected($old('modality') === 'online')>Online</option> --}}
            <option value="hybrid"    @selected($old('modality') === 'hybrid')>Hybrid</option>
        </select>
    </div>

    {{-- Online Schedule (Hybrid only) — hidden by default, shown via JS --}}
    <div class="sm:col-span-2 modality-online hidden">
        <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wide mb-2">
            Online Schedule
        </p>
        <div class="grid grid-cols-2 gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-100 dark:border-purple-800">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Online Start</label>
                <input type="date" name="online_start"
                       value="{{ $program?->online_start?->format('Y-m-d') ?? old('online_start') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
            </div>
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Online End</label>
                <input type="date" name="online_end"
                       value="{{ $program?->online_end?->format('Y-m-d') ?? old('online_end') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
            </div>
        </div>
    </div>

    {{-- Organizing Sponsor --}}
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Organizing Sponsor <span class="text-red-500">*</span>
        </label>
        <input type="text" name="organizing_sponsor" value="{{ $old('organizing_sponsor') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. ASEAN Secretariat"/>
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Status of Program <span class="text-red-500">*</span>
        </label>
        <select name="status" required
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="">-- Select Status --</option>
            @foreach($statusOptions as $key => $label)
                <option value="{{ $key }}" @selected($old('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Submission Date --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Submission Date
        </label>
        <input type="date" name="submission_date"
               value="{{ $program?->submission_date?->format('Y-m-d') ?? old('submission_date') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
    </div>

    {{-- Embassy Deadline --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Embassy Deadline
        </label>
        <input type="date" name="embassy_deadline"
               value="{{ $program?->embassy_deadline?->format('Y-m-d') ?? old('embassy_deadline') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
    </div>

    {{-- Interview Date --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Interview Date
        </label>
        <input type="date" name="interview_date"
               value="{{ $program?->interview_date?->format('Y-m-d') ?? old('interview_date') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"/>
    </div>

    {{-- Attached Agency --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Attached Agency
        </label>
        <input type="text" name="attached_agency" value="{{ $old('attached_agency') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. DILG"/>
    </div>

    {{-- Invited Agencies --}}
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Invited Agencies
        </label>
        <textarea name="invited_agencies" rows="2"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 resize-none"
               placeholder="e.g. DILG, DBM, NEDA (separate by comma)">{{ $old('invited_agencies') }}</textarea>
    </div>

</div>