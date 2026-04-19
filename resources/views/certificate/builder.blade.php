<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<style>
#canvas {
    position: relative;
    width: 1123px;
    height: 794px;
    margin: auto;
    overflow: hidden;
    border: 1px solid #ccc;
}

#canvas img {
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    object-fit: cover;
    z-index: 0;
}

.draggable {
    position: absolute;
    z-index: 2;
    padding: 0;
    cursor: move;
    user-select: none;
    white-space: nowrap;
    outline: 1px dashed rgba(0,0,0,0.5);
    transform: translateX(-50%); /* center on the x point */
}

.draggable:hover {
    outline: 1px dashed #e74c3c;
}
</style>

<div id="canvas">
    <img src="{{ asset('storage/'.$template->background) }}">

    @foreach($template->fields as $field)
        <div class="draggable"
             data-field="{{ $field->field_name }}"
             style="
                left: {{ $field->x }}px;
                top: {{ $field->y }}px;
                font-size: {{ $field->font_size }}px;
                font-weight: {{ $field->font_weight }};
                text-align: {{ $field->text_align }};
                font-family: 'Poppins', sans-serif;
                color: #00177d;
                @if($field->field_name === 'name') font-weight: bold; @endif
             ">
            {{ strtoupper($field->field_name) }}
        </div>
    @endforeach
</div>

<script>
$('.draggable').draggable({
    containment: "#canvas",
    stop: function (event, ui) {
        const el = $(this);
        $.post('/certificate/save-position', {
            _token:      '{{ csrf_token() }}',
            field_name:  el.data('field'),
            x:           ui.position.left,
            y:           ui.position.top,
            template_id: {{ $template->id }}
        }).done(function() {
            el.css('outline', '1px dashed green');
            setTimeout(() => el.css('outline', '1px dashed rgba(0,0,0,0.5)'), 1000);
        });
    }
});
</script>