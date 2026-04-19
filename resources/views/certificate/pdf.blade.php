{{-- resources/views/certificate/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            line-height: 0.9;
        }

        @font-face {
            font-family: 'Poppins';
            src: url("{{ public_path('fonts/Poppins-Regular.ttf') }}") format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Poppins-Bold';
            src: url("{{ public_path('fonts/Poppins-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        @font-face {
            font-family: 'Million Smiles';
            src: url("{{ public_path('fonts/Million-Smiles.ttf') }}") format('truetype');
            font-style: normal;
            font-weight: normal;
        }

        body {
            width: 1123px;
            height: 794px;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: absolute;
            top: 0; left: 0;
            width: 1123px;
            height: 794px;
            z-index: 0;
        }

        .field {
            position: absolute;
            z-index: 1;
            white-space: nowrap;
            transform: translateX(-50%); /* same offset */
        }
    </style>
</head>
<body>

    @php
        $imagePath = storage_path('app/public/' . $template->background);
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageMime = mime_content_type($imagePath);
        $imageSrc  = 'data:' . $imageMime . ';base64,' . $imageData;
    @endphp

    <img class="background" src="{{ $imageSrc }}">

    @foreach($template->fields as $field)
        <div class="field" style="
            left: {{ $field->x }}px;    
            top: {{ $field->y }}px;
            font-size: {{ $field->font_size }}px;
            font-weight: {{ $field->font_weight }};
            text-align: {{ $field->text_align }};
            @if($field->field_name === 'name')  color: #00177d; 
            text-transform: capitalize; 
            font-family: 'Poppins-Bold';
            
            @endif
        ">
            {!! $data[$field->field_name] ?? '' !!}
        </div>
    @endforeach

</body>
</html>