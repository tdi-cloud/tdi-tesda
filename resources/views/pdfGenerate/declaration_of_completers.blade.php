<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Declaration of Completers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #000;
            padding: 96px 96px;
            padding-top: 202px;
            line-height: 1.5;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .body-text {
            font-size: 12pt;
            text-align: justify;
            text-indent: 40px;
            margin-bottom: 24px;
            line-height: 1.4;
        }

        .participants-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11pt;
        }

        .participants-table thead tr {
            background-color: #fff;
            color: #000;
        }

        .participants-table th {
            border: 1px solid #000;
            padding: 7px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
        }

        .participants-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: middle;
        }

        .participants-table tbody tr:nth-child(even) {
            background-color: #fff;
        }

        .td-no {
            text-align: center;
            width: 5%;
        }

        .td-office {
            width: 28%;
        }

        .td-name {
            width: 37%;
        }

        .td-position {
            width: 30%;
        }

        .issued-date {
            font-size: 12pt;
            margin-bottom: 30px;
            line-height: 1.4;
            text-indent: 40px;
        }

        .signatory-section {
            width: 100%;
            margin-top: 80px;
        }

        .signatory-block {
            float: right;
            text-align: center;
            width: 45%;
        }

        .signatory-name {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }

        .signatory-position {
            font-size: 11pt;
            display: none;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>

    {{-- Title --}}
    <div class="title">Declaration of Completers</div>

    {{-- Body Paragraph --}}
    <div class="body-text">
        This Declaration of Completers is hereby issued to certify that the following
        {{ $personnelLabel }} of the Technical Education and Skills Development Authority (TESDA)
        have satisfactorily completed the <strong>{{ $program->title }} {{ $batch->batch }}</strong>
        held on <strong>{{ $dateRange }}</strong>
        (<strong>{{ $batch->hours }} hours</strong>)
        at the TESDA Central Office, Taguig City:
    </div>

    {{-- Participants Table --}}
    <table class="participants-table">
        <thead>
            <tr>
                <th class="td-no">No.</th>
                <th class="td-office">OFFICE</th>
                <th class="td-name">NAME</th>
                <th class="td-position">POSITION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $participant)
                @php
                    $emp = $participant->employee;
                    $mi  = ($emp->MI && strtolower(trim($emp->MI)) !== 'n/a' && trim($emp->MI) !== '')
                           ? ' ' . trim($emp->MI)
                           : '';
                    $fullname = strtoupper(trim($emp->FIRSTNAME . $mi .' '.$emp->LASTNAME));
                    $office   = $emp->OFFICE ?? $emp->{'OFFICE/DIVISION'} ?? '—';
                @endphp
                <tr>
                    <td class="td-no">{{ $index + 1 }}</td>
                    <td class="td-office">{{ $emp['OFFICE/DIVISION'] }}</td>
                    <td class="td-name">{{ $fullname }}</td>
                    <td class="td-position">{{ $emp->POSITION }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Issued Date --}}
    <div class="issued-date">
        Issued this {{ \Carbon\Carbon::now()->format('jS') }} day of
        {{ \Carbon\Carbon::now()->format('F Y') }}
        at the TESDA Central Office, East Service Road, Fort Bonifacio, Taguig City.
    </div>

    {{-- Signatory --}}
    <div class="signatory-section clearfix">
        <div class="signatory-block">
            <div class="signatory-name">{{ $signatoryName }}</div>
            <div class="signatory-position">{{ $signatoryPosition }}</div>
        </div>
    </div>

</body>
</html>