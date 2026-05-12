<x-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<div class="p-5">

    <input type="text"
        id="template_name"
        placeholder="Template Name"
        class="border p-2">

    <input type="file"
        id="background_image">

    <input type="file"
        id="signature_image">

    <button id="saveTemplate"
        class="bg-blue-500 text-white px-4 py-2">
        Save Template
    </button>

    <div
        id="certificateCanvas"
        class="relative mt-5 w-full h-[700px] border overflow-hidden">

        <img
            id="backgroundPreview"
            class="absolute w-full h-full object-cover">

        <div
            id="participant_name"
            class="draggable absolute text-4xl font-bold"
            style="top:300px;left:300px;">
            Juan Dela Cruz
        </div>

        <div
            id="program_title"
            class="draggable absolute text-xl"
            style="top:380px;left:250px;">
            Program Title
        </div>

        <div
            id="signature"
            class="draggable absolute"
            style="bottom:120px;right:250px;">
            Signature
        </div>

    </div>
</div>

<script>

background_image.onchange = evt => {

    const [file] = background_image.files;

    if(file) {

        backgroundPreview.src =
            URL.createObjectURL(file);
    }
};

interact('.draggable').draggable({

    listeners: {

        move(event) {

            let target = event.target;

            let x =
                (parseFloat(target.getAttribute('data-x')) || 0)
                + event.dx;

            let y =
                (parseFloat(target.getAttribute('data-y')) || 0)
                + event.dy;

            target.style.transform =
                `translate(${x}px, ${y}px)`;

            target.setAttribute('data-x', x);

            target.setAttribute('data-y', y);
        }
    }
});

$('#saveTemplate').click(function() {

    let formData = new FormData();

    formData.append(
        'template_name',
        $('#template_name').val()
    );

    formData.append(
        'background_image',
        $('#background_image')[0].files[0]
    );

    formData.append(
        'signature_image',
        $('#signature_image')[0].files[0]
    );

    formData.append('elements',

        JSON.stringify({

            participant_name: {

                x:
                    $('#participant_name').attr('data-x'),

                y:
                    $('#participant_name').attr('data-y')
            },

            program_title: {

                x:
                    $('#program_title').attr('data-x'),

                y:
                    $('#program_title').attr('data-y')
            }
        })
    );

    $.ajax({

        url: '/certificate-templates/store',

        method: 'POST',

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')
        },

        data: formData,

        processData: false,

        contentType: false,

        success: function() {

            Swal.fire(
                'Saved!',
                'Template saved successfully',
                'success'
            );
        }
    });
});

</script>

</x-layout>