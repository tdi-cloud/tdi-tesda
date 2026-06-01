
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $program->title }} — My Enrollment</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon_io/android-chrome-512x512.png') }}">
  @vite('resources/css/app.css')

  
  <style>
    :root {
      --emerald: #059669;
      --emerald-light: #d1fae5;
      --emerald-dark: #064e3b;
      --amber: #d97706;
      --amber-light: #fef3c7;
      --red: #dc2626;
      --red-light: #fee2e2;
    }
    body { font-family: 'DM Sans', sans-serif; background: #f8fafc; color: #1e293b; }
    h1, h2, h3, .serif { font-family: 'Fraunces', serif; }

    /* Hero */
    .hero-overlay { background: linear-gradient(to top, rgba(6,78,59,0.92) 0%, rgba(6,78,59,0.55) 50%, transparent 100%); }

    /* Cards */
    .card { background: #fff; border-radius: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.04); }

    /* Progress ring */
    .ring-track { stroke: #e2e8f0; }
    .ring-fill { stroke: var(--emerald); stroke-linecap: round; transition: stroke-dashoffset 0.6s ease; }

    /* Badge colours */
    .badge-present  { background: var(--emerald-light); color: var(--emerald-dark); }
    .badge-absent   { background: var(--red-light);    color: var(--red); }
    .badge-excused  { background: var(--amber-light);  color: var(--amber); }
    .badge-pending  { background: #e0e7ff; color: #3730a3; }
    .badge-approved { background: var(--emerald-light); color: var(--emerald-dark); }
    .badge-rejected { background: var(--red-light); color: var(--red); }

    /* Requirement row */
    .req-row { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 0.75rem; transition: background 0.15s; }
    .req-row:hover { background: #f1f5f9; }

    /* Speaker card */
    .speaker-img { aspect-ratio: 1/1; width: 100%; object-fit: cover; }

    /* Slide-up modal */
    #submit-modal { transition: opacity 0.2s; }
    #submit-modal.hidden { display: none; }

    /* Pill tabs */
    .tab-btn { padding: 0.4rem 1.1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500; color: #64748b; cursor: pointer; transition: all 0.15s; }
    .tab-btn.active { background: var(--emerald); color: #fff; }

    /* Toast */
    #toast { transition: opacity 0.3s, transform 0.3s; }
  </style>
</head>
<body class="min-h-screen">

{{-- ══════════════════════════════════════════════ --}}
{{-- HERO BANNER                                   --}}
{{-- ══════════════════════════════════════════════ --}}
<header class="relative h-72 md:h-80 overflow-hidden">
  {{-- @if($coverPage) --}}
    <img src="{{ $coverPage ? asset('storage/' . $coverPage->image) : asset('storage/default.png') }}"
         class="w-full h-full object-cover" alt="">
  {{-- @else
    <img src="{{ asset('storage/default.png') }}"
    <div class="w-full h-full bg-gradient-to-br from-emerald-800 to-teal-600"></div>
  @endif --}}
  <div class="hero-overlay absolute inset-0"></div>

  {{-- Back link --}}
  <a href="./"
     class="btn btn-success btn-sm btn-soft absolute top-5 left-5 flex items-center gap-2 text-white/80 hover:text-white text-sm font-medium transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    My Programs
  </a>

  <div class="absolute bottom-0 left-0 right-0 p-7 max-w-5xl mx-auto">
    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-3
      {{ $batch->status === 'Completed' ? 'bg-emerald-400/20 text-emerald-200' : 'bg-amber-400/20 text-amber-200' }}">
      {{ $batch->status }}
    </span>
    <h1 class="text-white text-2xl md:text-3xl  leading-snug drop-shadow-sm font-bold">
      {{ $program->title }}
    </h1>
    <p class="text-white/70 text-sm mt-1">{{ $program->program_code }} · {{ $batch->batch }}</p>
  </div>
</header>

{{-- ══════════════════════════════════════════════ --}}
{{-- MAIN CONTENT                                  --}}
{{-- ══════════════════════════════════════════════ --}}
<main class="max-w-5xl mx-auto px-4 md:px-6 py-10 space-y-10">

  {{-- ── Program Info ────────────────────────────── --}}
  <section class="card p-6 md:p-8">
    @if($program->description)
      <p class="text-slate-600 leading-relaxed text-base mb-6">{{ $program->description }}</p>
    @endif

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      {{-- Schedule --}}
      <div class="flex items-start gap-3">
        <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </span>
        <div>
          <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Schedule</p>
          <p class="text-sm font-medium text-slate-700 mt-0.5">
            {{ \Carbon\Carbon::parse($batch->date_start)->format('M d') }}
            –
            {{ \Carbon\Carbon::parse($batch->date_end)->format('M d, Y') }}
          </p>
          <p class="text-xs text-slate-500">
            {{ \Carbon\Carbon::parse($batch->time_start)->format('g:i A') }}
            –
            {{ \Carbon\Carbon::parse($batch->time_end)->format('g:i A') }}
          </p>
        </div>
      </div>

      {{-- Venue --}}
      <div class="flex items-start gap-3">
        <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </span>
        <div>
          <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Venue</p>
          <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $batch->venue ?? 'TBA' }}</p>
        </div>
      </div>

      {{-- Modality --}}
      <div class="flex items-start gap-3">
        <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </span>
        <div>
          <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Modality</p>
          <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $batch->modality }}</p>
        </div>
      </div>

      {{-- Duration --}}
      <div class="flex items-start gap-3">
        <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </span>
        <div>
          <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Duration</p>
          <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $batch->days }} day(s) · {{ $batch->hours }} hrs</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ── Progress Cards ──────────────────────────── --}}
  @php
    $totalDays    = (int) $batch->days;
    $totalHours   = (int) $batch->hours;
    $earnedHours  = (int) ($participant->hours ?? 0);
    $attendance   = strtolower($participant->attendance ?? 'Absent');

    // Submission counts
    $totalReqs    = $requirements->count();
    $submitted    = $submissions->whereIn('status', ['Pending','Approved'])->count();
    $approved     = $submissions->where('status','Approved')->count();

    $hoursPercent = $totalHours > 0 ? min(100, round($earnedHours / $totalHours * 100)) : 0;
    $reqPercent   = $totalReqs  > 0 ? min(100, round($approved   / $totalReqs  * 100)) : 0;

    // Ring circumference for r=34  →  2π×34 ≈ 213.6
    $circ = 213.6;
    $hoursDash = $circ - ($circ * $hoursPercent / 100);
    $reqDash   = $circ - ($circ * $reqPercent   / 100);
  @endphp

  <section>
    <h2 class="serif text-xl font-bold text-slate-800 mb-5">Your Progress</h2>
    <div class="grid sm:grid-cols-3 gap-4">

      {{-- Hours earned --}}
      <div class="card p-6 flex flex-col items-center text-center">
        <svg class="w-20 h-20 progress-ring -rotate-90" viewBox="0 0 80 80">
          <circle class="ring-track" cx="40" cy="40" r="34" fill="none" stroke-width="7"/>
          <circle class="ring-fill" cx="40" cy="40" r="34" fill="none" stroke-width="7"
            stroke-dasharray="{{ $circ }}"
            stroke-dashoffset="{{ $hoursDash }}"/>
        </svg>
        <p class="text-2xl font-bold text-emerald-700 mt-3">{{ $earnedHours }}<span class="text-base font-normal text-slate-400">/{{ $totalHours }}</span></p>
        <p class="text-sm text-slate-500 mt-1">Hours Earned</p>
      </div>

      {{-- Requirements submitted --}}
      <div class="card p-6 flex flex-col items-center text-center">
        <svg class="w-20 h-20 progress-ring -rotate-90" viewBox="0 0 80 80">
          <circle class="ring-track" cx="40" cy="40" r="34" fill="none" stroke-width="7"/>
          <circle class="ring-fill" cx="40" cy="40" r="34" fill="none" stroke-width="7"
            stroke-dasharray="{{ $circ }}"
            stroke-dashoffset="{{ $reqDash }}"
            style="stroke: #7c3aed"/>
        </svg>
        <p class="text-2xl font-bold text-violet-700 mt-3">{{ $approved }}<span class="text-base font-normal text-slate-400">/{{ $totalReqs }}</span></p>
        <p class="text-sm text-slate-500 mt-1">Requirements Approved</p>
      </div>

      {{-- Attendance status --}}
      <div class="card p-6 flex flex-col items-center justify-center text-center gap-3">
        @php
          $attColor = match($attendance) {
            'complete' => 'text-emerald-700',
            'absent' => 'text-amber-700',
            default   => 'text-red-600',
          };
          $attBg = match($attendance) {
            'complete' => 'bg-emerald-50',
            'absent' => 'bg-amber-50',
            default   => 'bg-red-50',
          };
          $attIcon = match($attendance) {
            'complete' => '✓',
            'absent' => '~',
            default   => '✕',
          };
        @endphp
        <span class="w-16 h-16 rounded-full {{ $attBg }} {{ $attColor }} text-3xl flex items-center justify-center font-bold">
          {{ $attIcon }}
        </span>
        <div>
          <p class="text-lg font-bold {{ $attColor }} capitalize">{{ $attendance }}</p>
          <p class="text-sm text-slate-500 mt-0.5">Attendance Status</p>
        </div>
      </div>
    </div>

  </section>

  {{-- ── Resource Speakers ───────────────────────── --}}
  @if($speakers->isNotEmpty())
  <section>
    <h2 class="serif text-xl font-bold text-slate-800 mb-5">Resource Speakers</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
      @foreach($speakers as $speaker)
      <div class="card overflow-hidden flex flex-col">
        {{-- Placeholder avatar --}}
        <div class="w-full h-40 bg-gradient-to-br from-slate-200 to-slate-100 flex items-center justify-center">
          <span class="text-5xl font-bold text-slate-300 serif">
            {{ strtoupper(substr($speaker->name, 0, 1)) }}
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-slate-800 text-sm">{{ $speaker->name }}</h3>
          @if($speaker->position)
            <p class="text-xs text-slate-500 mt-0.5">{{ $speaker->position }}</p>
          @endif
          @if($speaker->organization)
            <p class="text-xs text-emerald-600 mt-1 font-medium">{{ $speaker->organization }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ── Requirements ────────────────────────────── --}}
  <section>
    <div class="flex items-center justify-between mb-5">
      <h2 class="serif text-xl font-bold text-slate-800">Requirements</h2>
      <span class="text-sm text-slate-400">{{ $approved }} of {{ $totalReqs }} completed</span>
    </div>

    <div class="card divide-y divide-slate-100">
      @forelse($requirements as $req)
        @php
          $sub = $submissions->firstWhere('requirement_id', $req->id);
          $status = $sub ? $sub->status : null;

          $iconColor = match($status) {
            'Approved' => 'text-emerald-500',
            'Pending'  => 'text-amber-400',
            'Rejected' => 'text-red-400',
            default    => 'text-slate-300',
          };
          $badgeClass = match($status) {
            'Approved' => 'badge-approved',
            'Pending'  => 'badge-pending',
            'Rejected' => 'badge-rejected',
            default    => 'bg-slate-100 text-slate-400',
          };
          $badgeLabel = match($status) {
            'Approved' => 'Approved',
            'Pending'  => 'Under Review',
            'Rejected' => 'Rejected',
            default    => 'Not Submitted',
          };
          $due = "Day {$req->day_due}, Month {$req->month_due} after training";
        @endphp

        <div class="req-row">
          {{-- Status icon --}}
          <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
            @if($status === 'Approved')
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            @elseif($status === 'Pending')
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            @elseif($status === 'Rejected')
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            @else
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
            @endif
          </svg>

          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
             <p class="text-sm font-medium text-slate-800">
                  {{
                      $req->title === 'TREAP'
                          ? 'Terminal Report'
                          : (
                              $req->title === 'REAP'
                                  ? 'Terminal and Re-entry Action Plan'
                                  : (
                                      $req->title === 'TDOR'
                                          ? 'Training Development Outcome Report'
                                          : $req->title
                                  )
                          )
                  }}
              </p>
              @if($req->required === 'Yes')
                <span class="text-[11px] px-1.5 py-0.5 rounded bg-red-50 text-red-500 font-medium">Required</span>
              @endif
            </div>
            @if($req->description)
              <p class="text-xs text-slate-500 mt-0.5">{{ $req->description }}</p>
            @endif


            @php
                $dueDate = $req->getDueDateForBatch($batch);
            @endphp

            <p class="text-xs text-slate-400 mt-1">
                Due:
                {{ $dueDate ? \Carbon\Carbon::parse($dueDate)->format('M d, Y') : 'No due date' }}
            </p>
            @if($sub && $sub->remarks)
              <p class="text-xs text-amber-600 mt-1 italic">Remarks: {{ $sub->remarks }}</p>
            @endif
          </div>

          {{-- Badge / Submit button --}}
          <div class="flex-shrink-0 flex items-center gap-2">

              {{-- View uploaded file --}}
              @if($sub && $sub->file_path)

                    {{-- View File --}}
                    <a href="{{ asset('storage/' . $sub->file_path) }}"
                      target="_blank"
                      class="text-xs px-3 py-1.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition whitespace-nowrap">
                        View File
                    </a>

                    {{-- Delete Submission --}}
                    <form action="{{ route('enrolled.submission.destroy', $sub->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this submission?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="text-xs px-3 py-1.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition whitespace-nowrap">
                            Delete
                        </button>
                    </form>

                @endif

              <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $badgeClass }}">
                  {{ $badgeLabel }}
              </span>

              @if(!$status || strtolower($status) === 'rejected')
                  <button onclick="openModal({{ $req->id }}, '{{ addslashes($req->title) }}')"
                      class="text-xs px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition whitespace-nowrap">
                      Submit
                  </button>
              @endif
          </div>



        </div>
      @empty
        <div class="py-10 text-center text-slate-400 text-sm">No requirements defined for this program.</div>
      @endforelse
    </div>
  </section>

  @if($requirements->isNotEmpty())

  {{-- ── Requirement Templates ──────────────────────── --}}
<section>
  <h2 class="serif text-xl font-bold text-slate-800 mb-5">Requirement Report Templates</h2>

  @php
    $allTemplates = [
      'TREAP' => [
        'title'       => 'Terminal Report',
        'abbr'        => 'TREAP',
        'description' => 'A report submitted after training that summarizes what an employee learned and includes an action plan on how they will apply it at work, including steps, needed resources, and timeline.',
        'link'        => 'https://docs.google.com/document/d/1ZXxSHO0XNxXhfXh6CARzRh4My2Tdmv_j/edit',
      ],
      'REAP' => [
        'title'       => 'Terminal and Re-entry Action Plan',
        'abbr'        => 'REAP',
        'description' => 'A post-training document submitted after programs longer than 4 days that summarizes an employee’s learnings and includes an action plan with activities, outputs, schedule, and budget needed for implementation.',
        'link'        => 'https://docs.google.com/document/d/1ZXxSHO0XNxXhfXh6CARzRh4My2Tdmv_j/edit',
      ],
      'TDOR' => [
        'title'       => 'Training Development Outcome Report',
        'abbr'        => 'TDOR',
        'description' => 'A report filled out by an employee and supervisor about 6 months after training to assess its effectiveness and include the supervisor’s recommendations for the employee.',
        'link'        => 'https://docs.google.com/document/d/1aagF8BVWRtwqvaoAQx6qQG8juc1V_B12/edit',
      ],
    ];

    // Only keep templates whose key matches a requirement title in this batch
    $reqTitles       = $requirements->pluck('title')->toArray();
    $visibleTemplates = array_filter($allTemplates, fn($key) => in_array($key, $reqTitles), ARRAY_FILTER_USE_KEY);
  @endphp

  @if(!empty($visibleTemplates))
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($visibleTemplates as $tpl)
    <div class="card p-5 flex flex-col gap-3 hover:shadow-md transition-shadow">
      <div class="flex items-start gap-3">
        <span class="flex-shrink-0 w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </span>
        <div>
          <p class="text-sm font-semibold text-slate-800 leading-snug">{{ $tpl['title'] }}</p>
          <span class="inline-block mt-1 text-[11px] px-1.5 py-0.5 rounded font-mono bg-slate-100 text-slate-500">
            {{ $tpl['abbr'] }}
          </span>
        </div>
      </div>

      <p class="text-xs text-slate-500 leading-relaxed">{{ $tpl['description'] }}</p>

      <a href="{{ $tpl['link'] }}"
         target="_blank"
         class="mt-auto inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:underline transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Open Template
      </a>
    </div>
    @endforeach
  </div>
  @else
    <div class="card py-10 text-center text-slate-400 text-sm">No templates available for your requirements.</div>
  @endif

</section>

@endif

  {{-- ── Certificate Section ─────────────────────── --}}
  @php
    $allApproved   = $totalReqs > 0 && $approved >= $totalReqs;
    $certAvailable = $allApproved && $attendance === 'Complete';
  @endphp
  <section class="card p-6 flex flex-col sm:flex-row items-center justify-between gap-5
    {{ $certAvailable ? 'border-2 border-emerald-200 bg-emerald-50/50' : 'opacity-70' }}">
    <div class="flex items-center gap-4">
      <span class="w-12 h-12 rounded-xl {{ $certAvailable ? 'bg-emerald-100' : 'bg-slate-100' }} flex items-center justify-center text-2xl">
        🎓
      </span>
      <div>
        <h2 class="serif font-bold text-slate-800">Certificate of Completion</h2>
        <p class="text-sm text-slate-500 mt-0.5">
          @if($certAvailable)
            Your certificate is ready. Download it below.
          @else
            Complete all requirements and maintain attendance to unlock your certificate.
          @endif
        </p>
      </div>
    </div>
    @if($certAvailable)
      <a href="{{ route('employee.certificate.download', $participant->id) }}"
         class="flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 transition whitespace-nowrap shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download Certificate
      </a>
    @else
      <button disabled
        class="flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-200 text-slate-400 font-semibold text-sm cursor-not-allowed whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Not Yet Available
      </button>
    @endif
  </section>

  {{-- ── Supporting Documents ────────────────────── --}}
  @if($supportingDocs->isNotEmpty())
  <section>
    <h2 class="serif text-xl font-bold text-slate-800 mb-5">Supporting Documents</h2>
    <div class="card divide-y divide-slate-100">
      @foreach($supportingDocs as $doc)
      <div class="flex items-center gap-4 px-5 py-4">
        <span class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </span>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-slate-800">{{ $doc->subject }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ $doc->document_type }} · {{ $doc->document_number }} · {{ $doc->document_series }}</p>
        </div>
        @if($doc->link)
          <a href="{{ $doc->link }}" target="_blank"
             class="text-xs text-emerald-600 font-medium hover:underline flex-shrink-0">View →</a>
        @endif
      </div>
      @endforeach
    </div>
  </section>
  @endif

</main>

<footer class="text-center py-8 text-xs text-slate-400 border-t border-slate-100 mt-4">
  {{ $program->program_code }} · {{ $program->category }} · {{ $program->type }}
</footer>

{{-- ══════════════════════════════════════════════ --}}
{{-- SUBMIT REQUIREMENT MODAL                      --}}
{{-- ══════════════════════════════════════════════ --}}
<div id="submit-modal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-end sm:items-center justify-center p-4">
  <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
    <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
      <h3 class="serif text-white font-bold text-lg">Submit Requirement</h3>
      <button onclick="closeModal()" class="text-white/70 hover:text-white transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="p-6">
      <p class="text-sm text-slate-600 mb-5" id="modal-req-name">Select the requirement you are submitting.</p>

      <form id="submission-form"
            action="{{ route('enrolled.submissions.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4">
        @csrf
        <input type="hidden" name="participant_id" value="{{ $participant->id }}">
        <input type="hidden" name="batch_id"       value="{{ $batch->id }}">
        <input type="hidden" name="program_code"   value="{{ $program->program_code }}">
        <input type="hidden" name="requirement_id" id="modal-req-id">

        {{-- File upload --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Upload File</label>
          <input type="file" name="file" id="file-input" required
            class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg
                   file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700
                   hover:file:bg-emerald-100 transition">
          <p class="text-xs text-slate-400 mt-1">PDF, DOC, DOCX, JPG, PNG up to 10MB</p>
        </div>

        {{-- Notes --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Notes <span class="text-slate-400 font-normal">(optional)</span></label>
          <textarea name="notes" rows="3"
            class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"
            placeholder="Add any relevant notes..."></textarea>
        </div>

        <div class="flex gap-3 pt-1">
          <button type="button" onclick="closeModal()"
            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
            Cancel
          </button>
          <button type="submit"
            class="flex-1 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Toast --}}
<div id="toast"
     class="fixed bottom-6 right-6 bg-slate-900 text-white text-sm px-5 py-3 rounded-xl shadow-xl
            hidden opacity-0 translate-y-2 transition z-50">
</div>

@if(session('success'))
<script>showToast('{{ session('success') }}', true);</script>
@endif
@if(session('error'))
<script>showToast('{{ session('error') }}', false);</script>
@endif

<script>
  function openModal(reqId, reqTitle) {
    document.getElementById('modal-req-id').value   = reqId;
    document.getElementById('modal-req-name').textContent = 'Submitting: ' + reqTitle;
    document.getElementById('submit-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    document.getElementById('submit-modal').classList.add('hidden');
    document.getElementById('submission-form').reset();
    document.body.style.overflow = '';
  }

  document.getElementById('submit-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });

  function showToast(msg, success = true) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.toggle('bg-emerald-700', success);
    t.classList.toggle('bg-red-700', !success);
    t.classList.remove('hidden', 'opacity-0', 'translate-y-2');
    setTimeout(() => {
      t.classList.add('opacity-0', 'translate-y-2');
      setTimeout(() => t.classList.add('hidden'), 300);
    }, 3500);
  }
</script>

</body>
</html>