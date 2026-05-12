<!DOCTYPE html>
<html>
<head>
    <title>Certificate Verification</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .status-valid {
            color: green;
            font-size: 22px;
            font-weight: bold;
        }

        .status-invalid {
            color: red;
            font-size: 22px;
            font-weight: bold;
        }

        .info {
            margin-top: 20px;
            text-align: left;
        }

        .info p {
            margin: 8px 0;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            background: #eee;
            border-radius: 6px;
            font-size: 13px;
        }
    </style>

</head>
<body>

<div class="container">

    @if($cert)

        <div class="status-valid">
            ✔ CERTIFICATE VALID
        </div>

        <div class="info">

            <p><span class="label">Certificate No:</span> {{ $cert->certificate_no }}</p>

            <p><span class="label">Issued To:</span>
                {{ $cert->participant->employee->FIRSTNAME }}
                {{ $cert->participant->employee->LASTNAME }}
            </p>

            <p><span class="label">Program:</span>
                {{ $cert->participant->batch->program_code }}
            </p>

            <p><span class="label">Batch:</span>
                {{ $cert->participant->batch->batch }}
            </p>

            <p><span class="label">Venue:</span>
                {{ $cert->participant->batch->venue }}
            </p>

            <p><span class="label">Hours:</span>
                {{ $cert->participant->hours }}
            </p>

            <p><span class="label">Issued Date:</span>
                {{ $cert->issued_at }}
            </p>

            <p><span class="label">Status:</span>
                <span class="badge">OFFICIAL RECORD</span>
            </p>

        </div>

    @else

        <div class="status-invalid">
            ✖ INVALID CERTIFICATE
        </div>

        <p style="margin-top:20px;">
            The certificate code does not exist or has been tampered with.
        </p>

    @endif

</div>

</body>
</html>