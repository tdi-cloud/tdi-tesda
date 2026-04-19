@php
$imagePath = public_path('storage/'.$template->background);

$imageData = file_exists($imagePath)
    ? base64_encode(file_get_contents($imagePath))
    : null;

$src = $imageData ? 'data:image/png;base64,'.$imageData : null;
@endphp

<style>
@page {
    margin: 0;
    size: 1123px 794px;
}

html, body {
    margin: 0;
    padding: 0;
}

.page {
    width: 1123px;
    height: 794px;
    position: relative;
    overflow: hidden;
}

.bg {
    position: absolute;
    width: 1123px;
    height: 794px;
    left: 0;
    top: 0;
}

.text {
    position: absolute;
    white-space: nowrap;
    z-index: 2;
}

@font-face {
    font-family: 'MillionSmiles';
    src: url("{{ public_path('fonts/Million-Smiles.ttf') }}") format('truetype');
    font-weight: normal;
    font-style: normal;
}

/* FONTS */
@font-face {
    font-family: 'Poppins';
    src: url("{{ public_path('fonts/Poppins-Regular.ttf') }}") format('truetype');
}

@font-face {
    font-family: 'Poppins-Bold';
    src: url("{{ public_path('fonts/Poppins-Bold.ttf') }}") format('truetype');
}

.name-font {
    font-family: 'Poppins-Bold' !important;
}

.program-font,
.date-font {
    font-family: 'Poppins';
}
</style>

<div class="page">

    {{-- BACKGROUND --}}
    @if($src)
        <img class="bg" src="{{ $src }}">
    @endif

    {{-- FIELDS --}}
    @foreach($template->fields as $field)

        @php
            $class = match($field->field_name) {
                'name' => 'name-font',
                'program' => 'program-font',
                'date' => 'date-font',
                default => 'program-font'
            };
        @endphp

        <div class="text {{ $class }}"
            style="
                left: {{ $field->x }}px;
                top: {{ $field->y }}px;
                font-size: {{ $field->font_size ?? 20 }}px;
            ">

            {{ $data[$field->field_name] ?? '' }}

        </div>

    @endforeach

</div>