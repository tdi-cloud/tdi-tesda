{{-- resources/views/foreign-programs/_participant_form.blade.php --}}
@php
    $old = fn($field, $default = '') => old($field, $participant?->{$field} ?? $default);
    $statusOptions = \App\Models\ForeignParticipant::statusOptions();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- Name --}}
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ $old('name') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="Full name"/>
    </div>

    {{-- Sex --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Sex <span class="text-red-500">*</span>
        </label>
        <select name="sex" required
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="">-- Select --</option>
            <option value="male"   @selected($old('sex') === 'male')>Male</option>
            <option value="female" @selected($old('sex') === 'female')>Female</option>
            <option value="other"  @selected($old('sex') === 'other')>Other</option>
        </select>
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Status <span class="text-red-500">*</span>
        </label>
        <select name="status" required
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="">-- Select Status --</option>
            @foreach($statusOptions as $key => $label)
                <option value="{{ $key }}" @selected($old('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Position --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Position <span class="text-red-500">*</span>
        </label>
        <input type="text" name="position" value="{{ $old('position') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. Director III"/>
    </div>

    {{-- Agency --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Agency <span class="text-red-500">*</span>
        </label>
        <input type="text" name="agency" value="{{ $old('agency') }}" required
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. DILG"/>
    </div>

    {{-- Contact --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Contact No.
        </label>
        <input type="text" name="contact_no" value="{{ $old('contact_no') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="e.g. 09XX XXX XXXX"/>
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
            Email
        </label>
        <input type="email" name="email" value="{{ $old('email') }}"
               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
               placeholder="email@example.com"/>
    </div>

</div>