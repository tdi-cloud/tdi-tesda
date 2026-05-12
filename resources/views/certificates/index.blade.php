<x-layout>

<x-slot:title>
    Certificate Issuance
</x-slot:title>

<div class="p-6">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Certificate Issuance
            </h1>

            <p class="text-slate-500 mt-1">
                Select a training program to generate certificates
            </p>

        </div>

        <a
            href="/certificate-templates"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg"
        >
            Manage Templates
        </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @foreach($programs as $program)

            <a
                href="/certificates/program/{{ $program->program_code }}"
                class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-lg transition duration-200"
            >

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">
                            {{ $program->title }}
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            {{ $program->program_code }}
                        </p>

                    </div>

                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                        {{ $program->modality }}
                    </span>

                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">

                    <div class="bg-slate-50 rounded-xl p-3">

                        <p class="text-xs text-slate-500">
                            Competency
                        </p>

                        <p class="font-semibold text-sm mt-1">
                            {{ $program->competency }}
                        </p>

                    </div>

                    <div class="bg-slate-50 rounded-xl p-3">

                        <p class="text-xs text-slate-500">
                            Category
                        </p>

                        <p class="font-semibold text-sm mt-1">
                            {{ $program->category }}
                        </p>

                    </div>

                </div>

                <div class="mt-5 flex items-center justify-between">

                    <div>

                        <p class="text-xs text-slate-400">
                            Training Type
                        </p>

                        <p class="text-sm font-medium text-slate-700">
                            {{ $program->type }}
                        </p>

                    </div>

                    <div class="text-blue-600 text-sm font-semibold">
                        View Batches →
                    </div>

                </div>

            </a>

        @endforeach

    </div>

</div>

</x-layout>