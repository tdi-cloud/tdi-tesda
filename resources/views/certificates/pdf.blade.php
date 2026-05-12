<html>

<body

style="
margin:0;
width:100%;
height:100%;
background-image:url('{{ asset('storage/'.$template->background_image) }}');
background-size:cover;
position:relative;
font-family:Arial;
"

>

<div

style="
position:absolute;
top:280px;
left:250px;
font-size:42px;
font-weight:bold;
"

>

{{ $employee->FIRSTNAME }}
{{ $employee->LASTNAME }}

</div>

<div

style="
position:absolute;
top:360px;
left:220px;
font-size:24px;
"

>

has successfully completed

<strong>
{{ $program->title }}
</strong>

</div>

<div

style="
position:absolute;
top:420px;
left:220px;
font-size:20px;
"

>

Venue:
{{ $batch->venue }}

</div>

<div

style="
position:absolute;
top:460px;
left:220px;
font-size:20px;
"

>

Hours Completed:
{{ $participant->hours }}

</div>

<div

style="
position:absolute;
bottom:120px;
right:240px;
"

>

<img
src="{{ asset('storage/'.$template->signature_image) }}"
width="180">

</div>

<div

style="
position:absolute;
bottom:70px;
right:220px;
"

>

Training Director

</div>

<div

style="
position:absolute;
bottom:80px;
left:120px;
"

>

<img
src="data:image/png;base64,{{ $qr }}"
width="120">

</div>

</body>

</html>