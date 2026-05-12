<x-layout>

<x-slot:title>
    Batches
</x-slot:title>

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">

        <a href="/certificates" class="text-blue-600 text-sm">
            ← Back to Programs
        </a>

        <h1 class="text-3xl font-bold text-slate-800 mt-2">
            Training Batches
        </h1>

        <p class="text-slate-500 mt-1">
            Select a batch to view participants and generate certificates
        </p>

    </div>

    <!-- BATCH GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @foreach($batches as $batch)

            <div class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-lg transition">

                <!-- TOP INFO -->
                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            {{ $batch->batch }}
                        </h2>

                        <p class="text-sm text-slate-500">
                            Program Code: {{ $batch->program_code }}
                        </p>

                    </div>

                    <span class="
                        text-xs px-3 py-1 rounded-full

                        @if($batch->status == 'Completed')
                            bg-green-100 text-green-700
                        @elseif($batch->status == 'Ongoing')
                            bg-blue-100 text-blue-700
                        @else
                            bg-yellow-100 text-yellow-700
                        @endif
                    ">
                        {{ $batch->status }}
                    </span>

                </div>

                <!-- DETAILS -->
                <div class="mt-5 space-y-2 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Venue</span>
                        <span class="font-medium text-slate-700">
                            {{ $batch->venue ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Date</span>
                        <span class="font-medium text-slate-700">
                            {{ $batch->date_start }} - {{ $batch->date_end }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Time</span>
                        <span class="font-medium text-slate-700">
                            {{ $batch->time_start }} - {{ $batch->time_end }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Hours</span>
                        <span class="font-medium text-slate-700">
                            {{ $batch->hours }}
                        </span>
                    </div>

                </div>

                <!-- ACTION -->
                <div class="mt-6">

                    <a href="/certificates/batch/{{ $batch->id }}"
                       class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-medium">

                        View Participants

                    </a>

                </div>

            </div>

        @endforeach

    </div>

</div>

</x-layout>