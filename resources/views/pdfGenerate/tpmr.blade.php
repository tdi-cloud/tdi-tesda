<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TPMR Report</title>

    <style>
        p, h1,h2,h3{
            padding: 0;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px !important;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 11px;
        }

        table th {
            background: #f2f2f2;
        }

        .signature-section {
            margin-top: 60px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border:none !important;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: left;
            border:none !important;
        }

        .line {
            font-weight: bold;
            border-bottom: 1px solid black;
            min-width: 10px;
        }

        .bold{
            font-weight: bold;
        }

        .small {
            font-size: 13px;
            padding: 0;
        }
        .indication{
            width: 200px;
            border-top: 1px solid black;
            display: inline-block;
        }
        .number{
            width: 100%;
            text-align: right;
            padding: 0;
            
        }
        h3{
            padding: 0;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="number small">
        <p style="line-height: 15px;">TESDA-OP-AS-01-F06<br>Rev. No. 00 – 03/01/17</p>
    </div>
    <div class="header">
        <h3>Technical Education and Skills Development Authority </h3>
        @if($filter === 'monthly' && $month && $year)
        <p class="small">{{ $region }}</p>
        @endif
        <h2>TRAINING PROGRAM MONITORING REPORT</h2>
        <p class="small">
            @if($filter === 'monthly' && $month && $year)
                For the Month of {{ \Carbon\Carbon::create()->month((int) $month)->format('F') }}, {{ $year }}

            @elseif($filter === 'annual' && $year)
                For the Year {{ $year }}

            @else
                As of {{ \Carbon\Carbon::now()->format('F Y') }}
            @endif
        </p>
    </div>

    {{-- TABLE --}}
    <table class="table">
        <thead>
            <tr>
                <th>Training Program</th>
                <th>Start</th>
                <th>End</th>
                <th>Name of Participants</th>
                <th>Office</th>
                <th>Position</th>
                <th>Status of Implementation of Program (Completed/ Not Completed (NC))</th>
            </tr>
        </thead>

        <tbody>
            @foreach($reports as $r)
            <tr>
                <td>{{ $r->program_title }}</td>
                <td>{{ $r->date_start }}</td>
                <td>{{ $r->date_end }}</td>
                <td>{{ $r->FIRSTNAME }} {{ $r->MI }} {{ $r->LASTNAME }} </td>
                <td>{{ $r->office }}</td>
                <td>{{ $r->POSITION }}</td>
                <td>
                    {{ $r->attendance === 'Complete' ? 'Completed' : 'Not Complete' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SIGNATURES (AFTER TABLE) --}}
    <div class="signature-section">

        <table class="signature-table" style="">
            <tr>
                <td>
                    <strong>Prepared by:</strong>

                
                        <div style="margin-top: 40px;">
                            <div class="small bold " >{{ $preparedBy['name'] }}</div>
                            <div class="indication" >Name (Signature over printed name)</div>
                        </div>

                        <div style="margin-top: 10px;">
                            <div class="small bold " >{{ $preparedBy['position']  }}</div>
                            <div class="" >Position / Office</div>
                        </div>

                        <div style="margin-top: 10px;">
                            <div class="" >Date: {{ $preparedBy['prepared_date'] }}</div>
                        </div>
                        
                </td>

                <td style="">
                    <strong>Noted by:</strong>
                    
                    <div style="margin-top: 40px;">
                        <div class="small bold " >{{ $notedBy['name'] }}</div>
                        <div class="indication" >Name (Signature over printed name)</div>
                    </div>

                    <div style="margin-top: 10px;">
                        <div class="small bold " >{{ $notedBy['position'] }}</div>
                        <div class="" >Position / Office</div>
                    </div>

                    <div style="margin-top: 10px;">
                        <div class="" >Date: {{ $notedBy['noted_date']}}</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>