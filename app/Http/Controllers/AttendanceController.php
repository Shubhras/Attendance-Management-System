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
public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $search  = $request->get('search');
    $range   = $request->get('range');

    // Default: Today
    $date = $request->get('date', Carbon::today('Asia/Kolkata')->format('Y-m-d'));

    // Base employee query
    $query = Employee::with('shift')->orderBy('name');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('employee_code', 'like', "%$search%");
        });
    }

    $employees = $query->paginate($perPage)->withQueryString();

    // Attendance query
    $attendanceQuery = Attendance::whereIn('employee_id', $employees->pluck('id'));

    // RANGE FILTERS
    switch ($range) {
        case 'daily':
            $attendanceQuery->where('date', $date);
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

        default:
            $attendanceQuery->where('date', $date);
    }

    $attendances = $attendanceQuery->get();

    // Map employee_id → attendance
    $attendanceMap = [];
    foreach ($attendances as $a) {
        $attendanceMap[$a->employee_id] = $a;
    }

    return view('attendance.index', compact('employees', 'attendanceMap', 'date'));
}


    /* ----------------------------
       MASS ATTENDANCE SAVE
    ----------------------------- */
    public function saveBulk(Request $request)
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
            $date = $request->date;

            foreach ($request->records as $row) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $row['employee_id'],
                        'date'        => $date
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
            return back()->with('error', 'Failed to save: '.$e->getMessage());
        }

        return back()->with('success', 'Attendance saved successfully!');
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
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'status'      => 'required|in:present,absent,leave,pending',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date'        => Carbon::today('Asia/Kolkata')->format('Y-m-d'),
            ],
            [
                'status'        => $request->status,
                'clock_in'      => $request->clock_in,
                'clock_out'     => $request->clock_out,
                'scan_response' => $request->scan_response,
                'marked_by'     => auth()->id(),
            ]
        );

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

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'status' => 'required',
        ]);

        $attendance->update([
            'status'    => $request->status,
            'clock_in'  => $request->clock_in,
            'clock_out' => $request->clock_out,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated!');
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
    public function summary(Request $request)
{
    $month = $request->get('month', Carbon::now()->format('Y-m'));
    $start = Carbon::parse($month.'-01')->startOfMonth();
    $end   = Carbon::parse($month.'-01')->endOfMonth();

    $daysInMonth = $start->daysInMonth;

    $employees = Employee::orderBy('name')->get();

    // Fetch all attendance of the month in one query
    $att = Attendance::whereBetween('date', [$start, $end])->get();

    // Map attendance by employee + day
    $attendanceMap = [];
    foreach ($att as $a) {
        $day = Carbon::parse($a->date)->format('d');
        $attendanceMap[$a->employee_id][$day] = $a->status;
    }

    return view('attendance.summary', compact('employees', 'month', 'daysInMonth', 'attendanceMap'));
}

}
