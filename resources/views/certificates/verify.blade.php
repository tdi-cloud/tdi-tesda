<x-layout>

<div class="p-10">

<h1 class="text-3xl font-bold">
Certificate Verified
</h1>

<div class="mt-5">

<p>
Certificate No:
{{ $certificate->certificate_no }}
</p>

<p>
Program:
{{ $certificate->program_code }}
</p>

<p>
Issued:
{{ $certificate->created_at }}
</p>

</div>

</div>

</x-layout>