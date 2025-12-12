<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Machine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // -----------------------------
        // 1. Get Employee by employee_code
        // -----------------------------
        $employeeCode = $row['employee_id'] ?? null;
        if (!$employeeCode) return null;

        $employee = Employee::where('employee_code', $employeeCode)->first();
        if (!$employee) return null;

        // -----------------------------
        // 2. Get Machine ID by machine name
        // -----------------------------
        $machineName = $row['machine_id'] ?? null;
        $machine = Machine::where('name', $machineName)->first();
        $machineId = $machine->id ?? 7; // fallback default

        // -----------------------------
        // 3. Convert status text to number
        // -----------------------------
        $statusText = strtolower($row['status'] ?? '');
        $statusMap = [
            'present'   => 1,
            'half day'  => 2,
            'half_day'  => 2,
            'leave'     => 0,
        ];
        $status = $statusMap[$statusText] ?? 1;

        // -----------------------------
        // 4. Prepare attendance data
        // -----------------------------
        $attendanceData = [
            'employee_id'  => $employee->id,
            'machine_id'   => $machineId,
            'date'         => $this->convertDateOnly($row['date'] ?? null),
            'shift_type'   => $row['shift_type'] ?? null,
            'slot1'        => $this->convertDate($row['slot1'] ?? null),
            'slot2'        => $this->convertDate($row['slot2'] ?? null),
            'slot3'        => $this->convertDate($row['slot3'] ?? null),
            'clock_in'     => $this->convertDate($row['clock_in'] ?? null),
            'clock_out'    => $this->convertDate($row['clock_out'] ?? null),
            'status'       => $status,
            'scan_status'  => 1,
            'marked_by'    => auth()->id() ?? 1,
        ];

        // -----------------------------
        // 5. Update if exists, otherwise create
        // -----------------------------
        return Attendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date'        => $this->convertDateOnly($row['date'] ?? null),
                'shift_type'  => $row['shift_type'] ?? null,
            ],
            $attendanceData
        );
    }

    private function convertDate($value)
    {
        if (!$value) return null;

        if (is_numeric($value)) {
            return Carbon::instance(
                ExcelDate::excelToDateTimeObject($value)
            )->format('Y-m-d H:i:s');
        }

        return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d H:i:s');
    }

    private function convertDateOnly($value)
    {
        if (!$value) return null;
        return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d');
    }
}
