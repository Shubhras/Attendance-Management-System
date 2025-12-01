<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class OperatorAttendanceController extends Controller
{
    /* ------------------------------------------
       1️⃣ Get Employees Under This Operator
       (Employees where created_by = operator ID)
    -------------------------------------------*/
    public function assignedEmployees(Request $request)
    {
        $operatorId = auth()->user();
        // print_r("operatorId: " . $operatorId);die;
        $employees = Employee::where('employee_code', $operatorId->employee_code)
            ->where('is_operator', 0) // exclude operators
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => true,
            'employees' => $employees
        ]);
    }


    /* ------------------------------------------
       2️⃣ Mark Single Attendance
    -------------------------------------------*/
 public function markAttendance(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'records' => 'required|array',
        'records.*.employee_id' => 'required|exists:employees,id',
        'records.*.status' => 'required|in:present,half_day,leave,pending',
        'records.*.clock_in' => 'nullable|date_format:H:i',
        'records.*.clock_out' => 'nullable|date_format:H:i',
    ]);

    $date = $request->date;
    $markedBy = auth()->id();

    $responseData = [];

    foreach ($request->records as $record) {
        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $record['employee_id'],
                'date' => $date,
            ],
            [
                'status' => $record['status'],
                'clock_in' => $record['clock_in'] ?? null,
                'clock_out' => $record['clock_out'] ?? null,
                'marked_by' => $markedBy
            ]
        );

        $responseData[] = $attendance;
    }

    return response()->json([
        'status' => true,
        'message' => 'Attendance marked successfully!',
        'data' => $responseData
    ]);
}



    /* ------------------------------------------
       3️⃣ Bulk Attendance
    -------------------------------------------*/
    public function bulkMarkAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.employee_id' => 'required|exists:employees,id',
            'records.*.status' => 'required|in:present,half_day,leave,pending',
            'records.*.clock_in' => 'nullable|date_format:H:i',
            'records.*.clock_out' => 'nullable|date_format:H:i',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->records as $row) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $row['employee_id'],
                        'date'        => $request->date
                    ],
                    [
                        'status'    => $row['status'],
                        'clock_in'  => $row['clock_in'] ?? null,
                        'clock_out' => $row['clock_out'] ?? null,
                        'marked_by' => auth()->id(),
                    ]
                );
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Attendance saved successfully!'
        ]);
    }


    /* ------------------------------------------
       4️⃣ Attendance List for Operator
       (Show attendance of employees created by operator)
    -------------------------------------------*/
    public function attendanceList(Request $request)
    {
        $date = $request->get('date');

        $attendance = Attendance::with('employee')
            ->when($date, fn($q) => $q->where('date', $date))
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $attendance
        ]);
    }

    /* ------------------------------------------
       5️⃣ EXPORT PDF (ALL Employees Under Operator)
    -------------------------------------------*/
    public function exportAll(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $operatorId = auth()->user();

        $employeeIds = Employee::where('employee_code', $operatorId->employee_code)
            ->where('is_operator', 0)
            ->pluck('id');

        $attendance = Attendance::with('employee')
            ->whereIn('employee_id', $employeeIds)
            ->where('date', $date)
            ->get();

        $pdf = Pdf::loadView('attendance.pdf_all', [
            'attendance' => $attendance,
            'date' => $date,
            'generatedAt' => now()->format('d-m-Y H:i:s')
        ]);

        return $pdf->download("operator-attendance-$date.pdf");
    }


    /* ------------------------------------------
       6️⃣ EXPORT PDF (Single Employee)
    -------------------------------------------*/
    public function exportEmployee($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $attendance = Attendance::with('employee')
            ->where('employee_id', $employeeId)
            ->orderBy('date', 'desc')
            ->get();

        $pdf = Pdf::loadView('attendance.pdf_employee', [
            'attendance' => $attendance,
            'employee' => $employee,
            'generatedAt' => now()->format('d-m-Y H:i:s')
        ]);

        return $pdf->download("{$employee->name}-attendance.pdf");
    }
}
