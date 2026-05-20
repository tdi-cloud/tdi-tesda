<x-layout>
    <x-slot:title>Programs</x-slot:title>
    <x-monitoring-layout>

        
    <div name="header" class="px-6 pt-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-base-content">
                    Program Supporting Documents
                </h2>
                <p class="text-sm text-base-content/60 mt-0.5">
                    List of all submitted supporting documents across programs
                </p>
            </div>
            <div class="badge badge-neutral badge-lg font-mono">
                {{ $documents->total() }} records
            </div>
        </div>
    </div>
 
    <div class="p-4 md:p-6 space-y-4">
 
        {{-- SEARCH & FILTER CARD --}}
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('program-supporting-documents.mainindex') }}" 
                      id="filterForm">
                    
                    {{-- Search bar --}}
                    <div class="flex gap-2 mb-3">
                        <label class="input input-bordered flex items-center gap-2 flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search by subject, document number, program code or title..."
                                class="grow text-sm"
                            />
                        </label>
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>
                        @if(request()->hasAny(['search','document_type','document_series','program_id','origin']))
                            <a href="{{ route('program-supporting-documents.mainindex') }}" 
                               class="btn btn-ghost">
                                Clear
                            </a>
                        @endif
                    </div>
 
                    {{-- Filters row --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
 
                        {{-- Program filter --}}
                        <select name="program_id" class="select select-bordered select-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Programs</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" 
                                    {{ request('program_id') == $program->id ? 'selected' : '' }}>
                                    {{ $program->program_code ? '[' . $program->program_code . '] ' : '' }}{{ Str::limit($program->title, 40) }}
                                </option>
                            @endforeach
                        </select>
 
                        {{-- Document type filter --}}
                        <select name="document_type" class="select select-bordered select-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Document Types</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type }}" 
                                    {{ request('document_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
 
                        {{-- Series / Year filter --}}
                        <select name="document_series" class="select select-bordered select-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Years</option>
                            @foreach($documentSeries as $series)
                                <option value="{{ $series }}" 
                                    {{ request('document_series') == $series ? 'selected' : '' }}>
                                    {{ $series }}
                                </option>
                            @endforeach
                        </select>
 
                        {{-- Origin filter --}}
                        <select name="origin" class="select select-bordered select-sm w-full"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Origins</option>
                            @foreach($origins as $origin)
                                <option value="{{ $origin }}" 
                                    {{ request('origin') == $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
 
                    </div>
 
                </form>
            </div>
        </div>
 
        {{-- ACTIVE FILTERS --}}
        @if(request()->hasAny(['search','document_type','document_series','program_id','origin']))
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-xs text-base-content/50 font-medium">Active filters:</span>
                @if(request('search'))
                    <div class="badge badge-outline gap-1">
                        Search: "{{ request('search') }}"
                    </div>
                @endif
                @if(request('document_type'))
                    <div class="badge badge-outline gap-1">
                        Type: {{ request('document_type') }}
                    </div>
                @endif
                @if(request('document_series'))
                    <div class="badge badge-outline gap-1">
                        Year: {{ request('document_series') }}
                    </div>
                @endif
                @if(request('origin'))
                    <div class="badge badge-outline gap-1">
                        Origin: {{ request('origin') }}
                    </div>
                @endif
            </div>
        @endif
 
        {{-- TABLE CARD --}}
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="overflow-x-auto">
                <table class="table table-sm table-zebra">
                    <thead>
                        <tr class="bg-base-200 text-base-content/70 text-xs uppercase tracking-wide">
                            <th class="w-8">#</th>
                            <th>Program</th>
                            <th>Document Type</th>
                            <th>Document No.</th>
                            <th>Subject</th>
                            <th>Series</th>
                            <th>Origin</th>
                            <th>Date Issued</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr class="hover">
                                {{-- Row number --}}
                                <td class="text-base-content/40 text-xs font-mono">
                                    {{ $documents->firstItem() + $loop->index }}
                                </td>
 
                                {{-- Program info --}}
                                <td class="max-w-[200px]">
                                    @if($doc->program)
                                        <div class="font-medium text-sm leading-tight line-clamp-2 cursor-pointer" onclick="goToProgram({{ $doc->program->id }})">
                                            {{ $doc->program->title }}
                                        </div>
                                        @if($doc->program_code)
                                            <div class="badge badge-ghost badge-sm mt-1 font-mono">
                                                {{ $doc->program_code }} 
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-base-content/30 italic text-xs">No program</span>
                                    @endif
                                </td>
 
                                {{-- Document type --}}
                                <td>
                                    <div class="badge badge-outline badge-sm whitespace-nowrap">
                                        {{ $doc->document_type }}
                                    </div>
                                </td>
 
                                {{-- Document number --}}
                                <td class="font-mono text-xs font-semibold text-primary">
                                    {{ $doc->document_number }}
                                </td>
 
                                {{-- Subject --}}
                                <td class="max-w-[220px]">
                                    <span class="text-sm line-clamp-2 leading-snug" title="{{ $doc->subject }}">
                                        {{ $doc->subject }}
                                    </span>
                                </td>
 
                                {{-- Series (year) --}}
                                <td class="font-mono text-sm text-center">
                                    {{ $doc->document_series }}
                                </td>
 
                                {{-- Origin --}}
                                <td>
                                    @if($doc->origin)
                                        <span class="text-sm">{{ $doc->origin }}</span>
                                    @else
                                        <span class="text-base-content/25 text-xs">—</span>
                                    @endif
                                </td>
 
                                {{-- Date issued --}}
                                <td class="whitespace-nowrap text-sm">
                                    @if($doc->date_issued)
                                        {{ \Carbon\Carbon::parse($doc->date_issued)->format('M d, Y') }}
                                    @else
                                        <span class="text-base-content/25 text-xs">—</span>
                                    @endif
                                </td>
 
                                {{-- Link --}}
                                <td>
                                    @if($doc->link)
                                        <a href="{{ $doc->link }}" target="_blank" rel="noopener"
                                           class="btn btn-xs btn-ghost text-primary gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            View
                                        </a>
                                    @else
                                        <span class="text-base-content/25 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-3 text-base-content/40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-base-content/50">No documents found</p>
                                            <p class="text-sm">Try adjusting your search or filters.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
 
            {{-- PAGINATION --}}
            @if($documents->hasPages())
                <div class="card-body pt-0 px-4 pb-4 border-t border-base-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3">
                        <p class="text-xs text-base-content/50">
                            Showing <span class="font-semibold text-base-content">{{ $documents->firstItem() }}</span>
                            to <span class="font-semibold text-base-content">{{ $documents->lastItem() }}</span>
                            of <span class="font-semibold text-base-content">{{ $documents->total() }}</span> results
                        </p>
                        <div class="join">
                            @if($documents->onFirstPage())
                                <button class="join-item btn btn-sm btn-disabled">«</button>
                            @else
                                <a href="{{ $documents->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
                            @endif
 
                            @foreach($documents->getUrlRange(max(1, $documents->currentPage() - 2), min($documents->lastPage(), $documents->currentPage() + 2)) as $page => $url)
                                @if($page == $documents->currentPage())
                                    <button class="join-item btn btn-sm btn-active btn-primary">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="join-item btn btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach
 
                            @if($documents->hasMorePages())
                                <a href="{{ $documents->nextPageUrl() }}" class="join-item btn btn-sm">»</a>
                            @else
                                <button class="join-item btn btn-sm btn-disabled">»</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
 
        </div>
    </div>


    <script>
        function goToProgram(id) {
            window.location.href = `./programs/${id}`;
        }
    </script>


     



    </x-monitoring-layout>
</x-layout>



