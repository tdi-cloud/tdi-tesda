<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>TESDA ORDER</title>

    <style>
        *{
            line-height: 1.25rem;
        }
        h1, p{
            padding: 0;
            margin: 0;
        }
        @page {
            size: A4;
            margin: 0.8in 0.5in 0.5in 0.5in ;
        }

        .page-number:before {
            content: "Page " counter(page) " of " counter(pages);
        }
        p {
            text-align: justify;
            width: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            border: 3px solid black;
            padding: 0;
            position: relative;
            margin: 0;
            padding-top: 22%;
            min-height: 100vh;
        }

        .main-title {
            text-align: center;
            position: fixed;
            top: -14px;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 0;
        }

        .title {
            font-size: 37px;
            font-weight: bold;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .top-header {
            font-size: 16px;
            font-weight: bold;
            position: fixed;
            top: 0;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1px solid black;
        }

        .top-header td,
        th {
            vertical-align: top;
            padding: 5px 6px;
            line-height: 1;
            text-align: justify;
        }

        .MAIN{
            margin: 0.06in 0.4in;
        }
        .main-description{
            text-align: justify;
            text-indent: 40px;
            line-height: 1.25rem;
            width: 100%;
        }

        ul, ol, li { text-indent: 0;}


    </style>
</head>

<body class="page-break">

    <div class="main-title">
        <h1 class="title">TESDA ORDER</h1>
    </div>

    <table class="top-header" border="1" style=" min-height: 200px;">
        <tr>
            
            <td colspan="2">
                Subject: {{ $program->subject}}
            </td>

            <td style="text-align: left">
                <br>
                Number _____ Series of {{ $program->series}}
            </td>
        </tr>
        <tr>
            <td>Date issued: 
                <br>
                <br>
                {{ $program->date_issued}}
            </td>
            <td>
                Effectivity:
                <br>
                <br>
                {{ $program->effectivity }}
            </td>
            <td>Supersedes:
                <br>
                <br>
                {{ $program->supersedes }}
            </td>
        </tr>
    </table>

    <div class="MAIN">

        <div class="main-description"  >
            <p style="text-align: justify;">{!! $program->body !!}</p>
        </div>
        <br>
        @if($program->with_employees == 1)

        @foreach($program->batches as $batch)

        @if($program->with_batch == 1)
        @php
            $start = \Carbon\Carbon::createFromFormat('d/m/Y', $batch->date_start);
            $end = \Carbon\Carbon::createFromFormat('d/m/Y', $batch->date_end);
        @endphp

            <p>
            <b>{{ $batch->batch }}:</b>
            @if($start->format('F Y') === $end->format('F Y'))
                {{ $start->format('d') }} - {{ $end->format('d F Y') }}
            @else
                {{ $start->format('d F Y') }} - {{ $end->format('d F Y') }}
            @endif
            </p>
            <br>

        @endif
        
        

        <table border="1" style="border-collapse: collapse; width: 100%; text-align: left; font-size: 16px !important;">
            <thead>
                <tr>
                    <th style="text-align: center;">OFFICE</th>
                    <th style="text-align: center;">NAME</th>
                    <th style="text-align: center;">POSITION</th>
                </tr>
            </thead>

            <tbody>
                @foreach($batch->participants as $participant)
                        
                    <tr>
                        <td style="width: 33.33%; padding: 4px; vertical-align: middle;">
                            {{ $participant->employee['OFFICE/DIVISION'] ?? '' }}
                        </td>

                        <td style="width: 33.33%; padding: 4px; vertical-align: middle;">
                            {{ $participant->employee->FIRSTNAME }} {{ $participant->employee->MI }} {{ $participant->employee->LASTNAME }}
                        </td>

                        <td style="width: 33.33%; padding: 4px; vertical-align: middle;">
                            {{ $participant->employee->POSITION }}
                        </td>
                    </tr>
                @endforeach
                    
               
            </tbody>
        </table>
        <br>
        @endforeach

      
        @endif
        <div class="closure main-description">
            {!! $program->closure !!}
        </div>

        {{-- SIGNATORY --}}
        <div>
        <div style="height: 50px;"></div>

        <div style="width: 100%; text-align: right;">
            <div style="
                display: inline-block;
                white-space: nowrap;
                text-align: center;
                padding: 5px 10px;
            ">
                <div><b>{!! $program->signatory_name !!}</b></div>
                <div>{!! $program->signatory_position !!}</div>
            </div>
        </div>

        </div>


    </div>
</body>
</html>
