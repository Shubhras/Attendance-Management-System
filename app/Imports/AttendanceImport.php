<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Machine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        // -----------------------------
        // 1. Validate employee exists
        // -----------------------------
        $employee = Employee::where('employee_code', $row['employee_id'])->first();
        if (!$employee) {
            return null; // Or optional: throw new \Exception("Employee not found");
        }

        // -----------------------------
        // 2. Machine ID
        // -----------------------------
        $machine = Machine::where('name', $row['machine_id'])->first();
        $machineId = $machine->id ?? 7;

        // -----------------------------
        // 3. Status Mapping
        // -----------------------------
        $status = [
            'present'   => 1,
            'half day'  => 2,
            'half_day'  => 2,
            'leave'     => 0,
        ][$row['status']] ?? 1;

        // -----------------------------
        // 4. Attendance Data
        // -----------------------------
        $attendanceData = [
            'employee_id'  => $employee->id,
            'machine_id'   => $machineId,
            'date'         => $this->convertDateOnly($row['date']),
            'shift_type'   => $row['shift_type'],
            'slot1'        => $this->convertDate($row['slot1']),
            'slot2'        => $this->convertDate($row['slot2']),
            'slot3'        => $this->convertDate($row['slot3']),
            'clock_in'     => $this->convertDate($row['clock_in']),
            'clock_out'    => $this->convertDate($row['clock_out']),
            'status'       => $status,
            'scan_status'  => 1,
            'marked_by'    => auth()->id() ?? 1,
        ];

        // -----------------------------
        // 5. Update or Create
        // -----------------------------
        return Attendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date'        => $this->convertDateOnly($row['date']),
                'shift_type'  => $row['shift_type'],
            ],
            $attendanceData
        );
    }

    // -----------------------------
    // Excel Validation Rules
    // -----------------------------
    public function rules(): array
    {
        return [
            '*.employee_id' => 'required|exists:employees,employee_code',
            '*.date'        => 'required|date',
            '*.shift_type'  => 'required|in:morning,evening,night',
            '*.status'      => 'required|in:present,half day,half_day,leave',
            '*.slot1'       => 'nullable',
            '*.slot2'       => 'nullable',
            '*.slot3'       => 'nullable',
            '*.clock_in'    => 'nullable',
            '*.clock_out'   => 'nullable',
            '*.machine_id'  => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.employee_id.exists' => 'Employee code does not exist in excel file.',
            '*.shift_type.in'      => 'Shift must be morning, evening or night.',
            '*.status.in'          => 'Status must be present, half day or leave.',
        ];
    }

    private function convertDate($value)
    {
        if (!$value) return null;
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d H:i:s');
        }
        return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d H:i:s');
    }

    private function convertDateOnly($value)
    {
        if (!$value) return null;
        return Carbon::parse(str_replace('/', '-', $value))->format('Y-m-d');
    }
}
