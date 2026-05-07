<?php

namespace App\Http\Controllers;

use App\Models\{Attendance, Machine};
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /* ----------------------------
       LIST ATTENDANCE (MASS MARK UI)
    ----------------------------- */
// public function index(Request $request)
// {
//     $perPage = $request->get('per_page', 10);
//     $search  = $request->get('search');
//     $range   = $request->get('range');

//     // Default: Today
//     $date = $request->get('date', Carbon::today('Asia/Kolkata')->format('Y-m-d'));

//     // Base employee query
//     // $query = Employee::with('shift')->orderBy('name');
//   // Base employee query (ONLY fingerprint = 1)
//     $query = Employee::with('shift')
//         ->where('fingerprint', 1)
//         ->orderBy('name');
//     if ($search) {
//         $query->where(function($q) use ($search) {
//             $q->where('name', 'like', "%$search%")
//               ->orWhere('employee_code', 'like', "%$search%");
//         });
//     }

//     $employees = $query->paginate($perPage)->withQueryString();

//     // Attendance query
//     $attendanceQuery = Attendance::whereIn('employee_id', $employees->pluck('id'));

//     // RANGE FILTERS
//     switch ($range) {
//         case 'daily':
//             $attendanceQuery->where('date', $date);
//             break;

//         case 'monthly':
//             $attendanceQuery->whereMonth('date', Carbon::parse($date)->month)
//                             ->whereYear('date', Carbon::parse($date)->year);
//             break;

//         case '3months':
//             $attendanceQuery->whereBetween('date', [
//                 Carbon::parse($date)->subMonths(3),
//                 Carbon::parse($date)
//             ]);
//             break;

//         case '6months':
//             $attendanceQuery->whereBetween('date', [
//                 Carbon::parse($date)->subMonths(6),
//                 Carbon::parse($date)
//             ]);
//             break;

//         default:
//             $attendanceQuery->where('date', $date);
//     }

//     $attendances = $attendanceQuery->get();

//     // Map employee_id → attendance
//     $attendanceMap = [];
//     foreach ($attendances as $a) {
//         $attendanceMap[$a->employee_id] = $a;
//     }

//     return view('attendance.index', compact('employees', 'attendanceMap', 'date'));
// }
public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $search  = $request->get('search');
    $range   = $request->get('range');
    $date    = $request->get('date', Carbon::today()->format('Y-m-d'));

    // Fetch employees (fingerprint = 1)
$query = Employee::with(['shift', 'machine'])
        ->where('fingerprint', 1)
        ->orderBy('name');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('employee_code', 'like', "%$search%");
        });
    }

    $employees = $query->paginate($perPage)->withQueryString();

    // Attendance
    $attendanceQuery = Attendance::select(
        'id',
        'employee_id',
        'machine_id',
        'slot1',
        'slot2',
        'slot3',
        'shift_type',
        'status',
        'clock_in',
        'clock_out',
        'date',
        'marked_by'
    )->whereIn('employee_id', $employees->pluck('id'));

    switch ($range) {
        case 'daily':
        default:
            $attendanceQuery->whereDate('date', $date);
            break;

        case 'monthly':
            $attendanceQuery->whereMonth('date', Carbon::parse($date)->month)
                            ->whereYear('date', Carbon::parse($date)->year);
            break;

        case '3months':
            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(3),
                Carbon::parse($date)
            ]);
            break;

        case '6months':
            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(6),
                Carbon::parse($date)
            ]);
            break;
    }

    $attendances = $attendanceQuery->with('machine', 'marker')->get();
    $machine = Machine::orderBy('name')->get();
    // group by employee — returns collection per employee
    $attendanceMap = $attendances->groupBy('employee_id');
    /*
    |--------------------------------------------------------------------------
    | Machine Summary
    |--------------------------------------------------------------------------
    */

    $machineSummary = Machine::with('employees')
        ->get()
        ->map(function ($machine) use ($date) {

            $employeeIds = $machine->employees->pluck('id');

            // Attendance count
            $attendanceCount = Attendance::whereDate('date', $date)
                ->whereIn('employee_id', $employeeIds)
                ->distinct('employee_id')
                ->count('employee_id');

            return [
                'machine_name'      => $machine->name,
                'total_employee'    => $machine->employees->count(),

                'present_count'     => $attendanceCount,

                'male_count'        => $machine->employees
                                            ->where('gender', 'male')
                                            ->count(),

                'female_count'      => $machine->employees
                                            ->where('gender', 'female')
                                            ->count(),

                'company_employee'  => $machine->employees
                                            ->where('employee_type', 'company')
                                            ->count(),

                'contractor_employee' => $machine->employees
                                            ->where('employee_type', 'contractor')
                                            ->count(),
            ];
        });
    return view('attendance.index', compact('employees', 'attendanceMap', 'date','machine','machineSummary'));
}
public function exportwithempAttendance1(Request $request)
{
    $date = $request->date ?? Carbon::today()->format('Y-m-d');

    $attendance = Attendance::with([
            'employee.contractor',
            'machine'
        ])
        ->whereDate('date', $date)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Machine Summary
    |--------------------------------------------------------------------------
    */

    $machineSummary = Machine::with(['employees.contractor'])
        ->get()
        ->map(function ($machine) use ($date) {

            $employeeIds = $machine->employees->pluck('id');

            $attendanceCount = Attendance::whereDate('date', $date)
                ->whereIn('employee_id', $employeeIds)
                ->distinct('employee_id')
                ->count('employee_id');

            return [
                'machine_name'        => $machine->name,

                'total_employee'      => $machine->employees->count(),

                'attendance_count'    => $attendanceCount,

                'male_count'          => $machine->employees
                                                ->where('gender', 'male')
                                                ->count(),

                'female_count'        => $machine->employees
                                                ->where('gender', 'female')
                                                ->count(),

                'company_employee'    => $machine->employees
                                                ->where('employee_type', 'company')
                                                ->count(),

                'contractor_employee' => $machine->employees
                                                ->where('employee_type', 'contractor')
                                                ->count(),

                // Contractor Names
                'contractors'         => $machine->employees
                                                ->where('employee_type', 'contractor')
                                                ->pluck('contractor.name')
                                                ->filter()
                                                ->unique()
                                                ->implode(', '),

                // Employee Names
                'employee_names'      => $machine->employees
                                                ->pluck('name')
                                                ->implode(', '),
            ];
        });

    $pdf = PDF::loadView('attendance.pdf', [
        'attendance'      => $attendance,
        'machineSummary'  => $machineSummary,
        'date'            => $date,
        'generatedAt'     => now()->format('d-m-Y h:i A')
    ]);

    return $pdf->download('attendance-report.pdf');
}
public function exportwithempAttendance(Request $request)
{
    $date  = $request->date ?? Carbon::today()->format('Y-m-d');
    $range = $request->range ?? 'daily';

    /*
    |--------------------------------------------------------------------------
    | Attendance Query
    |--------------------------------------------------------------------------
    */

    $attendanceQuery = Attendance::with([
        'employee.contractor',
        'machine'
    ]);

    switch ($range) {

        case 'monthly':

            $attendanceQuery->whereMonth('date', Carbon::parse($date)->month)
                            ->whereYear('date', Carbon::parse($date)->year);

            break;

        case '3months':

            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(3),
                Carbon::parse($date)
            ]);

            break;

        case '6months':

            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(6),
                Carbon::parse($date)
            ]);

            break;

        case 'daily':
        default:

            $attendanceQuery->whereDate('date', $date);

            break;
    }

    $attendance = $attendanceQuery->get();

    /*
    |--------------------------------------------------------------------------
    | Employee Summary
    |--------------------------------------------------------------------------
    */

    $employeeIds = $attendance->pluck('employee_id')->unique();

    $employees = Employee::with('contractor')
        ->whereIn('id', $employeeIds)
        ->get();

    $totalEmployee = $employees->count();

    $totalAttendance = $attendance
                            ->whereIn('status', [1,2])
                            ->count();

    $maleCount = $employees
                    ->where('gender', 'male')
                    ->count();

    $femaleCount = $employees
                    ->where('gender', 'female')
                    ->count();

    $companyEmployee = $employees
                            ->where('employee_type', 'company')
                            ->count();

    $contractorEmployee = $employees
                            ->where('employee_type', 'contractor')
                            ->count();

    $contractorNames = $employees
                            ->pluck('contractor.name')
                            ->filter()
                            ->unique()
                            ->implode(', ');

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    $pdf = PDF::loadView('attendance.full-report-pdf', [

        'attendance'          => $attendance,

        'date'                => $date,

        'range'               => $range,

        'generatedAt'         => now()->format('d-m-Y h:i A'),

        'totalEmployee'       => $totalEmployee,

        'totalAttendance'     => $totalAttendance,

        'maleCount'           => $maleCount,

        'femaleCount'         => $femaleCount,

        'companyEmployee'     => $companyEmployee,

        'contractorEmployee'  => $contractorEmployee,

        'contractorNames'     => $contractorNames,
    ]);

    return $pdf->download('attendance-report.pdf');
}
public function exportMachineSummary(Request $request)
{
    $date  = $request->date ?? Carbon::today()->format('Y-m-d');
    $range = $request->range ?? 'daily';

    $machineSummary = Machine::with([
        'employees.contractor'
    ])->get()->map(function ($machine) use ($date, $range) {

        $employeeIds = $machine->employees->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Attendance Query
        |--------------------------------------------------------------------------
        */

        $attendanceQuery = Attendance::whereIn('employee_id', $employeeIds);

        switch ($range) {

            case 'monthly':

                $attendanceQuery->whereMonth(
                    'date',
                    Carbon::parse($date)->month
                )->whereYear(
                    'date',
                    Carbon::parse($date)->year
                );

                break;

            case '3months':

                $attendanceQuery->whereBetween('date', [
                    Carbon::parse($date)->subMonths(3),
                    Carbon::parse($date)
                ]);

                break;

            case '6months':

                $attendanceQuery->whereBetween('date', [
                    Carbon::parse($date)->subMonths(6),
                    Carbon::parse($date)
                ]);

                break;

            default:

                $attendanceQuery->whereDate('date', $date);

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Count
        |--------------------------------------------------------------------------
        */

        $attendanceCount = $attendanceQuery
            ->distinct('employee_id')
            ->count('employee_id');

        /*
        |--------------------------------------------------------------------------
        | Contractor Details With Employee Names
        |--------------------------------------------------------------------------
        */

        $contractorDetails = $machine->employees
            ->where('employee_type', 'contractor')
            ->groupBy(function ($emp) {

                return optional($emp->contractor)->name ?? 'Unknown';

            })
            ->map(function ($emps, $contractorName) {

                $employeeNames = $emps->pluck('name')->implode(', ');

                return $contractorName
                    . ' (' . $emps->count() . ')'
                    . ' : '
                    . $employeeNames;

            })
            ->implode(' | ');

        /*
        |--------------------------------------------------------------------------
        | Company Employee Details
        |--------------------------------------------------------------------------
        */

        $companyEmployeeDetails = $machine->employees
            ->where('employee_type', 'company')
            ->pluck('name')
            ->implode(', ');

        return [

            'machine_name' => $machine->name,

            'total_employee' => $machine->employees->count(),

            'attendance_count' => $attendanceCount,

            'male_count' => $machine->employees
                ->where('gender', 'male')
                ->count(),

            'female_count' => $machine->employees
                ->where('gender', 'female')
                ->count(),

            'company_employee' => $machine->employees
                ->where('employee_type', 'company')
                ->count(),

            'contractor_employee' => $machine->employees
                ->where('employee_type', 'contractor')
                ->count(),

            'company_employee_names' => $companyEmployeeDetails ?: '-',

            'contractor_details' => $contractorDetails ?: '-',
        ];
    });

    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    $pdf = PDF::loadView('attendance.machine-summary-pdf', [

        'machineSummary' => $machineSummary,

        'date' => $date,

        'range' => ucfirst($range),

        'generatedAt' => now()->format('d-m-Y h:i A'),

    ])->setPaper('a4', 'landscape');

    return $pdf->download('machine-summary-report.pdf');
}
public function exportEmployeeDetails(Request $request)
{
    $date  = $request->date ?? Carbon::today()->format('Y-m-d');
    $range = $request->range ?? 'daily';

    $attendanceQuery = Attendance::with([
        'employee.contractor',
        'machine'
    ]);

    switch ($range) {

        case 'monthly':

            $attendanceQuery->whereMonth('date', Carbon::parse($date)->month)
                            ->whereYear('date', Carbon::parse($date)->year);

            break;

        case '3months':

            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(3),
                Carbon::parse($date)
            ]);

            break;

        case '6months':

            $attendanceQuery->whereBetween('date', [
                Carbon::parse($date)->subMonths(6),
                Carbon::parse($date)
            ]);

            break;

        default:

            $attendanceQuery->whereDate('date', $date);

            break;
    }

    $attendance = $attendanceQuery->get();

    $pdf = PDF::loadView('attendance.employee-details-pdf', [

        'attendance' => $attendance,
        'date' => $date,
        'range' => $range,
        'generatedAt' => now()->format('d-m-Y h:i A'),

    ]);

    return $pdf->download('employee-attendance-report.pdf');
}

// public function singleMarkForm(Request $request)
// {
//     $employee = Employee::findOrFail($request->employee_id);
//     $date = $request->date ?? now()->format('Y-m-d');

//     return view('attendance.single_mark', compact('employee', 'date'));
// }
public function singleMarkForm(Request $request)
{
    $employee = Employee::with('machine')->findOrFail($request->employee_id);

    $date = $request->date ?? now()->format('Y-m-d');

    // Get machine_id from employee relation
    $machineId = $employee->machine?->id;

    return view('attendance.single_mark', compact('employee', 'date', 'machineId'));
}
// public function storeSingle(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|exists:employees,id',
//         'status'      => 'required|in:0,1,2',
//         'clock_in'    => 'nullable',   // allow any format
//         'clock_out'   => 'nullable',
//         'date'        => 'required|date'
//     ]);

//     $selectedDate = $request->date;

//     // Normalize times to H:i:s
//     $clockIn = $request->clock_in
//         ? date('H:i:s', strtotime($request->clock_in))
//         : null;

//     $clockOut = $request->clock_out
//         ? date('H:i:s', strtotime($request->clock_out))
//         : null;

//     // Combine date + time for the datetime column
//     $clockInDateTime = $clockIn ? ($selectedDate . ' ' . $clockIn) : null;
//     $clockOutDateTime = $clockOut ? ($selectedDate . ' ' . $clockOut) : null;

//     // Save attendance
//     Attendance::updateOrCreate(
//         [
//             'employee_id' => $request->employee_id,
//             'date'        => $selectedDate,  // unique for date + employee
//         ],
//         [
//             'status'    => (int) $request->status,
//             'clock_in'  => $clockIn,
//             'clock_out' => $clockOut,
//             'marked_by' => auth()->id(),
//         ]
//     );

//     // Update employee status
//     Employee::where('id', $request->employee_id)
//         ->update([
//             'attendance_status' => (int) $request->status
//         ]);

//     return redirect()->route('attendance.index')
//         ->with('success', 'Attendance marked successfully!');
// }

// 7 may with slots and machine
// public function storeSingle(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|exists:employees,id',
//         'status'      => 'required|in:0,1,2',
//         'clock_in'    => 'nullable',
//         'clock_out'   => 'nullable',
//         'date'        => 'required|date',
//     ]);

//     $status = (int) $request->status;
//     $selectedDate = $request->date;
//     $now = now();

//     $employee = Employee::findOrFail($request->employee_id);

//     // Auto-detect slot values (like update)
//     $slot1 = $now;
//     $slot2 = $slot1; // default same as slot1
//     $slot3 = $slot2; // default same as slot2

//     // Auto-set machine from employee
//     $machineId = $employee->machine_id ?? null;
//     // Save attendance
//     Attendance::updateOrCreate(
//         [
//             'employee_id' => $employee->id,
//             'date'        => $selectedDate,
//         ],
//         [
//             'status'     => $status,
//             'scan_status'=> $status,
//             'slot1'      => $slot1,
//             'slot2'      => $slot2,
//             'slot3'      => $slot3,
//             'machine_id' => $machineId,
//             'marked_by'  => auth()->id(),
//         ]
//     );

//     // Update employee attendance_status
//     $employee->update(['attendance_status' => $status]);

//     return redirect()
//         ->route('attendance.index')
//         ->with('success', 'Attendance marked successfully!');
// }

public function storeSingle(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'status'      => 'required|in:0,1,2',
        'date'        => 'required|date',
    ]);

    $status = (int) $request->status;
    $selectedDate = $request->date;
    $now = now();

    $employee = Employee::findOrFail($request->employee_id);

    // Find existing attendance
    $attendance = Attendance::firstOrNew([
        'employee_id' => $employee->id,
        'date'        => $selectedDate,
    ]);

    // Machine ID
    $machineId = $employee->machine_id ?? null;

    // Default values
    $clockIn  = null;
    $clockOut = null;

    // Present
    if ($status == 1) {

        $clockIn  = $now->format('H:i:s');
        $clockOut = $now->copy()->addHours(8)->format('H:i:s');

    }
    // Half Day
    elseif ($status == 2) {

        $clockIn  = $now->format('H:i:s');
        $clockOut = $now->copy()->addHours(4)->format('H:i:s');
    }

    // Update attendance
    $attendance->status       = $status;
    $attendance->scan_status  = 0;
    $attendance->slot1        = $now;
    $attendance->slot2        = $now;
    $attendance->slot3        = $now;
    $attendance->clock_in     = $clockIn;
    $attendance->clock_out    = $clockOut;
    $attendance->machine_id   = $machineId;
    $attendance->marked_by    = auth()->id();

    $attendance->save();

    // Update employee status
    $employee->update([
        'attendance_status' => $status
    ]);

    return redirect()
        ->route('attendance.index')
        ->with('success', 'Attendance marked successfully!');
}

    /* ----------------------------
       MASS ATTENDANCE SAVE
    ----------------------------- */
    // public function saveBulk(Request $request)
    // {
    //     $request->validate([
    //         'date' => 'required|date',
    //         'records' => 'required|array',
    //         'records.*.employee_id' => 'required|exists:employees,id',
    //         'records.*.status' => 'required|in:present,half_day,leave,pending',
    //         'records.*.clock_in' => 'nullable|date_format:H:i',
    //         'records.*.clock_out' => 'nullable|date_format:H:i',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $date = $request->date;

    //         foreach ($request->records as $row) {
    //             Attendance::updateOrCreate(
    //                 [
    //                     'employee_id' => $row['employee_id'],
    //                     'date'        => $date
    //                 ],
    //                 [
    //                     'status'    => $row['status'],
    //                     'clock_in'  => $row['clock_in'] ?? null,
    //                     'clock_out' => $row['clock_out'] ?? null,
    //                     'marked_by' => auth()->id(),
    //                 ]
    //             );
    //         }

    //         DB::commit();

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to save: '.$e->getMessage());
    //     }

    //     return back()->with('success', 'Attendance saved successfully!');
    // }
/* ----------------------------
   MASS ATTENDANCE SAVE
----------------------------- */
// befor slots

// public function saveBulk(Request $request)
// {
//     $request->validate([
//         'date' => 'required|date',
//         'records' => 'required|array',
//         'records.*.employee_id' => 'required|exists:employees,id',
//         'records.*.status' => 'required|in:0,1,2',
//         'records.*.clock_in' => 'nullable',   // no strict format
//         'records.*.clock_out' => 'nullable',
//     ]);

//     DB::beginTransaction();

//     try {
//         $selectedDate = $request->date;

//         foreach ($request->records as $row) {

//             $employeeId = $row['employee_id'];
//             $status     = (int) $row['status'];

//             // Normalize time to H:i:s
//             $clockIn = !empty($row['clock_in'])
//                 ? date('H:i:s', strtotime($row['clock_in']))
//                 : null;

//             $clockOut = !empty($row['clock_out'])
//                 ? date('H:i:s', strtotime($row['clock_out']))
//                 : null;

//             // Find existing attendance for that date
//             $attendance = Attendance::where('employee_id', $employeeId)
//                 ->whereDate('date', $selectedDate)
//                 ->first();

//             if ($attendance) {
//                 // UPDATE existing
//                 $attendance->update([
//                     'status'    => $status,
//                     'clock_in'  => $clockIn,
//                     'clock_out' => $clockOut,
//                     'marked_by' => auth()->id(),
//                 ]);
//             } else {
//                 // CREATE new
//                 Attendance::create([
//                     'employee_id' => $employeeId,
//                     'date'        => $selectedDate,
//                     'status'      => $status,
//                     'clock_in'    => $clockIn,
//                     'clock_out'   => $clockOut,
//                     'marked_by'   => auth()->id(),
//                 ]);
//             }

//             // Update Employee table
//             Employee::where('id', $employeeId)
//                 ->update([
//                     'attendance_status' => $status
//                 ]);
//         }

//         DB::commit();
//         return back()->with('success', 'Bulk attendance saved successfully!');

//     } catch (\Throwable $e) {
//         DB::rollBack();
//         return back()->with('error', 'Failed to save: ' . $e->getMessage());
//     }
// }
public function saveBulk(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'records' => 'required|array',
        'records.*.employee_id' => 'required|exists:employees,id',
        'records.*.status' => 'required|in:0,1,2'
    ]);

    DB::beginTransaction();

    try {
        $date = $request->date;

foreach ($request->records as $row) {
    $empId = $row['employee_id'];
    $status = (int)$row['status'];
    $machineId = $row['machine_id'] ?? Employee::find($empId)?->machine_id;
    $now = now();
    $slot1 = $now;
    $slot2 = $slot1; // default same as slot1
    $slot3 = $slot2; // default same as slot2
    $attendance = Attendance::where('employee_id', $empId)
        ->whereDate('date', $date)
        ->first();

    if ($attendance) {
        // Fill next available slot
        if (!$attendance->slot1) {
            $attendance->slot1 = $now;
        } elseif (!$attendance->slot2) {
            $attendance->slot2 = $now;
        } elseif (!$attendance->slot3) {
            $attendance->slot3 = $now;
        }

        $attendance->status     = $status;
        $attendance->scan_status = 1;
        $attendance->machine_id = $machineId;
        $attendance->marked_by  = auth()->id();
        $attendance->save();
    } else {
        Attendance::create([
            'employee_id' => $empId,
            'date'        => $date,
            'machine_id'  => $machineId,
            'slot1'      => $slot1,
            'slot2'      => $slot2,
            'slot3'      => $slot3,
            'status'      => $status,
            'scan_status' => 1,
            'marked_by'   => auth()->id(),
        ]);
    }

    Employee::where('id', $empId)->update(['attendance_status' => $status]);
}


        DB::commit();
        return back()->with('success', 'Bulk attendance saved successfully!');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to save: '.$e->getMessage());
    }
}





    /* ----------------------------
       SIMPLE MARK ATTENDANCE PAGE
    ----------------------------- */
    public function mark()
    {
        $employees = Employee::orderBy('name')->get();
        return view('attendance.mark', compact('employees'));
    }

    /* ----------------------------
       SINGLE STORE (OPTIONAL)
    ----------------------------- */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required',
    //         'status'      => 'required|in:present,absent,leave,pending',
    //     ]);

    //     Attendance::updateOrCreate(
    //         [
    //             'employee_id' => $request->employee_id,
    //             'date'        => Carbon::today('Asia/Kolkata')->format('Y-m-d'),
    //         ],
    //         [
    //             'status'        => $request->status,
    //             'clock_in'      => $request->clock_in,
    //             'clock_out'     => $request->clock_out,
    //             'scan_response' => $request->scan_response,
    //             'marked_by'     => auth()->id(),
    //         ]
    //     );

    //     return back()->with('success', 'Attendance marked successfully!');
    // }
/* ----------------------------
   SINGLE STORE (OPTIONAL)
----------------------------- */
public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'status'      => 'required|in:present,absent,leave,pending',
        'clock_in'    => 'nullable|date_format:H:i',
        'clock_out'   => 'nullable|date_format:H:i',
    ]);

    // 🔁 Convert string status → numeric 1/0
    $status = match ($request->status) {
        'present' => 1,
        'absent', 
        'leave'  => 0,
        default  => null, // pending → NULL
    };

    $today = Carbon::today('Asia/Kolkata')->format('Y-m-d');

    // Save/update attendance entry
    $attendance = Attendance::updateOrCreate(
        [
            'employee_id' => $request->employee_id,
            'date'        => $today,
        ],
        [
            'status'        => $status,
            'clock_in'      => $request->clock_in,
            'clock_out'     => $request->clock_out,
            'scan_response' => $request->scan_response,
            'marked_by'     => auth()->id(),
        ]
    );

    // ✅ Also update employee's current attendance status
    Employee::where('id', $request->employee_id)
        ->update([
            'attendance_status' => $status
        ]);

    return back()->with('success', 'Attendance marked successfully!');
}


    /* ----------------------------
       EDIT SINGLE ATTENDANCE
    ----------------------------- */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    // public function update(Request $request, $id)
    // {
    //     // echo "Update function called";die;
    //     $attendance = Attendance::findOrFail($id);

    //     $request->validate([
    //         'status' => 'required',
    //     ]);

    //     $attendance->update([
    //         'status'    => $request->status,
    //         'clock_in'  => $request->clock_in,
    //         'clock_out' => $request->clock_out,
    //     ]);

    //     return redirect()->route('attendance.index')->with('success', 'Attendance updated!');
    // }
    
    // slot code before
// public function update(Request $request, $id)
// {
//     $attendance = Attendance::findOrFail($id);

//     $request->validate([
//         'status'    => 'required',
//         'clock_in'  => 'nullable|date_format:H:i',
//         'clock_out' => 'nullable|date_format:H:i',
//     ]);

//     // Ensure status is always numeric 0 or 1
//     $status = (int)$request->status; // If coming as "1"/"0"

//     // Update attendance record
//     $attendance->update([
//         'status'    => $status,
//         'clock_in'  => $request->clock_in,
//         'clock_out' => $request->clock_out,
//     ]);

//     // ✅ Update employee table also
//     Employee::where('id', $attendance->employee_id)
//         ->update(['attendance_status' => $status]);

//     return redirect()
//         ->route('attendance.index')
//         ->with('success', 'Attendance updated!');
// }

// 7 may with slots and machine 
// public function update(Request $request, $id)
// {
//     $attendance = Attendance::findOrFail($id);

//     $request->validate([
//         'status' => 'required|in:0,1,2',
//         'clock_in' => 'nullable',
//         'clock_out' => 'nullable',
//         'machine_id' => 'nullable|exists:machines,id',
//     ]);

//     $status = (int)$request->status;
//     $now = now();

//     // Auto-detect slot values
//     $slot1 = $attendance->slot1 ?? $now;
//     $slot2 = $attendance->slot2 ?? ($attendance->slot1 ? $now : null);
//     $slot3 = $attendance->slot3 ?? ($attendance->slot2 ? $now : null);

//     // Machine ID from request or fallback to employee relation
//     $machineId = $request->machine_id ?? $attendance->employee->machine_id ?? null;

//     // Optionally, combine clock_in/out with date
//     $clockIn = $request->clock_in ? $attendance->date->format('Y-m-d') . ' ' . $request->clock_in : $attendance->clock_in;
//     $clockOut = $request->clock_out ? $attendance->date->format('Y-m-d') . ' ' . $request->clock_out : $attendance->clock_out;

//     $attendance->update([
//         'status'      => $status,
//         'scan_status' => $status,
//         'slot1'       => $slot1,
//         'slot2'       => $slot2,
//         'slot3'       => $slot3,
//         'machine_id'  => $machineId,
//         'clock_in'    => $clockIn,
//         'clock_out'   => $clockOut,
//         'marked_by'   => auth()->id(),
//     ]);

//     Employee::where('id', $attendance->employee_id)
//         ->update(['attendance_status' => $status]);

//     return redirect()
//         ->route('attendance.index')
//         ->with('success', 'Attendance updated!');
// }
public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    $request->validate([
        'status'     => 'required|in:0,1,2',
        'machine_id' => 'nullable|exists:machines,id',
    ]);

    $status = (int) $request->status;
    $now = now();

    // Machine ID
    $machineId = $request->machine_id
        ?? $attendance->employee->machine_id
        ?? null;

    /*
    |--------------------------------------------------------------------------
    | Admin Manual Attendance
    |--------------------------------------------------------------------------
    | Admin has full access:
    | - No shift restriction
    | - No time restriction
    | - No clock-in/out required
    | - Directly mark Present / Leave / Half Day
    |--------------------------------------------------------------------------
    */

    $attendance->update([
        'status'      => $status, // 1=Present,0=Leave,2=Half Day
        'scan_status' => 0, // Manual admin entry
        'machine_id'  => $machineId,
        'marked_by'   => auth()->id(),
        'updated_at'  => $now,
    ]);

    // Update employee current attendance status
    Employee::where('id', $attendance->employee_id)
        ->update([
            'attendance_status' => $status
        ]);

    return redirect()
        ->route('attendance.index')
        ->with('success', 'Attendance updated successfully!');
}

    /* ----------------------------
       EXPORT PDF (ALL EMPLOYEES)
    ----------------------------- */
public function exportAll(Request $request)
{
    $range = $request->get('range');
    $date  = $request->get('date', Carbon::today('Asia/Kolkata')->format('Y-m-d'));

    $query = Attendance::with('employee');

    switch ($range) {
        case 'monthly':
            $query->whereMonth('date', Carbon::parse($date)->month)
                ->whereYear('date', Carbon::parse($date)->year);
            $file = "attendance-monthly-".Carbon::parse($date)->format('Y-m').".pdf";
            break;

        case '3months':
            $query->whereBetween('date', [
                Carbon::parse($date)->subMonths(3),
                Carbon::parse($date)
            ]);
            $file = "attendance-3months.pdf";
            break;

        case '6months':
            $query->whereBetween('date', [
                Carbon::parse($date)->subMonths(6),
                Carbon::parse($date)
            ]);
            $file = "attendance-6months.pdf";
            break;

        default:
            $query->where('date', $date);
            $file = "attendance-$date.pdf";
    }

    $attendance = $query->get();
    $generatedAt = Carbon::now('Asia/Kolkata')->format('d-m-Y H:i:s');

    $pdf = Pdf::loadView('attendance.pdf_all', compact('attendance', 'generatedAt', 'date', 'range'))
              ->setPaper('a4');

    return $pdf->download($file);
}


    /* ----------------------------
       EXPORT PDF (SINGLE EMPLOYEE)
    ----------------------------- */
    public function exportEmployee($id)
    {
        $attendance = Attendance::with('employee')->where('employee_id', $id)->get();
        $employee = Employee::findOrFail($id);

        $generatedAt = Carbon::now('Asia/Kolkata')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('attendance.pdf_employee', compact('attendance', 'employee', 'generatedAt'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("{$employee->name}-attendance.pdf");
    }
//     public function summary(Request $request)
// {
//     $month = $request->get('month', Carbon::now()->format('Y-m'));
//     $start = Carbon::parse($month.'-01')->startOfMonth();
//     $end   = Carbon::parse($month.'-01')->endOfMonth();

//     $daysInMonth = $start->daysInMonth;

//     $employees = Employee::orderBy('name')->get();

//     // Fetch all attendance of the month in one query
//     $att = Attendance::whereBetween('date', [$start, $end])->get();

//     // Map attendance by employee + day
//     $attendanceMap = [];
//     foreach ($att as $a) {
//         $day = Carbon::parse($a->date)->format('d');
//         $attendanceMap[$a->employee_id][$day] = $a->status;
//     }

//     return view('attendance.summary', compact('employees', 'month', 'daysInMonth', 'attendanceMap'));
// }

// working code 10dec
// public function summary(Request $request)
// {
//     $month = $request->get('month', Carbon::now()->format('Y-m'));

//     // Prepare start and end dates for selected month
//     $start = Carbon::parse($month . '-01')->startOfMonth();
//     $end   = Carbon::parse($month . '-01')->endOfMonth();

//     $daysInMonth = $start->daysInMonth;

//     // Get employees list
//     $employees = Employee::orderBy('name')->get();

//     // Fetch all attendance for the month (optimized)
//     $attendances = Attendance::whereBetween('date', [$start, $end])->get();

//     // Prepare attendance map (employee_id → day → status)
//     $attendanceMap = [];

//     foreach ($attendances as $a) {
//         $day = Carbon::parse($a->date)->day;  // returns integer day (1–31)
//         $attendanceMap[$a->employee_id][$day] = (int) $a->status; // force integer
//     }

//     return view('attendance.summary', compact('employees', 'month', 'daysInMonth', 'attendanceMap'));
// }
// public function summary(Request $request)
// {
    
//     $month = $request->get('month', Carbon::now()->format('Y-m'));
//     $employeeId = $request->get('employee_id');
//     $searchCode = $request->get('search_code');
//     $start = Carbon::parse($month . '-01')->startOfMonth();
//     $end   = Carbon::parse($month . '-01')->endOfMonth();
//     $daysInMonth = $start->daysInMonth;

//     // Employees
//     $empQuery = Employee::orderBy('name');

//     if ($employeeId) {
//         $empQuery->where('id', $employeeId);
//     }
//        // Add search by employee code
//     if ($searchCode) {
//         $empQuery->where('employee_code', 'like', '%' . $searchCode . '%');
//     }
//     $employees = $empQuery->get();

//     // Attendance
//     $attQuery = Attendance::whereBetween('date', [$start, $end]);
//     if ($employeeId) {
//         $attQuery->where('employee_id', $employeeId);
//     }
//     $attendances = $attQuery->get();

//     // Attendance Map
//     $attendanceMap = [];

//     foreach ($attendances as $a) {
//         $day = Carbon::parse($a->date)->day;
//         $hoursFormatted = null;

//         if (!empty($a->clock_in) && !empty($a->clock_out) && $a->clock_in != "00:00:00" && $a->clock_out != "00:00:00") {

//             // Parse clock_in
//             $in = str_contains($a->clock_in, '-') ?
//                 : Carbon::parse($a->clock_in);

//             // Parse clock_out
//             $out = str_contains($a->clock_out, '-') ?
//                 : Carbon::parse($a->clock_out);

//             // Night shift fix
//             if ($out->lt($in)) {
//                 $out->addDay();
//             }

//             $totalMins = $in->diffInMinutes($out);
//             if ($totalMins < 0) $totalMins = 0;

//             $h = floor($totalMins / 60);
//             $m = $totalMins % 60;

//             // Correct format: 03h 42min
//             $hoursFormatted = sprintf("%02dh %02dmin", $h, $m);
//             // print_r($hoursFormatted);die;
//         }

//         $attendanceMap[$a->employee_id][$day] = [
//             'status' => (int)$a->status,
//             'hours'  => $hoursFormatted
//         ];
//     }

//     return view('attendance.summary', compact(
//         'employees',
//         'month',
//         'daysInMonth',
//         'attendanceMap'
//     ));
// }

//3apr without status active code
// public function summary(Request $request)
// {
//     $month = $request->get('month', Carbon::now()->format('Y-m'));
//     $employeeId = $request->get('employee_id');
//     $searchCode = $request->get('search_code');

//     $start = Carbon::parse($month . '-01')->startOfMonth();
//     $end   = Carbon::parse($month . '-01')->endOfMonth();
//     $daysInMonth = $start->daysInMonth;

//     // Employees
//     $empQuery = Employee::orderBy('name');

//     if ($employeeId) {
//         $empQuery->where('id', $employeeId);
//     }

//     if ($searchCode) {
//         $empQuery->where('employee_code', 'like', '%' . $searchCode . '%');
//     }

//     $employees = $empQuery->get();

//     // Attendance
//     $attQuery = Attendance::whereBetween('date', [$start, $end]);
//     if ($employeeId) {
//         $attQuery->where('employee_id', $employeeId);
//     }
//     $attendances = $attQuery->get();

//     // Slot Hour Definition
//     $slotHours = [
//         'morning' => [
//             'slot1' => 2,
//             'slot2' => 2,
//             'slot3' => 2,
//         ],
//         'night' => [
//             'slot1' => 2,
//             'slot2' => 4,
//             'slot3' => 6,
//         ],
//     ];

//     $attendanceMap = [];

//     foreach ($attendances as $a) {
//         $day = Carbon::parse($a->date)->day;
//         $totalHours = 0;

//         $shift = $a->shift_type ?? 'morning';

//         if (!empty($slotHours[$shift])) {
//             foreach (['slot1', 'slot2', 'slot3'] as $slot) {
//                 if (!is_null($a->$slot)) {
//                     $totalHours += $slotHours[$shift][$slot];
//                 }
//             }
//         }

//         // Format hours → "06h 00min"
//         $hoursFormatted = $totalHours > 0
//             ? sprintf('%02dh 00min', $totalHours)
//             : null;

//         $attendanceMap[$a->employee_id][$day] = [
//             'status' => (int) $a->status,
//             'hours'  => $hoursFormatted,
//         ];
//     }

//     return view('attendance.summary', compact(
//         'employees',
//         'month',
//         'daysInMonth',
//         'attendanceMap'
//     ));
// }
//end
public function summary(Request $request)
{
    $month = $request->get('month', Carbon::now()->format('Y-m'));
    $employeeId = $request->get('employee_id');
    $searchCode = $request->get('search_code');

    $start = Carbon::parse($month . '-01')->startOfMonth();
    $end   = Carbon::parse($month . '-01')->endOfMonth();
    $daysInMonth = $start->daysInMonth;

    // Employees
    $empQuery = Employee::where('is_active', true)->orderBy('name');

    if ($employeeId) {
        $empQuery->where('id', $employeeId);
    }

    if ($searchCode) {
        $empQuery->where('employee_code', 'like', '%' . $searchCode . '%');
    }

    $employees = $empQuery->get();

    // Attendance
    $attQuery = Attendance::whereBetween('date', [$start, $end]);
    if ($employeeId) {
        $attQuery->where('employee_id', $employeeId);
    }
    $attendances = $attQuery->get();

    // Slot Hour Definition
    $slotHours = [
        'morning' => [
            'slot1' => 2,
            'slot2' => 2,
            'slot3' => 2,
        ],
        'night' => [
            'slot1' => 2,
            'slot2' => 4,
            'slot3' => 6,
        ],
    ];

    $attendanceMap = [];

    foreach ($attendances as $a) {
        $day = Carbon::parse($a->date)->day;
        $totalHours = 0;

        $shift = $a->shift_type ?? 'morning';

        if (!empty($slotHours[$shift])) {
            foreach (['slot1', 'slot2', 'slot3'] as $slot) {
                if (!is_null($a->$slot)) {
                    $totalHours += $slotHours[$shift][$slot];
                }
            }
        }

        // Format hours → "06h 00min"
        $hoursFormatted = $totalHours > 0
            ? sprintf('%02dh 00min', $totalHours)
            : null;

        $attendanceMap[$a->employee_id][$day] = [
            'status' => (int) $a->status,
            'hours'  => $hoursFormatted,
        ];
    }

    return view('attendance.summary', compact(
        'employees',
        'month',
        'daysInMonth',
        'attendanceMap'
    ));
}







}
