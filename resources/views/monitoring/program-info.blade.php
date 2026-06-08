<style>
    .progitem {
      /* Hidden by default */
      opacity: 0;
      transform: scale(0.5);
      animation: popUp 0.4s ease forwards;
    }

    @keyframes popUp {
      0%   { opacity: 0; transform: scale(0.5); }
      70%  { transform: scale(1.08); }       /* slight overshoot */
      100% { opacity: 1; transform: scale(1); }
    }

    .progitem:nth-child(1)  { animation-delay: 0.1s; }
    .progitem:nth-child(2)  { animation-delay: 0.2s; }
    .progitem:nth-child(3)  { animation-delay: 0.3s; }
    .progitem:nth-child(4)  { animation-delay: 0.4s; }
    .progitem:nth-child(5)  { animation-delay: 0.5s; }
    .progitem:nth-child(6)  { animation-delay: 0.6s; }
    .progitem:nth-child(7)  { animation-delay: 0.7s; }
    .progitem:nth-child(8)  { animation-delay: 0.8s; }
    .progitem:nth-child(9)  { animation-delay: 0.9s; }


</style>



<div class="px-5 py-3">
    <h1 class="poppins-bold text-slate-600 mb-2 dark:text-slate-200 ">Program Details</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 ">

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-between">
            <button onclick="uploadModal()" class="btn btn-sm btn-default rounded-2xl"><i class="fa-solid fa-image"></i> Cover Page</button>
            @if($cover)
            <div id="cover-panel">
                <div class="flex items-center gap-2">
                    <img class="w-10 h-10 object-cover rounded-lg border border-slate-300 dark:border-slate-600" src="/storage/{{ $cover->image }}" alt="">
                    <button onclick="deleteCover({{ $cover->id }})" class="btn btn-xs btn-circle btn-error btn-soft"><i class="fa-regular fa-trash-can"></i></button>
                </div>
            </div>
            <script>
                function deleteCover(id) {
                    if (!confirm('Are you sure you want to delete this?')) return;

                    $.ajax({
                        url: '/cover/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.message);
                            $('#cover-panel').hide();


                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            alert('Something went wrong');
                        }
                    });
                }
            </script>

            @endif
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Program Code</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-code text-blue-600"></i> {{ $myprogram->program_code }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Program Type</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-gears"></i> {{ $myprogram->type }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Modality</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-chalkboard text-blue-500"></i> {{ $myprogram->modality }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Category</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-house-chimney-window text-indigo-400"></i> {{ $myprogram->category }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Initiated</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-play text-orange-400"></i> {{ $myprogram->initiated }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 ">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Provider</h1>
            <p class="poppins-medium text-[14px] leading-4 truncate hover:whitespace-normal hover:overflow-visible"><i class="fa-solid fa-handshake text-indigo-500"></i> {{ $myprogram->provider }}</p>
        </div>

        <div class="progitem border-l-4 p-2 border  rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
            <h1 class="text-[13px] poppins-semibold text-slate-500">Cost</h1>
            <p class="poppins-medium text-[14px]"><i class="fa-solid fa-money-bills text-green-500"></i> {{ $myprogram->cost }}</p>
        </div>

        <div onclick="openCompetencyModal()"  class="progitem cursor-pointer hover:scale-[1.02] diration-500 border-l-4 p-2 border rounded-lg col-span-2
        {{ $myprogram->competencies->isEmpty() 
            ? 'border-red-400 bg-red-50 dark:bg-red-950 dark:border-red-600' 
            : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800' }}">

        <h1 class="text-[13px] poppins-semibold 
            {{ $myprogram->competencies->isEmpty() ? 'text-red-500' : 'text-slate-500' }}">
            Competency
            @if($myprogram->competencies->isEmpty())
                <span class="ml-1 text-red-500 animate-pulse">⚠ Required</span>
            @endif
        </h1>

        @if($myprogram->competencies->isEmpty())
            <p class="poppins-medium text-[13px] text-red-500 flex items-center gap-1 mt-1 leading-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
                No competencies added yet — please add at least one competency to this program.
            </p>
        @else
        {{-- Marquee / ticker wrapper --}}
        <div class="overflow-hidden relative mt-1" style="mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);">
                    <div class="flex gap-3 competency-ticker" style="width: max-content;">
                        {{-- Duplicate the list for seamless loop --}}
                        @foreach([...$myprogram->competencies, ...$myprogram->competencies] as $comp)
                            <span class="inline-flex items-center gap-1 poppins-medium text-[13px] whitespace-nowrap
                                px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 border border-yellow-300 dark:border-yellow-700">
                                <i class="fa-regular fa-lightbulb text-yellow-500 text-[11px]"></i>
                                {{ $comp->competency }}
                                @if($comp->domain)
                                    <span class="text-[11px] text-yellow-500">({{ $comp->domain }})</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>

                <style>
                    .competency-ticker {
                        animation: ticker-scroll 18s linear infinite;
                    }
                    .competency-ticker:hover {
                        animation-play-state: paused;
                    }
                    @keyframes ticker-scroll {
                        0%   { transform: translateX(0); }
                        100% { transform: translateX(-50%); }
                    }
                </style>
            @endif
        </div>

        @include('monitoring.myprogram-includes.create-competency')

    </div>

</div>
