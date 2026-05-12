<!DOCTYPE html>
<html>
<head>
    <title>Certificate Preview</title>
</head>

<body style="background:#ddd;">

<div style="
    display:flex;
    justify-content:center;
    padding:30px;
">

    <div style="transform:scale(.9);">

        @include('certificate.template', [

            'fullname' => $fullname,

            'program' => $program->title,

            'venue' => $p->batch->venue,

            'hours' => $p->hours,

            'signatory_name' => $setup->signatory_name,

            'signatory_position' => $setup->signatory_position,

            'qr' => '',

            'background' => asset('storage/'.$setup->background_path),

            'signature' => $setup->signature_path
                ? asset('storage/'.$setup->signature_path)
                : 'null',

            'setup' => $setup
        ])

    </div>

</div>

</body>
</html>