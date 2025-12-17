<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Attendance,Shift};
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

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
//  public function markAttendance(Request $request)
// {
//     $request->validate([
//         'date' => 'required|date',
//         'records' => 'required|array',
//         'records.*.employee_id' => 'required|exists:employees,id',
//         'records.*.status' => 'required|in:present,half_day,leave,pending',
//         'records.*.clock_in' => 'nullable|date_format:H:i',
//         'records.*.clock_out' => 'nullable|date_format:H:i',
//     ]);

//     $date = $request->date;
//     $markedBy = auth()->id();

//     $responseData = [];

//     foreach ($request->records as $record) {
//         $attendance = Attendance::updateOrCreate(
//             [
//                 'employee_id' => $record['employee_id'],
//                 'date' => $date,
//             ],
//             [
//                 'status' => $record['status'],
//                 'clock_in' => $record['clock_in'] ?? null,
//                 'clock_out' => $record['clock_out'] ?? null,
//                 'marked_by' => $markedBy
//             ]
//         );

//         $responseData[] = $attendance;
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'Attendance marked successfully!',
//         'data' => $responseData
//     ]);
// }

// Working with status true
// public function markAttendance(Request $request)
// {
//     // Use provided datetime or current datetime
//     $dateTime = $request->date ?? now()->format('Y-m-d H:i:s');

//     // Validate the single record
//     $validator = Validator::make($request->all(), [
//         'employee_id' => 'required|exists:employees,id',
//         'status'      => 'required|boolean',
//         'clock_in'    => 'nullable|date_format:H:i',
//         'clock_out'   => 'nullable|date_format:H:i',
//         'date'        => 'nullable',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Validation error',
//             'errors'  => $validator->errors()
//         ], 422);
//     }

//     $markedBy = auth()->id();

//     // Convert "true"/"false" string to boolean 1/0 if needed
//     $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

//     // Save/update the attendance record
//     $attendance = Attendance::updateOrCreate(
//         [
//             'employee_id' => $request->employee_id,
//             'date'        => $dateTime,
//         ],
//         [
//             'employee_id' => $request->employee_id,
//             'date'        => $dateTime,
//             'status'      => $status,
//             'clock_in'    => $request->clock_in ?? null,
//             'clock_out'   => $request->clock_out ?? null,
//             'marked_by'   => $markedBy
//         ]
//     );

//     // ✅ Update employee's attendance_status and assign to variable
//     $updateEmployee = Employee::where('id', $request->employee_id)
//         ->update(['attendance_status' => $status]);

//     return response()->json([
//         'status'         => true,
//         'message'        => 'Attendance marked successfully!',
//         'date_used'      => $dateTime,
//         'data'           => $attendance,
//         // 'updateEmployee' => $updateEmployee // returns 1 if updated successfully
//     ]);
// }

// Create date time logics
// public function markAttendance(Request $request)
// {
//     // 1️⃣ Parse scan datetime
//     $scanDateTime = $request->date
//         ? Carbon::parse($request->date)
//         : now();

//     // 2️⃣ Validate input
//     $validator = Validator::make($request->all(), [
//         'employee_id' => 'required|exists:employees,id',
//         'date'        => 'nullable|date',
//         'status'      => 'required|boolean', // frontend scan true/false
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Validation error',
//             'errors'  => $validator->errors()
//         ], 422);
//     }

//     // 3️⃣ Fetch employee
//     $employee = Employee::find($request->employee_id);
//     $createdAt = Carbon::parse($employee->created_at);

//     // 4️⃣ Calculate hours difference
//     $diffHours = $createdAt->diffInHours($scanDateTime);

//     // 5️⃣ Determine attendance status (1 = Present, 2 = Half Day, 0 = Leave)
//     if ($diffHours <= 2) {
//         $attendanceStatus = 1; // Present
//     } elseif ($diffHours <= 3) {
//         $attendanceStatus = 2; // Half Day
//     } else {
//         $attendanceStatus = 0; // Leave
//     }

//     // 6️⃣ Fetch or create attendance record for this day
//     $attendance = Attendance::firstOrNew([
//         'employee_id' => $employee->id,
//         'date'        => $scanDateTime->format('Y-m-d'),
//     ]);

//     // 7️⃣ Set clock_in / clock_out automatically
//     if (!$attendance->exists || !$attendance->clock_in) {
//         $attendance->clock_in = $scanDateTime->format('H:i');
//         $attendance->clock_out = null;
//     } else {
//         $attendance->clock_out = $scanDateTime->format('H:i');
//     }

//     // 8️⃣ Save attendance
//     $attendance->status = $attendanceStatus;
//     $attendance->marked_by = auth()->id();
//     $attendance->scan_status = $request->status ? 1 : 0;
//     $attendance->save();

//     // 9️⃣ Update employee current attendance status
//     // $employee->update(['attendance_status' => $attendanceStatus]);
// Employee::where('id', $employee->id)
//         ->update(['attendance_status' => $attendanceStatus]);
//     // 10️⃣ Return response
//     return response()->json([
//         'status'                  => true,
//         'message'                 => 'Attendance marked successfully',
//         'scan_status_received'    => $request->status,
//         'difference_hours'        => $diffHours,
//         'final_attendance_status' => $attendanceStatus,
//         'data'                    => $attendance
//     ]);
// }


// Working for day Shift
// public function markAttendance(Request $request)
// {
//     $scanDateTime = $request->date
//         ? Carbon::parse($request->date)
//         : now();

//     $validator = Validator::make($request->all(), [
//         'employee_id' => 'required|exists:employees,id',
//         'date'        => 'nullable|date',
//         'status'      => 'required|boolean',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Validation error',
//             'errors'  => $validator->errors()
//         ], 422);
//     }

//     $employee = Employee::find($request->employee_id);
//     $shift = Shift::find($employee->shift_id);

//     if (!$shift) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Shift not assigned to this employee'
//         ], 400);
//     }

//     // Build shift times
//     $shiftStart = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_in_time);
//     $shiftEnd   = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_out_time);

//     // Night shift (end time next day)
//     if ($shiftEnd->lt($shiftStart)) {
//         $shiftEnd->addDay();
//     }

//     // -----------------------------
//     // MAIN FIX: if scanned BEFORE shift start
//     // -----------------------------
//     if ($scanDateTime->lt($shiftStart)) {
//         $attendanceStatus = 0;  // Leave
//         $diffHours = 0;
//     } else {
//         // Calculate late hours
//         $diffHours = $shiftStart->diffInHours($scanDateTime);

//         if ($diffHours <= 2) {
//             $attendanceStatus = 1; // Present
//         } elseif ($diffHours <= 3) {
//             $attendanceStatus = 2; // Half Day
//         } else {
//             $attendanceStatus = 0; // Leave
//         }
//     }

//     // Save attendance
//     $attendance = Attendance::firstOrNew([
//         'employee_id' => $employee->id,
//         'date'        => $scanDateTime->format('Y-m-d'),
//     ]);

//     if (!$attendance->exists || !$attendance->clock_in) {
//         $attendance->clock_in = $scanDateTime->format('H:i');
//         $attendance->clock_out = null;
//     } else {
//         $attendance->clock_out = $scanDateTime->format('H:i');
//     }

//     $attendance->status       = $attendanceStatus;
//     $attendance->scan_status  = $request->status ? 1 : 0;
//     $attendance->marked_by    = auth()->id();
//     $attendance->save();

//     Employee::where('id', $employee->id)
//         ->update(['attendance_status' => $attendanceStatus]);

//     return response()->json([
//         'status'                  => true,
//         'message'                 => 'Attendance marked successfully',
//         'difference_hours'        => $diffHours,
//         'final_attendance_status' => $attendanceStatus,
//         'data'                    => $attendance
//     ]);
// }

// dec11
// public function markAttendance(Request $request)
// {
//     $scanDateTime = $request->date
//         ? Carbon::parse($request->date)
//         : now();

//     $validator = Validator::make($request->all(), [
//         'employee_id' => 'required|exists:employees,id',
//         'date'        => 'nullable|date',
//         'status'      => 'required|boolean',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Validation error',
//             'errors'  => $validator->errors()
//         ], 422);
//     }

//     $employee = Employee::find($request->employee_id);
//     $shift = Shift::find($employee->shift_id);

//     if (!$shift) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Shift not assigned to this employee'
//         ], 400);
//     }

//     // -------------------------
//     // Shift times
//     // -------------------------
//     $shiftStart = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_in_time);
//     $shiftEnd   = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_out_time);

//     $isNightShift = false;

//     // Detect night shift
//     if ($shiftEnd->lt($shiftStart)) {
//         $isNightShift = true;
//         $shiftEnd->addDay();
//     }

//     // Fix attendance date for night shift
//     if ($isNightShift && $scanDateTime->lt($shiftStart)) {
//         $attendanceDate = $shiftStart->copy()->subDay()->format('Y-m-d');
//         $shiftStart->subDay();
//     } else {
//         $attendanceDate = $scanDateTime->format('Y-m-d');
//     }

//     // ---------------------------------------------------------
//     // MARK CLOCK-IN / CLOCK-OUT
//     // ---------------------------------------------------------
//     $attendance = Attendance::firstOrNew([
//         'employee_id' => $employee->id,
//         'date'        => $attendanceDate,
//     ]);

//     // store clock-in first time
//     if (!$attendance->exists || !$attendance->clock_in) {
//         $attendance->clock_in = $scanDateTime->format('H:i:s');
//         $attendance->clock_out = null;
//     } else {
//         $attendance->clock_out = $scanDateTime->format('H:i:s');
//     }

//     // ---------------------------------------------------------
//     // If only clock-in happened → calculate based on late
//     // ---------------------------------------------------------
//     $clockIn = Carbon::parse($attendanceDate . ' ' . $attendance->clock_in);
//     $lateMinutes = $clockIn->diffInMinutes($shiftStart);
//     $lateHours = $lateMinutes / 60;

//     // ---------------------------------------------------------
//     // If clock-out exists → calculate total worked hours
//     // ---------------------------------------------------------
//     if ($attendance->clock_out) {
//         $clockOut = Carbon::parse($attendanceDate . ' ' . $attendance->clock_out);
//         $workedHours = $clockIn->diffInHours($clockOut, false);
//     } else {
//         $workedHours = 0;
//     }

//     // ---------------------------------------------------------
//     // FINAL ATTENDANCE RULES
//     // ---------------------------------------------------------

//     // CASE 1: Only clock-IN (late logic)
//     if (!$attendance->clock_out) {

//         if ($lateHours <= 1) {
//             $attendanceStatus = 1; // Present
//         } elseif ($lateHours <= 4) {
//             $attendanceStatus = 2; // Half Day
//         } else {
//             $attendanceStatus = 0; // Leave
//         }

//     } else {
//         // CASE 2: FULL-IN + OUT (work hours logic)

//         if ($workedHours >= 6) {
//             $attendanceStatus = 1; // Present
//         } elseif ($workedHours >= 4) {
//             $attendanceStatus = 2; // Half Day
//         } else {
//             $attendanceStatus = 0; // Leave
//         }
//     }

//     // ---------------------------------------------------------
//     // Save data
//     // ---------------------------------------------------------
//     $attendance->status      = $attendanceStatus;
//     $attendance->scan_status = $request->status ? 1 : 0;
//     $attendance->marked_by   = auth()->id();
//     $attendance->save();

//     Employee::where('id', $employee->id)
//         ->update(['attendance_status' => $attendanceStatus]);

//     return response()->json([
//         'status'                  => true,
//         'message'                 => 'Attendance marked successfully',
//         'attendance_date_used'    => $attendanceDate,
//         'late_hours'              => $lateHours,
//         'worked_hours'            => $workedHours,
//         'final_attendance_status' => $attendanceStatus,
//         'data'                    => $attendance
//     ]);
// }

// public function markAttendance(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|integer|exists:employees,id',
//         'machine_id'  => 'required|integer',
//         'date'        => 'required|date',
//         'scan_status' => 'required|boolean',
//     ]);

//     $employeeId = $request->employee_id;
//     $machineId  = $request->machine_id;  // frontend sends this
//     $date       = $request->date;
//     $scanStatus = $request->scan_status;

//     $scanTime = \Carbon\Carbon::now();
//     $time = $scanTime->format('H:i:s');

//     // CREATE or GET Attendance
//     $attendance = Attendance::firstOrCreate(
//         ['employee_id' => $employeeId, 'date' => $date],
//         [
//             'machine_id' => $machineId,
//             'slot1' => null,
//             'slot2' => null,
//             'slot3' => null,
//             'status' => 0
//         ]
//     );

//     // Always update machine
//     $attendance->machine_id = $machineId;

//     // Detect shift (Morning or Night)
//     $shift = null;

//     if ($time >= '08:00:00' && $time <= '20:00:00') {
//         $shift = 'morning';
//     } elseif ($time >= '20:00:00' || $time <= '08:00:00') {
//         $shift = 'night';
//     }

//     // Determine slot
//     $slot = null;

//     if ($shift === 'morning') {

//         if ($time >= '08:00:00' && $time <= '10:00:00') $slot = 'slot1';
//         elseif ($time >= '14:00:00' && $time <= '16:00:00') $slot = 'slot2';
//         elseif ($time >= '18:00:00' && $time <= '20:00:00') $slot = 'slot3';

//     } elseif ($shift === 'night') {

//         if ($time >= '20:00:00' && $time <= '22:00:00') $slot = 'slot1';
//         elseif ($time >= '02:00:00' && $time <= '04:00:00') $slot = 'slot2';
//         elseif ($time >= '06:00:00' && $time <= '08:00:00') $slot = 'slot3';
//     }

//     if (!$slot) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Scan time not matching any slot'
//         ], 422);
//     }

//     // Save slot timestamp if scan is TRUE
//     if ($scanStatus) {
//         $attendance->$slot = $scanTime;
//         $attendance->scan_status = 1;
//     }

//     // Count completed slots
//     $completed = collect([
//         $attendance->slot1,
//         $attendance->slot2,
//         $attendance->slot3,
//     ])->filter()->count();

//     // Apply final status
//     if ($completed == 3)       $attendance->status = 1; // present
//     elseif ($completed == 2)   $attendance->status = 2; // half
//     else                       $attendance->status = 0; // leave

//     $attendance->save();

//     return response()->json([
//         'status' => true,
//         'message' => 'Attendance marked',
//         'shift' => $shift,
//         'slot' => $slot,
//         'completed_slots' => $completed,
//         'attendance_status' => $attendance->status,
//         'record' => $attendance
//     ]);
// }
// 2
// public function markAttendance(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|integer|exists:employees,id',
//         'machine_id'  => 'required|integer',
//         'date'        => 'required|date',
//         'scan_status' => 'required|boolean',
//     ]);

//     $employeeId = $request->employee_id;
//     $machineId  = $request->machine_id;
//     $scanTime   = \Carbon\Carbon::parse($request->date); // use sent date/time
//     $scanStatus = (bool) $request->scan_status;

//     $time = $scanTime->format('H:i:s');

//     // Create or get attendance record
//     $attendance = Attendance::firstOrCreate(
//         ['employee_id' => $employeeId, 'date' => $scanTime->toDateString()],
//         [
//             'machine_id' => $machineId,
//             'slot1' => null,
//             'slot2' => null,
//             'slot3' => null,
//             'status' => 0,
//             'scan_status' => 0
//         ]
//     );

//     // Always update machine
//     $attendance->machine_id = $machineId;

//     // Define slots
//     $morningSlots = [
//         'slot1' => ['08:00:00', '10:00:00'],
//         'slot2' => ['14:00:00', '16:00:00'],
//         'slot3' => ['18:00:00', '20:00:00'],
//     ];

//     $nightSlots = [
//         'slot1' => ['20:00:00', '22:00:00'],
//         'slot2' => ['02:00:00', '04:00:00'],
//         'slot3' => ['06:00:00', '08:00:00'],
//     ];

//     $slot = null;

//     // Check morning slots
//     foreach ($morningSlots as $key => [$start, $end]) {
//         if ($time >= $start && $time <= $end) {
//             $slot = $key;
//             break;
//         }
//     }

//     // Check night slots if morning slot not matched
//     if (!$slot) {
//         foreach ($nightSlots as $key => [$start, $end]) {
//             if ($start < $end) { // same day
//                 if ($time >= $start && $time <= $end) {
//                     $slot = $key;
//                     break;
//                 }
//             } else { // crosses midnight
//                 if ($time >= $start || $time <= $end) {
//                     $slot = $key;
//                     break;
//                 }
//             }
//         }
//     }

//     if (!$slot) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Scan time not matching any slot'
//         ], 422);
//     }

//     // Save slot timestamp if scan_status = true
//     if ($scanStatus) {
//         $attendance->$slot = $scanTime;
//         $attendance->scan_status = 1;
//     }

//     // Count completed slots
//     $completed = collect([$attendance->slot1, $attendance->slot2, $attendance->slot3])->filter()->count();

//     // Apply final status
//     if ($completed == 3) $attendance->status = 1; // Present
//     elseif ($completed == 2) $attendance->status = 2; // Half-day
//     else $attendance->status = 0; // Leave
//     $attendance->marked_by   = auth()->id();
//     $attendance->save();

//     return response()->json([
//         'status' => true,
//         'message' => 'Attendance marked',
//         'slot' => $slot,
//         'completed_slots' => $completed,
//         'attendance_status' => $attendance->status,
//         'record' => $attendance
//     ]);
// }

public function markAttendance(Request $request)
{
    // -------------------------
    // VALIDATIONS WITH ERROR RESPONSE
    // -------------------------
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required|integer|exists:employees,id',
        'machine_id'  => 'required|integer',
        'date'        => 'required|date',
        'scan_status' => 'required|boolean',
    ], [
        'employee_id.required' => 'Employee ID is required.',
        'machine_id.required'  => 'Machine ID is required.',
        'date.required'        => 'Date is required.',
        'scan_status.required' => 'Scan status is required.',
    ]);

    // Get full validation errors
    $errors = $validator->errors()->getMessages();

    // Only return errors for missing keys  
    foreach ($errors as $field => $messages) {
        if ($request->has($field)) {
            unset($errors[$field]);
        }
    }

    // If any missing key errors exist → return
    if (!empty($errors)) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation errors',
            'errors'  => $errors,
        ], 422);
    }

    // -------------------------
    // Extract validated data
    // -------------------------
    $employeeId = $request->employee_id;
    $machineId  = $request->machine_id;
    $scanTime   = \Carbon\Carbon::parse($request->date);
    $scanStatus = (bool) $request->scan_status;
    $time       = $scanTime->format('H:i:s');

    // -------------------------
    // Determine Shift Type
    // -------------------------
    $isNightShift = ($time >= '20:00:00' || $time <= '08:00:00');
    $shiftType    = $isNightShift ? 'night' : 'morning';

    // For night shift scan between midnight–8am → previous day's attendance
    if ($isNightShift && $time <= '08:00:00') {
        $attendanceDate = $scanTime->copy()->subDay()->toDateString();
    } else {
        $attendanceDate = $scanTime->toDateString();
    }

    // -------------------------
    // Find/Create attendance record
    // -------------------------
    $attendance = Attendance::firstOrNew([
        'employee_id' => $employeeId,
        'date'        => $attendanceDate,
        'shift_type'  => $shiftType,
    ]);

    $attendance->machine_id = $machineId;
    $attendance->marked_by  = auth()->id();

    // -------------------------
    // SLOT Definitions
    // -------------------------
    $morningSlots = [
        'slot1' => ['08:00:00', '10:00:00'],
        'slot2' => ['14:00:00', '16:00:00'],
        'slot3' => ['18:00:00', '20:00:00'],
    ];

    $nightSlots = [
        'slot1' => ['20:00:00', '22:00:00'],
        'slot2' => ['22:00:01', '02:00:00'], // Crosses midnight
        'slot3' => ['02:00:01', '08:00:00'], // Crosses midnight
    ];

    $slotsToCheck = ($shiftType === 'night') ? $nightSlots : $morningSlots;

    $slot = null;

    foreach ($slotsToCheck as $key => [$start, $end]) {

        // Night shift cross-midnight slots
        if ($shiftType === 'night' && ($key == 'slot2' || $key == 'slot3')) {
            if ($time >= $start || $time <= $end) {
                $slot = $key;
                break;
            }
        } 
        // Normal slots
        else {
            if ($time >= $start && $time <= $end) {
                $slot = $key;
                break;
            }
        }
    }

    if (!$slot) {
        return response()->json([
            'status'  => false,
            'message' => 'Scan time not matching any slot'
        ], 422);
    }

    // -------------------------
    // MARK SLOT
    // -------------------------
    if ($scanStatus) {
        $attendance->$slot = $scanTime;
        $attendance->scan_status = 1;
    }

    // -------------------------
    // Count completed slots
    // -------------------------
    $completed = collect([
        $attendance->slot1,
        $attendance->slot2,
        $attendance->slot3
    ])->filter()->count();

    // Set attendance status automatically
    if ($completed == 3) {
        $attendance->status = 1; // Present
    } elseif ($completed == 2) {
        $attendance->status = 2; // Half Day
    } else {
        $attendance->status = 0; // Absent
    }

    $attendance->save();

    // -------------------------
    // RESPONSE
    // -------------------------
    return response()->json([
        'status'            => true,
        'message'           => 'Attendance marked successfully',
        'shift_type'        => $shiftType,
        'slot'              => $slot,
        'completed_slots'   => $completed,
        'attendance_status' => $attendance->status,
        'record'            => $attendance
    ]);
}










// Working for shift 8 dec
// public function markAttendance(Request $request)
// {
//     $scanDateTime = $request->date
//         ? Carbon::parse($request->date)
//         : now();

//     $validator = Validator::make($request->all(), [
//         'employee_id' => 'required|exists:employees,id',
//         'date'        => 'nullable|date',
//         'status'      => 'required|boolean',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Validation error',
//             'errors'  => $validator->errors()
//         ], 422);
//     }

//     $employee = Employee::find($request->employee_id);
//     $shift = Shift::find($employee->shift_id);

//     if (!$shift) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Shift not assigned to this employee'
//         ], 400);
//     }

//     // -------------------------
//     // Build shift times
//     // -------------------------
//     $shiftStart = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_in_time);
//     $shiftEnd   = Carbon::parse($scanDateTime->format('Y-m-d') . ' ' . $shift->clock_out_time);

//     $isNightShift = false;

//     // Detect night shift
//     if ($shiftEnd->lt($shiftStart)) {
//         $isNightShift = true;
//         $shiftEnd->addDay();                // night shift ends next day
//     }

//     // -------------------------
//     // Correct ATTENDANCE DATE on night shift
//     // -------------------------
//     if ($isNightShift && $scanDateTime->lt($shiftStart)) {
//         // Example:
//         // Shift = 20:00 (Dec 5)
//         // Scan = 05:00 (Dec 6)
//         // Real attendance date = Dec 5
//         $attendanceDate = $shiftStart->copy()->subDay()->format('Y-m-d');
//         $shiftStart->subDay();  // shift start moves to previous day
//     } else {
//         $attendanceDate = $scanDateTime->format('Y-m-d');
//     }

//     // -------------------------
//     // Determine late / present / half / leave
//     // -------------------------

//     if ($scanDateTime->lt($shiftStart)) {
//         // too early (e.g., 5 AM for morning shift)
//         $attendanceStatus = 0; // Leave
//         $diffHours = 0;
//     } else {
//         $diffHours = $shiftStart->diffInHours($scanDateTime);

//         if ($diffHours <= 2) {
//             $attendanceStatus = 1; // Present
//         } elseif ($diffHours <= 3) {
//             $attendanceStatus = 2; // Half Day
//         } else {
//             $attendanceStatus = 0; // Leave
//         }
//     }

//     // -------------------------
//     // Save attendance with correct DATE
//     // -------------------------
//     $attendance = Attendance::firstOrNew([
//         'employee_id' => $employee->id,
//         'date'        => $attendanceDate,
//     ]);

//     if (!$attendance->exists || !$attendance->clock_in) {
//         $attendance->clock_in = $scanDateTime->format('H:i');
//         $attendance->clock_out = null;
//     } else {
//         $attendance->clock_out = $scanDateTime->format('H:i');
//     }

//     $attendance->status       = $attendanceStatus;
//     $attendance->scan_status  = $request->status ? 1 : 0;
//     $attendance->marked_by    = auth()->id();
//     $attendance->save();

//     Employee::where('id', $employee->id)
//         ->update(['attendance_status' => $attendanceStatus]);

//     return response()->json([
//         'status'                  => true,
//         'message'                 => 'Attendance marked successfully',
//         'attendance_date_used'    => $attendanceDate,
//         'difference_hours'        => $diffHours,
//         'final_attendance_status' => $attendanceStatus,
//         'data'                    => $attendance
//     ]);
// }






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
    // public function attendanceList(Request $request)
    // {
    //     $date = $request->get('date');

    //     $attendance = Attendance::with('employee')
    //         ->when($date, fn($q) => $q->where('date', $date))
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $attendance
    //     ]);
    // }
public function attendanceList(Request $request)
{
    $date = $request->get('date');
    $search = $request->get('search'); // search keyword
    $perPage = (int) $request->get('per_page', 10); // default 10 per page

    $query = Attendance::with('employee')
        ->when($date, fn($q) => $q->whereDate('date', $date)) // filter by date
        ->when($search, function($q, $search) {
            $q->whereHas('employee', function($emp) use ($search) {
                $emp->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        })
        ->orderBy('date', 'asc');

    $attendances = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'data' => $attendances->items(),
        'pagination' => [
            'total'        => $attendances->total(),
            'per_page'     => $attendances->perPage(),
            'current_page' => $attendances->currentPage(),
            'last_page'    => $attendances->lastPage(),
            'next_page_url'=> $attendances->nextPageUrl(),
            'prev_page_url'=> $attendances->previousPageUrl(),
        ],
    ]);
}

public function attendanceByEmployee(Request $request, $employee_id)
{
    // dd($employee_id);
    // print_r("employee_id: " . $employee_id);die;
    $date = $request->get('date');
    $search = $request->get('search');
    $perPage = (int) $request->get('per_page', 10);

    $query = Attendance::with('employee')
        ->where('employee_id', $employee_id) // 🔥 REQUIRED FILTER
        ->when($date, fn($q) => $q->whereDate('date', $date))
        ->when($search, function ($q, $search) {
            $q->whereHas('employee', function ($emp) use ($search) {
                $emp->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        })
        ->orderBy('date', 'desc');

    $attendances = $query->paginate($perPage);

    return response()->json([
        'status' => true,
        'data' => $attendances->items(),
        'pagination' => [
            'total'        => $attendances->total(),
            'per_page'     => $attendances->perPage(),
            'current_page' => $attendances->currentPage(),
            'last_page'    => $attendances->lastPage(),
            'next_page_url'=> $attendances->nextPageUrl(),
            'prev_page_url'=> $attendances->previousPageUrl(),
        ],
    ]);
}

public function monthlyAttendance(Request $request, $employee_id)
{
    $timezone = 'Asia/Kolkata';
    $now      = Carbon::now($timezone);
    $today    = $now->toDateString();

    /*
    |--------------------------------------------------------------------------
    | CASE 1️⃣ : SINGLE DATE (Highest Priority)
    |--------------------------------------------------------------------------
    */
    if ($request->has('date')) {
        try {
            $date = Carbon::parse($request->date, $timezone)->toDateString();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Invalid date format. Use YYYY-MM-DD'
            ], 400);
        }

        // ❌ Do not allow future date
        if ($date > $today) {
            return response()->json([
                'status' => false,
                'error'  => 'Future date not allowed'
            ], 400);
        }

        $attendance = Attendance::where('employee_id', $employee_id)
            ->whereDate('date', $date)
            ->first();

        return response()->json([
            'status' => true,
            'range'  => 'single_date',
            'data'   => [[
                'date'      => $date,
                'status'    => $attendance->status ?? null,
                'clock_in'  => $attendance->clock_in ?? null,
                'clock_out' => $attendance->clock_out ?? null,
            ]],
            'summary' => [
                'present'  => $attendance?->status == 1 ? 1 : 0,
                'leave'    => $attendance?->status == 0 ? 1 : 0,
                'half_day' => $attendance?->status == 2 ? 1 : 0,
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CASE 2️⃣ : LAST 3 MONTHS
    |--------------------------------------------------------------------------
    */
    if ($request->boolean('last_3_months')) {

        $startDate = $now->copy()->startOfMonth()->subMonths(2);
        $endDate   = $now->copy()->toDateString(); // ⬅️ today only

    }
    /*
    |--------------------------------------------------------------------------
    | CASE 3️⃣ : MONTH + YEAR
    |--------------------------------------------------------------------------
    */
    else {

        $year  = (int) $request->get('year', $now->year);
        $month = (int) $request->get('month', $now->month);

        $requestedMonth = Carbon::createFromDate($year, $month, 1, $timezone);

        // ❌ Future month not allowed
        if ($requestedMonth->gt($now)) {
            return response()->json([
                'status' => false,
                'error'  => 'Future month data not allowed'
            ], 400);
        }

        $startDate = $requestedMonth->copy()->startOfMonth();

        // ✅ If current month → till today
        if ($requestedMonth->isSameMonth($now)) {
            $endDate = $now->toDateString();
        } else {
            $endDate = $requestedMonth->copy()->endOfMonth();
        }

        // ❌ Older than last 3 months
        if ($startDate->lt($now->copy()->startOfMonth()->subMonths(3))) {
            return response()->json([
                'status' => false,
                'error'  => 'You can request only current and last 3 months data'
            ], 400);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch attendance
    |--------------------------------------------------------------------------
    */
    $attendances = Attendance::where('employee_id', $employee_id)
        ->whereBetween('date', [$startDate, $endDate])
        ->orderBy('date')
        ->orderBy('shift_type')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Fill missing dates
    |--------------------------------------------------------------------------
    */
    // $data = [];
    // for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

    //     $record = $attendances->firstWhere('date', $date->toDateString());

    //     $data[] = [
    //         'date'      => $date->toDateString(),
    //         'status'    => $record->status ?? null,
    //         'clock_in'  => $record->clock_in ?? null,
    //         'clock_out' => $record->clock_out ?? null,
    //     ];
    // }
$data = [];

for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

    $dailyRecords = $attendances->filter(function ($att) use ($date) {
        return Carbon::parse($att->date)->toDateString() === $date->toDateString();
    });

    $shifts = [];

    if ($dailyRecords->isNotEmpty()) {

        foreach ($dailyRecords as $record) {
            $shifts[] = [
                'shift_type' => $record->shift_type,
                'slots' => [
                    $record->slot1,
                    $record->slot2,
                    $record->slot3,
                ],
                'status'     => $record->status,
                'clock_in'   => $record->clock_in,
                'clock_out'  => $record->clock_out,
                'machine_id' => $record->machine_id,
            ];
        }

    } else {
        // 👇 NO SHIFT FOUND → RETURN NULL VALUES
        $shifts[] = [
            'shift_type' => null,
            'slots'      => [null, null, null],
            'status'     => null,
            'clock_in'   => null,
            'clock_out'  => null,
            'machine_id' => null,
        ];
    }

    $data[] = [
        'date'   => $date->toDateString(),
        'shifts' => $shifts,
    ];
}


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */
    $summary = [
        'present'  => $attendances->where('status', 1)->count(),
        'leave'    => $attendances->where('status', 0)->count(),
        'half_day' => $attendances->where('status', 2)->count(),
    ];

    return response()->json([
        'status' => true,
        'range'  => $request->boolean('last_3_months') ? 'last_3_months' : 'monthly',
        'from'   => $startDate->toDateString(),
        'to'     => $endDate,
        'data'   => $data,
        'summary'=> $summary,
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
// public function employeeAttendancePdf(Request $request)
// {
//     $timezone = 'Asia/Kolkata';

//     $request->validate([
//         'start_date' => 'required|date',
//         'end_date'   => 'required|date|after_or_equal:start_date',
//     ]);

//     // USE DATE ONLY
//     $startDate = Carbon::parse($request->start_date)->toDateString();
//     $endDate   = Carbon::parse($request->end_date)->toDateString();

//     // CORRECT TOTAL DAYS (no decimals)
//     $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

//     // GET ALL EMPLOYEES
//     $employees = Employee::all();

//     if ($employees->count() == 0) {
//         return response()->json([
//             'status' => false,
//             'message' => 'No employees found'
//         ], 404);
//     }

//     $reportData = [];

//     foreach ($employees as $emp) {

//         $presentCount = Attendance::where('employee_id', $emp->id)
//             ->whereDate('date', '>=', $startDate)
//             ->whereDate('date', '<=', $endDate)
//             ->where('status', 1)
//             ->count();

//         $leaveCount = Attendance::where('employee_id', $emp->id)
//             ->whereDate('date', '>=', $startDate)
//             ->whereDate('date', '<=', $endDate)
//             ->where('status', 0)
//             ->count();

//         $halfDayCount = Attendance::where('employee_id', $emp->id)
//             ->whereDate('date', '>=', $startDate)
//             ->whereDate('date', '<=', $endDate)
//             ->where('status', 2)
//             ->count();

//         $reportData[] = [
//             'employee'   => $emp,
//             'present'    => $presentCount,
//             'leave'      => $leaveCount,
//             'half_day'   => $halfDayCount,
//             'total_days' => $totalDays,
//         ];
//     }

//     $generated_at = now($timezone)->format('d-m-Y h:i A');

//     $pdf = PDF::loadView('reports.employee_attendance', [
//         'reportData'   => $reportData,
//         'startDate'    => Carbon::parse($startDate)->format('d M Y'),
//         'endDate'      => Carbon::parse($endDate)->format('d M Y'),
//         'generated_at' => $generated_at,
//     ])->setPaper('A4', 'portrait');

//     return $pdf->download("attendance-report-{$startDate}-to-{$endDate}.pdf");
// }
public function employeeAttendancePdf(Request $request)
{
    $timezone = 'Asia/Kolkata';

    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = Carbon::parse($request->start_date)->toDateString();
    $endDate   = Carbon::parse($request->end_date)->toDateString();

    $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

    $employees = Employee::all();

    if ($employees->count() == 0) {
        return response()->json([
            'status' => false,
            'message' => 'No employees found'
        ], 404);
    }

    $reportData = [];

    // foreach ($employees as $emp) {

    //     // Attendance
    //     $present = Attendance::where('employee_id', $emp->id)
    //         ->whereBetween('date', [$startDate, $endDate])
    //         ->where('status', 1)
    //         ->count();

    //     $leave = Attendance::where('employee_id', $emp->id)
    //         ->whereBetween('date', [$startDate, $endDate])
    //         ->where('status', 0)
    //         ->count();

    //     $halfDay = Attendance::where('employee_id', $emp->id)
    //         ->whereBetween('date', [$startDate, $endDate])
    //         ->where('status', 2)
    //         ->count();

    //     // ------------ SALARY CALCULATION ------------
    //     if ($emp->salary_type === 'monthly') {
    //         $perDay = $emp->salary_monthly / $totalDays;
    //     } else {
    //         $perDay = $emp->salary_daily;
    //     }

    //     $presentSalary = $present * $perDay;
    //     $halfSalary = $halfDay * ($perDay / 2);

    //     $totalSalary = round($presentSalary + $halfSalary, 2);

    //     $reportData[] = [
    //         'employee'      => $emp,
    //         'total_days'    => $totalDays,
    //         'present'       => $present,
    //         'leave'         => $leave,
    //         'half_day'      => $halfDay,
    //         'per_day_pay'   => round($perDay, 2),
    //         'salary_present'=> round($presentSalary, 2),
    //         'salary_half'   => round($halfSalary, 2),
    //         'total_salary'  => $totalSalary,
    //     ];
    // }
foreach ($employees as $emp) {

    // Attendance
    $present = Attendance::where('employee_id', $emp->id)
        ->whereBetween('date', [$startDate, $endDate])
        ->where('status', 1)
        ->count();

    $leave = Attendance::where('employee_id', $emp->id)
        ->whereBetween('date', [$startDate, $endDate])
        ->where('status', 0)
        ->count();

    $halfDay = Attendance::where('employee_id', $emp->id)
        ->whereBetween('date', [$startDate, $endDate])
        ->where('status', 2)
        ->count();

    // Salary calc
    if ($emp->salary_type === 'monthly') {
        $perDay = $emp->salary_monthly / $totalDays;
    } else {
        $perDay = $emp->salary_daily;
    }

    $presentSalary = $present * $perDay;
    $halfSalary = $halfDay * ($perDay / 2);
    $totalSalary = round($presentSalary + $halfSalary, 2);

    // NEW FIELDS
    $type = $emp->employee_type === 'company' ? 'Company' : 'Contractor';

    $contractorName = $emp->employee_type === 'contractor'
        ? optional($emp->contractor)->name
        : '-';

    $reportData[] = [
        'employee'        => $emp,
        'type'            => $type,
        'contractor_name' => $contractorName,
        'total_days'      => $totalDays,
        'present'         => $present,
        'leave'           => $leave,
        'half_day'        => $halfDay,
        'per_day_pay'     => round($perDay, 2),
        'salary_present'  => round($presentSalary, 2),
        'salary_half'     => round($halfSalary, 2),
        'total_salary'    => $totalSalary,
    ];
}

    $generated_at = now($timezone)->format('d-m-Y h:i A');

    $pdf = PDF::loadView('reports.employee_attendance', [
        'reportData'   => $reportData,
        'startDate'    => Carbon::parse($startDate)->format('d M Y'),
        'endDate'      => Carbon::parse($endDate)->format('d M Y'),
        'generated_at' => $generated_at,
    ])->setPaper('A4', 'landscape');

    return $pdf->download("employee_attendance-{$startDate}-to-{$endDate}.pdf");
}


}
