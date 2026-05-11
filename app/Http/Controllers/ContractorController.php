<?php

namespace App\Http\Controllers;

use App\Models\{Contractor, Attendance};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Contractor::query();

        // search by name, mobile, email, company_name
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        // echo"dd";die;

        // show only non-deleted (SoftDeletes takes care by default)
        $contractors = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $contractorList = Contractor::orderBy('name')->get();
        return view('contractors.index', compact('contractors', 'contractorList'));
    }

    public function create()
    {
        // We'll show the create modal in index; but still return a view if needed.
        return view('contractors.create');
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'mobile' => 'required|string|max:50|unique:contractors,mobile',
    //         'email' => 'nullable|email|max:255',
    //         'govid' => 'nullable|string|max:255',
    //         'dob' => 'nullable|date',
    //         'gender' => 'nullable|in:male,female,other',
    //         'address' => 'nullable|string',
    //         'company_name' => 'nullable|string|max:255',
    //         'self_photo' => 'nullable|image|max:2048',
    //     ]);

    //     if ($request->hasFile('self_photo')) {
    //         $path = $request->file('self_photo')->store('contractors', 'public');
    //         $data['self_photo'] = $path;
    //     }

    //     // uuid & created_by are handled in model boot
    //     $contractor = Contractor::create($data);

    //     return redirect()->route('contractors.index')
    //         ->with('success', 'Contractor created successfully.');
    // }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50|unique:contractors,mobile',
            'email' => 'nullable|email|max:255',
            'govid' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'self_photo' => 'nullable|image|max:2048',
        ]);

        // ✅ Create folder if missing
        // $photoPath = public_path('contractors_image/photos');
        // if (!file_exists($photoPath)) {
        //     mkdir($photoPath, 0777, true);
        // }

        // // ✅ Save photo directly to public/contractors/photos
        // if ($request->hasFile('self_photo')) {
        //     $photo = $request->file('self_photo');
        //     $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        //     $photo->move($photoPath, $photoName);
        //     $data['self_photo'] = 'contractors_image/photos/' . $photoName;
        // }
        // ------------------------------------------------------------
        // ✅ Save image using same clean mechanism as Machines module
        // ------------------------------------------------------------
        if ($request->hasFile('self_photo')) {

            $photoPath = public_path('contractors_image/photos');

            if (!file_exists($photoPath)) {
                mkdir($photoPath, 0777, true);
            }

            $photo = $request->file('self_photo');
            $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move($photoPath, $photoName);

            $data['self_photo'] = 'contractors_image/photos/' . $photoName;
        }

        // ✅ Assign unique employee code range
        $last = Contractor::orderBy('id', 'desc')->first();
        $lastEnd = $last ? $last->code_end : 999;
        $data['code_start'] = $lastEnd + 1;
        $data['code_end'] = $data['code_start'] + 999; // range of 1000 codes

        $contractor = Contractor::create($data);

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor created successfully with code range '
                . $contractor->code_start . ' - ' . $contractor->code_end);
    }


    public function show(Contractor $contractor)
    {
        return view('contractors.show', compact('contractor'));
    }

    public function edit(Contractor $contractor)
    {
        return view('contractors.edit', compact('contractor'));
    }

    // public function update(Request $request, Contractor $contractor)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'mobile' => 'required|string|max:50|unique:contractors,mobile,' . $contractor->id,
    //         'email' => 'nullable|email|max:255',
    //         'govid' => 'nullable|string|max:255',
    //         'dob' => 'nullable|date',
    //         'gender' => 'nullable|in:male,female,other',
    //         'address' => 'nullable|string',
    //         'company_name' => 'nullable|string|max:255',
    //         'self_photo' => 'nullable|image|max:2048',
    //     ]);

    //     if ($request->hasFile('self_photo')) {
    //         // delete old if exists
    //         if ($contractor->self_photo && Storage::disk('public')->exists($contractor->self_photo)) {
    //             Storage::disk('public')->delete($contractor->self_photo);
    //         }
    //         $path = $request->file('self_photo')->store('contractors', 'public');
    //         $data['self_photo'] = $path;
    //     }

    //     $contractor->update($data);

    //     return redirect()->route('contractors.index')
    //         ->with('success', 'Contractor updated successfully.');
    // }
    public function update(Request $request, Contractor $contractor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:50|unique:contractors,mobile,' . $contractor->id,
            'email' => 'nullable|email|max:255',
            'govid' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'self_photo' => 'nullable|image|max:2048',
        ]);

        // ✅ Ensure folder exists
        $photoPath = public_path('contractors_image/photos');
        if (!file_exists($photoPath)) {
            mkdir($photoPath, 0777, true);
        }

        // ✅ Replace old photo if new one uploaded
        if ($request->hasFile('self_photo')) {
            // Delete old photo if it exists
            if ($contractor->self_photo && file_exists(public_path($contractor->self_photo))) {
                unlink(public_path($contractor->self_photo));
            }

            $photo = $request->file('self_photo');
            $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move($photoPath, $photoName);
            $data['self_photo'] = 'contractors_image/photos/' . $photoName;
        }

        $contractor->update($data);

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor updated successfully.');
    }

    public function destroy(Request $request, Contractor $contractor)
    {
        // Soft delete - deleted_by set in model boot deleting event
        $contractor->delete();

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor deleted successfully.');
    }
    // public function downloadSingleContractorReport($contractor_id)
// {
//     $contractor = Contractor::with(['employees.shift'])->findOrFail($contractor_id);

    //     // Always use CURRENT month & year (no request params in admin)
//     $now = now();
//     $year = $now->year;
//     $month = $now->month;

    //     $generatedAt = $now->format('d M Y, h:i A');
//     $monthName = $now->format('F Y');
//     $currentMonthDays = $now->daysInMonth;

    //     $summary = [];

    //     foreach ($contractor->employees as $emp) {
//         $monthlySalary = $emp->salary ?? 0;
//         $perDayPay = $monthlySalary > 0 ? round($monthlySalary / 30, 2) : 0;

    //         $present = Attendance::where('employee_id', $emp->id)
//             ->whereYear('date', $year)
//             ->whereMonth('date', $month)
//             ->where('status', 1)->count();

    //         $leave = Attendance::where('employee_id', $emp->id)
//             ->whereYear('date', $year)
//             ->whereMonth('date', $month)
//             ->where('status', 0)->count();

    //         $halfDay = Attendance::where('employee_id', $emp->id)
//             ->whereYear('date', $year)
//             ->whereMonth('date', $month)
//             ->where('status', 2)->count();

    //         $salary_present = $present * $perDayPay;
//         $salary_half = ($halfDay * $perDayPay) / 2;
//         $total_salary = round($salary_present + $salary_half, 2);

    //         $summary[] = [
//             'employee'       => $emp,
//             'present'        => $present,
//             'leave'          => $leave,
//             'half_day'       => $halfDay,
//             'per_day_pay'    => $perDayPay,
//             'salary_present' => $salary_present,
//             'salary_half'    => $salary_half,
//             'total_salary'   => $total_salary,
//             'month_days'     => $currentMonthDays,
//         ];
//     }

    //     $pdf = PDF::loadView('reports.contractor_report', [
//         'contractor'   => $contractor,
//         'summary'      => $summary,
//         'generated_at' => $generatedAt,
//         'month_name'   => $monthName,
//         'month_days'   => $currentMonthDays,
//     ])->setPaper('a4', 'portrait');

    //     return $pdf->download("Report-{$contractor->name}-{$monthName}.pdf");
// }
    public function downloadSingleContractorReport(Request $request)
    {
        $request->validate([
            'contractor_id' => 'required|exists:contractors,id',
            'range' => 'required',
            'date' => 'required|date',
        ]);

        $contractor = Contractor::with(['employees.shift'])
            ->findOrFail($request->contractor_id);

        $date = Carbon::parse($request->date);
        $range = $request->range;

        $generatedAt = now()->format('d M Y, h:i A');

        /*
        |--------------------------------------------------------------------------
        | Date Range Filter
        |--------------------------------------------------------------------------
        */

        switch ($range) {

            case 'monthly':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                $reportTitle = 'Monthly Report';
                break;

            case '3months':
                $startDate = $date->copy()->subMonths(3)->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                $reportTitle = 'Last 3 Months Report';
                break;

            case '6months':
                $startDate = $date->copy()->subMonths(6)->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                $reportTitle = 'Last 6 Months Report';
                break;

            case 'daily':
            default:
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $reportTitle = 'Daily Report';
                break;
        }

        $summary = [];

        /*
        |--------------------------------------------------------------------------
        | Employee Loop
        |--------------------------------------------------------------------------
        */

        foreach ($contractor->employees as $emp) {

            /*
            |--------------------------------------------------------------------------
            | Salary Logic (FIXED)
            |--------------------------------------------------------------------------
            */

            $salaryType = strtolower(trim($emp->salary_type ?? 'daily'));

            // ✅ Correct columns used
            $salaryAmount = $salaryType === 'monthly'
                ? (float) ($emp->salary_monthly ?? 0)
                : (float) ($emp->salary_daily ?? 0);

            $daysInMonth = $startDate->daysInMonth;

            $perDayPay = $salaryType === 'monthly'
                ? ($daysInMonth > 0 ? round($salaryAmount / $daysInMonth, 2) : 0)
                : $salaryAmount;

            /*
            |--------------------------------------------------------------------------
            | Attendance Query
            |--------------------------------------------------------------------------
            */

            $attendanceQuery = Attendance::where('employee_id', $emp->id);

            if ($range == 'daily') {

                $attendanceQuery->whereDate('date', $date->format('Y-m-d'));

            } else {

                $attendanceQuery->whereDate('date', '>=', $startDate->format('Y-m-d'))
                    ->whereDate('date', '<=', $endDate->format('Y-m-d'));
            }

            /*
            |--------------------------------------------------------------------------
            | Attendance Counts
            |--------------------------------------------------------------------------
            */

            $present = (clone $attendanceQuery)->where('status', 1)->count();
            $leave = (clone $attendanceQuery)->where('status', 0)->count();
            $halfDay = (clone $attendanceQuery)->where('status', 2)->count();

            /*
            |--------------------------------------------------------------------------
            | Salary Calculation
            |--------------------------------------------------------------------------
            */

            $salary_present = round($present * $perDayPay, 2);
            $salary_half = round($halfDay * ($perDayPay / 2), 2);

            $total_salary = round($salary_present + $salary_half, 2);

            /*
            |--------------------------------------------------------------------------
            | Debug (REMOVE in production)
            |--------------------------------------------------------------------------
            */

            // dd([
            //     'employee_id' => $emp->id,
            //     'salary_type' => $salaryType,
            //     'salary_amount' => $salaryAmount,
            //     'per_day_pay' => $perDayPay,
            // ]);

            $summary[] = [
                'employee' => $emp,
                'salary_type' => $salaryType,

                'present' => $present,
                'leave' => $leave,
                'half_day' => $halfDay,

                'per_day_pay' => $perDayPay,
                'salary_present' => $salary_present,
                'salary_half' => $salary_half,
                'total_salary' => $total_salary,

                'month_days' => $daysInMonth,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Stats
        |--------------------------------------------------------------------------
        */

        $totalEmployees = $contractor->employees->count();

        $totalMale = $contractor->employees->where('gender', 'male')->count();
        $totalFemale = $contractor->employees->where('gender', 'female')->count();

        $todayAttendanceMarked = Attendance::whereIn(
            'employee_id',
            $contractor->employees->pluck('id')
        )
            ->whereDate('date', now()->toDateString())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PDF Generation
        |--------------------------------------------------------------------------
        */

        $pdf = PDF::loadView('reports.contractor_report', [
            'contractor' => $contractor,
            'summary' => $summary,
            'generated_at' => $generatedAt,
            'report_title' => $reportTitle,

            'startDate' => $startDate->format('d-m-Y'),
            'endDate' => $endDate->format('d-m-Y'),

            'totalEmployees' => $totalEmployees,
            'totalMale' => $totalMale,
            'totalFemale' => $totalFemale,
            'todayAttendanceMarked' => $todayAttendanceMarked,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'Contractor-Report-' . $contractor->name . '.pdf'
        );
    }
    public function downloadLast3MonthsReport()
    {
        $months = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'year' => $date->year,
                'month' => $date->month,
                'name' => $date->format('F Y'),
                'days' => $date->daysInMonth,
            ];
        }

        $contractors = Contractor::with(['employees'])->get();

        $allData = [];
        foreach ($contractors as $contractor) {
            foreach ($months as $m) {
                $summary = [];
                foreach ($contractor->employees as $emp) {
                    $monthlySalary = $emp->salary ?? 0;
                    $perDayPay = $monthlySalary > 0 ? round($monthlySalary / 30, 2) : 0;

                    $present = Attendance::where('employee_id', $emp->id)
                        ->whereYear('date', $m['year'])
                        ->whereMonth('date', $m['month'])
                        ->where('status', 1)->count();

                    $leave = Attendance::where('employee_id', $emp->id)
                        ->whereYear('date', $m['year'])
                        ->whereMonth('date', $m['month'])
                        ->where('status', 0)->count();

                    $halfDay = Attendance::where('employee_id', $emp->id)
                        ->whereYear('date', $m['year'])
                        ->whereMonth('date', $m['month'])
                        ->where('status', 2)->count();

                    $total_salary = round(($present * $perDayPay) + ($halfDay * $perDayPay / 2), 2);

                    $summary[] = [
                        'employee' => $emp,
                        'present' => $present,
                        'leave' => $leave,
                        'half_day' => $halfDay,
                        'total_salary' => $total_salary,
                    ];
                }

                $allData[] = [
                    'contractor' => $contractor,
                    'month' => $m,
                    'summary' => $summary,
                ];
            }
        }

        $pdf = PDF::loadView('reports.contractor_3months_report', [
            'allData' => $allData,
            'generated_at' => now()->format('d M Y, h:i A'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('All-Contractors-Last-3-Months-Report.pdf');
    }
}
