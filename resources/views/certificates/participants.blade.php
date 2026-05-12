<x-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="p-5">

<table class="table w-full">

<thead>

<tr>

<th>Name</th>
<th>Attendance</th>
<th>Hours</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($participants as $participant)

<tr>

<td>
{{ $participant->employee->FIRSTNAME }}
{{ $participant->employee->LASTNAME }}
</td>

<td>
{{ $participant->attendance }}
</td>

<td>
{{ $participant->hours }}
</td>

<td>

@if($participant->attendance == 'Complete')

<button
    class="generateBtn bg-green-500 text-white px-4 py-2 rounded"
    data-id="{{ $participant->id }}">
    Generate
</button>

@else

<span class="text-red-500">
Not Eligible
</span>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<script>

$('.generateBtn').click(function() {

    let id = $(this).data('id');

    $.ajax({

        url:
            '/certificates/generate/' + id,

        method: 'POST',

        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },

        success: function(res) {

            window.open(res.pdf, '_blank');

            Swal.fire(
                'Generated!',
                'Certificate created successfully',
                'success'
            );
        },

        error: function(err) {
            console.log('STATUS:', err.status);
            console.log('RESPONSE TEXT:', err.responseText);
            console.log('RESPONSE JSON:', err.responseJSON);

            Swal.fire(
                'Error',
                err.responseJSON.error,
                'error'
            );
        }
    });
});

</script>

</x-layout>