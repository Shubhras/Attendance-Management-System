<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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
    $query = Employee::with('shift')
        ->where('fingerprint', 1)
        ->orderBy('name');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('employee_code', 'like', "%$search%");
        });
    }

    $employees = $query->paginate($perPage)->withQueryString();

    // Attendance Fetch (IMPORTANT: select ID)
    $attendanceQuery = Attendance::select(
        'id',
        'employee_id',
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

    $attendances = $attendanceQuery->get();

    // Make Map: emp_id → attendance row
    $attendanceMap = $attendances->keyBy('employee_id');

    return view('attendance.index', compact('employees', 'attendanceMap', 'date'));
}
public function singleMarkForm(Request $request)
{
    $employee = Employee::findOrFail($request->employee_id);
    $date = $request->date ?? now()->format('Y-m-d');

    return view('attendance.single_mark', compact('employee', 'date'));
}

public function storeSingle(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'status'      => 'required|in:0,1,2',
        'clock_in'    => 'nullable',   // allow any format
        'clock_out'   => 'nullable',
        'date'        => 'required|date'
    ]);

    $selectedDate = $request->date;

    // Normalize times to H:i:s
    $clockIn = $request->clock_in
        ? date('H:i:s', strtotime($request->clock_in))
        : null;

    $clockOut = $request->clock_out
        ? date('H:i:s', strtotime($request->clock_out))
        : null;

    // Combine date + time for the datetime column
    $clockInDateTime = $clockIn ? ($selectedDate . ' ' . $clockIn) : null;
    $clockOutDateTime = $clockOut ? ($selectedDate . ' ' . $clockOut) : null;

    // Save attendance
    Attendance::updateOrCreate(
        [
            'employee_id' => $request->employee_id,
            'date'        => $selectedDate,  // unique for date + employee
        ],
        [
            'status'    => (int) $request->status,
            'clock_in'  => $clockIn,
            'clock_out' => $clockOut,
            'marked_by' => auth()->id(),
        ]
    );

    // Update employee status
    Employee::where('id', $request->employee_id)
        ->update([
            'attendance_status' => (int) $request->status
        ]);

    return redirect()->route('attendance.index')
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
public function saveBulk(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'records' => 'required|array',
        'records.*.employee_id' => 'required|exists:employees,id',
        'records.*.status' => 'required|in:0,1,2',
        'records.*.clock_in' => 'nullable',   // no strict format
        'records.*.clock_out' => 'nullable',
    ]);

    DB::beginTransaction();

    try {
        $selectedDate = $request->date;

        foreach ($request->records as $row) {

            $employeeId = $row['employee_id'];
            $status     = (int) $row['status'];

            // Normalize time to H:i:s
            $clockIn = !empty($row['clock_in'])
                ? date('H:i:s', strtotime($row['clock_in']))
                : null;

            $clockOut = !empty($row['clock_out'])
                ? date('H:i:s', strtotime($row['clock_out']))
                : null;

            // Find existing attendance for that date
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('date', $selectedDate)
                ->first();

            if ($attendance) {
                // UPDATE existing
                $attendance->update([
                    'status'    => $status,
                    'clock_in'  => $clockIn,
                    'clock_out' => $clockOut,
                    'marked_by' => auth()->id(),
                ]);
            } else {
                // CREATE new
                Attendance::create([
                    'employee_id' => $employeeId,
                    'date'        => $selectedDate,
                    'status'      => $status,
                    'clock_in'    => $clockIn,
                    'clock_out'   => $clockOut,
                    'marked_by'   => auth()->id(),
                ]);
            }

            // Update Employee table
            Employee::where('id', $employeeId)
                ->update([
                    'attendance_status' => $status
                ]);
        }

        DB::commit();
        return back()->with('success', 'Bulk attendance saved successfully!');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to save: ' . $e->getMessage());
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
public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    $request->validate([
        'status'    => 'required',
        'clock_in'  => 'nullable|date_format:H:i',
        'clock_out' => 'nullable|date_format:H:i',
    ]);

    // Ensure status is always numeric 0 or 1
    $status = (int)$request->status; // If coming as "1"/"0"

    // Update attendance record
    $attendance->update([
        'status'    => $status,
        'clock_in'  => $request->clock_in,
        'clock_out' => $request->clock_out,
    ]);

    // ✅ Update employee table also
    Employee::where('id', $attendance->employee_id)
        ->update(['attendance_status' => $status]);

    return redirect()
        ->route('attendance.index')
        ->with('success', 'Attendance updated!');
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
public function summary(Request $request)
{
    $month = $request->get('month', Carbon::now()->format('Y-m'));

    // Prepare start and end dates for selected month
    $start = Carbon::parse($month . '-01')->startOfMonth();
    $end   = Carbon::parse($month . '-01')->endOfMonth();

    $daysInMonth = $start->daysInMonth;

    // Get employees list
    $employees = Employee::orderBy('name')->get();

    // Fetch all attendance for the month (optimized)
    $attendances = Attendance::whereBetween('date', [$start, $end])->get();

    // Prepare attendance map (employee_id → day → status)
    $attendanceMap = [];

    foreach ($attendances as $a) {
        $day = Carbon::parse($a->date)->day;  // returns integer day (1–31)
        $attendanceMap[$a->employee_id][$day] = (int) $a->status; // force integer
    }

    return view('attendance.summary', compact('employees', 'month', 'daysInMonth', 'attendanceMap'));
}

}
