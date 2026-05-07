<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 15px;
            /* Define page margin here */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            color: #000;
            font-size: 14px;
        }

        .page {
            width: 100%;
            /* Removed height: 100% and padding which sometimes conflict in DOMPDF */
            box-sizing: border-box;
        }

        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 26px;
            /* Increased vertical spacing to fill page */
        }

        .card-cell {
            width: 50%;
            height: 240px;
            vertical-align: top;
            padding: 0px;
        }

        .card-box {
            border: 2px solid #000;
            border-radius: 15px;
            padding: 18px;
            height: 185px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        .id-header {
            text-align: center;
            font-size: 18px;
            /* Slightly smaller */
            font-weight: bold;
            margin-bottom: 15px;
            margin-top: 5px;
        }

        .field {
            font-size: 13px;
            /* Reduced text size */
            margin-bottom: 8px;
            /* Reduced bottom margin */
            line-height: 1.3;
        }
    </style>
</head>

<body>

    @foreach($chunks as $chunk)
    <div class="page" @if(!$loop->last) style="page-break-after: always;" @endif>
        <table class="grid-table">
            @foreach($chunk as $employee)
            <tr>
                {{-- FRONT SIDE --}}
                <td class="card-cell">
                    <div class="card-box">
                        <div class="id-header">
                            ID : {{ $employee->employee_code }}
                        </div>

                        <div class="field"><strong>Name:</strong> {{ $employee->name }}</div>
                        <div class="field"><strong>Gender:</strong> {{ ucfirst($employee->gender) }}</div>

                        <div class="field" style="margin-top: 25px;">
                            @if($employee->employee_type === 'company')
                            <strong>Employee Type:</strong> Company
                            @else
                            <strong>Employee Type: </strong> Contractor - {{ $employee->contractor->name ?? 'N/A' }}
                            @endif
                        </div>
                    </div>
                </td>

                {{-- BACK SIDE --}}
                <td class="card-cell">
                    <div class="card-box">
                        <div class="id-header">
                            ID : {{ $employee->employee_code }}
                        </div>

                        <div class="field"><strong>Mobile:</strong> {{ $employee->mobile }}</div>

                        <div class="field">
                            @php
                            $machineName = $machine->firstWhere('id', $employee->machine_id)?->name ?? 'Unassigned';
                            @endphp
                            <strong>Machine:</strong> {{ $machineName }}
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach

            {{-- Fill empty rows if there are less than 4 employees in this chunk --}}
            @php $count = count($chunk); @endphp
            @while($count < 4) <tr>
                <td class="card-cell" style="border: none;">&nbsp;</td>
                <td class="card-cell" style="border: none;">&nbsp;</td>
                </tr>
                @php $count++; @endphp
                @endwhile
        </table>
    </div>
    @endforeach

</body>

</html>