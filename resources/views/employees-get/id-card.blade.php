<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        /* Ensure NO content overflows */
        .page {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .card-box {
            width: 100%;
            /* height: auto; */
            border: 1px solid #000;
            padding: 10px;
            font-size: 13px;
            line-height: 1.3;
        }

        /* .photo-box {
            width: 100%;
            height: 90px;
            border: 1px dashed #000;
            text-align: center;
            line-height: 90px;
            margin-top: 10px;
        } */
    </style>
</head>
<body>

{{-- ================= FRONT SIDE ================= --}}
<div class="page">
    <div class="card-box">
        <h3 class="label" style="text-align:center;margin:0;padding:0;">FRONT SIDE</h3>
        <h3 style="text-align:center;margin:0;padding:0;">
            ID : {{ $employee->employee_code }}
        </h3>

        <p><strong>Name:</strong> {{ $employee->name }}</p>
        <p><strong>Gender:</strong> {{ ucfirst($employee->gender) }}</p>

        <!-- <p><strong>Contractor / Company:</strong>
            @if($employee->employee_type === 'company')
                Company Employee
            @else
                {{ $employee->contractor->name ?? 'N/A' }}
            @endif
        </p> -->
        <!-- Main Heading -->
        <!-- <p style="margin-bottom: 4px;"><strong>Contractor / Company:</strong></p> -->

        <!-- Dynamic Employee Type Line -->
        <div>
            @if($employee->employee_type === 'company')
                <strong>Employee Type:</strong> Company
            @else
                <strong style="margin-top: -20px;">Employee Type: </strong> Contractor - {{ $employee->contractor->name ?? 'N/A' }}
            @endif
        </div>
    </div>
</div>

{{-- FORCE NEW PAGE --}}
<div style="page-break-after: always;"></div>

{{-- ================= BACK SIDE ================= --}}
<div class="page">
    <div class="card-box">
       <h3 class="label" style="text-align:center;margin:0;padding:0;">BACK SIDE</h3>
        <h3 style="text-align:center;margin:0;padding:0;">
            ID : {{ $employee->employee_code }}
        </h3>

        <p><strong>Mobile:</strong> {{ $employee->mobile }}</p>

        <p>
            @php
                $machineName = $machine->firstWhere('id', $employee->machine_id)?->name ?? 'Unassigned';
            @endphp
            <strong>Machine:</strong> {{ $machineName }}
        </p>

        {{-- PHOTO (UNCHANGED AS REQUESTED) --}}
        @if($employee->photo)
            <img src="{{ public_path('employees/photos/' . basename($employee->photo)) }}"
                 style="width:30px;height:auto;">
        @else
            <div class="photo-box">PHOTO</div>
        @endif
    </div>
</div>

</body>
</html>