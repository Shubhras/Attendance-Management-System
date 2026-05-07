<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use Illuminate\Http\Request;
// use DB;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
class DashboardController extends Controller
{
    // public function index()
    // {
    //     return view('dashboard/index');
    // }
public function downloadAttendanceTemplate()
{
    $filePath = public_path('templates/employee_attendance.xlsx');

    if (!file_exists($filePath)) {
        abort(404, 'Attendance template not found');
    }

    return response()->download(
        $filePath,
        'attendance_import_template.xlsx'
    );
}
     public function index()
    {
$salarySummary = DB::table('salary_payments')
    ->select('month', DB::raw('SUM(net_amount) as total_salary'))
    ->groupBy('month')
    ->orderBy('month', 'ASC')
    ->limit(6)
    ->get();


    // Attendance Summary (today)
    $today = date('Y-m-d');

    $attendanceSummary = DB::table('attendances')
        ->selectRaw("
            SUM(CASE WHEN status = 1 THEN 1 END) as present,
            SUM(CASE WHEN status = 0 THEN 1 END) as absent,
            SUM(CASE WHEN status = 2 THEN 1 END) as halfday
        ")
        ->whereDate('date', $today)
        ->first();

    // Revenue / Expense (dummy data / you can replace)
    $revenue = [20000, 25000, 22000, 27000, 30000, 32000];
    $expense = [10000, 12000, 15000, 16000, 17000, 18000];

    return view('dashboard.index', compact('salarySummary', 'attendanceSummary', 'revenue', 'expense'));
        // return view('dashboard/index5');
    }
    public function index2()
    {
        return view('dashboard/index2');
    }
    
    public function index3()
    {
        return view('dashboard/index3');
    }
    
    public function index4()
    {
        return view('dashboard/index4');
    }
    
    public function index5()
    {
$salarySummary = DB::table('salary_payments')
    ->select('month', DB::raw('SUM(net_amount) as total_salary'))
    ->groupBy('month')
    ->orderBy('month', 'ASC')
    ->limit(6)
    ->get();


    // Attendance Summary (today)
    $today = date('Y-m-d');

    $attendanceSummary = DB::table('attendances')
        ->selectRaw("
            SUM(CASE WHEN status = 1 THEN 1 END) as present,
            SUM(CASE WHEN status = 0 THEN 1 END) as absent,
            SUM(CASE WHEN status = 2 THEN 1 END) as halfday
        ")
        ->whereDate('date', $today)
        ->first();

    // Revenue / Expense (dummy data / you can replace)
    $revenue = [20000, 25000, 22000, 27000, 30000, 32000];
    $expense = [10000, 12000, 15000, 16000, 17000, 18000];

    return view('dashboard.index5', compact('salarySummary', 'attendanceSummary', 'revenue', 'expense'));
        // return view('dashboard/index5');
    }
    
    public function index6()
    {
        return view('dashboard/index6');
    }
    
    public function index7()
    {
        return view('dashboard/index7');
    }
    
    public function index8()
    {
        return view('dashboard/index8');
    }
    
    public function index9()
    {
        return view('dashboard/index9');
    }
    
    public function index10()
    {
        return view('dashboard/index10');
    }
// public function importAttendance(Request $request)
// {
//     $request->validate([
//         'file' => 'required|mimes:xlsx,csv'
//     ]);

//     Excel::import(new AttendanceImport, $request->file('file'));

//     return back()->with('success', 'Attendance Imported Successfully');
// }
    public function importAttendance(Request $request)
{
    $request->validate([
         'file' => 'required|file|extensions:xlsx,csv'
        // 'file' => 'required|mimes:xlsx,csv'
    ]);

    try {
        $import = new AttendanceImport();
        Excel::import($import, $request->file('file'));

        if ($import->failures()->isNotEmpty()) {
            return back()->with('import_errors', $import->failures());
        }

        return back()->with('success', 'Attendance Imported Successfully');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
public function machinesalaryCalculate(Request $request)
{
    // $query = DB::table('machines')
    //     ->leftJoin('employees', 'machines.id', '=', 'employees.machine_id')
    //     ->select(
    //         'machines.id',
    //         'machines.name as machine_name',
    //         'machines.image as machine_image',
    //         DB::raw('COUNT(employees.id) as total_employees'),
    //         DB::raw('COALESCE(SUM(
    //             CASE 
    //                 WHEN employees.salary_type = "monthly" THEN employees.salary_monthly
    //                 WHEN employees.salary_type = "daily" THEN employees.salary_daily * 30
    //                 ELSE 0 
    //             END
    //         ), 0) as total_monthly_salary')
    //     )
    //     ->groupBy('machines.id', 'machines.name', 'machines.image');
// $query = DB::table('machines')
//     ->leftJoin('employees', 'machines.id', '=', 'employees.machine_id')
//     ->select(
//         'machines.id',
//         'machines.name as machine_name',
//         'machines.image as machine_image',

//         DB::raw('COUNT(employees.id) as total_employees'),

//         // Monthly Salary
//         DB::raw('COALESCE(SUM(
//             CASE 
//                 WHEN employees.salary_type = "monthly" THEN employees.salary_monthly
//                 WHEN employees.salary_type = "daily" THEN employees.salary_daily * 30
//                 ELSE 0 
//             END
//         ), 0) as total_monthly_salary'),

//         // ✅ Daily Salary (NEW)
//         DB::raw('COALESCE(SUM(
//             CASE 
//                 WHEN employees.salary_type = "monthly" THEN employees.salary_monthly / 30
//                 WHEN employees.salary_type = "daily" THEN employees.salary_daily
//                 ELSE 0 
//             END
//         ), 0) as total_daily_salary')
//     )
//     ->groupBy('machines.id', 'machines.name', 'machines.image');

$days = date('t');

$query = DB::table('machines')
    ->leftJoin('employees', 'machines.id', '=', 'employees.machine_id')
    ->select(
        'machines.id',
        'machines.name as machine_name',
        'machines.image as machine_image',

        DB::raw('COUNT(employees.id) as total_employees'),

        //Monthly Cost
        DB::raw("COALESCE(SUM(
            CASE 
                WHEN employees.salary_type = 'monthly' THEN employees.salary_monthly
                WHEN employees.salary_type = 'daily' THEN employees.salary_daily * $days
                ELSE 0 
            END
        ), 0) as total_monthly_salary"),

        //Daily Cost
        DB::raw("COALESCE(SUM(
            CASE 
                WHEN employees.salary_type = 'monthly' THEN employees.salary_monthly / $days
                WHEN employees.salary_type = 'daily' THEN employees.salary_daily
                ELSE 0 
            END
        ), 0) as total_daily_salary")
    )
    ->groupBy('machines.id', 'machines.name', 'machines.image');
    // Search by machine name
    if ($search = $request->get('search')) {
        $query->where('machines.name', 'like', "%{$search}%");
    }

    $machineStats = $query->orderBy('machines.name')->paginate(10)->withQueryString();

    $grandTotalEmployees = $machineStats->sum('total_employees');
    $grandTotalSalary = $machineStats->sum('total_monthly_salary');

    return view('dashboard.machine_salary', compact(
        'machineStats',
        'grandTotalEmployees',
        'grandTotalSalary'
    ));
}
// public function exportMachineConsumption(Request $request)
// {
//     $request->validate([
//         'from_date' => 'required|date',
//         'to_date'   => 'required|date|after_or_equal:from_date',
//     ]);

//     $data = DB::table('attendances')
//         ->join('employees', 'attendances.employee_id', '=', 'employees.id')
//         ->join('machines', 'employees.machine_id', '=', 'machines.id')
//         ->whereBetween('attendances.date', [$request->from_date, $request->to_date])
//         ->whereIn('attendances.status', [1, 2]) // Present + Half Day
//         ->select(
//             'machines.name as machine_name',
//             DB::raw('COUNT(DISTINCT employees.id) as present_employees'),
//             DB::raw("
//                 ROUND(SUM(
//                     CASE
//                         WHEN employees.salary_type = 'daily' THEN
//                             employees.salary_daily *
//                             CASE
//                                 WHEN attendances.status = 2 THEN 0.5
//                                 ELSE 1
//                             END

//                         WHEN employees.salary_type = 'monthly' THEN
//                             (employees.salary_monthly / DAY(LAST_DAY(attendances.date))) *
//                             CASE
//                                 WHEN attendances.status = 2 THEN 0.5
//                                 ELSE 1
//                             END
//                         ELSE 0
//                     END
//                 ), 2) as total_consumption
//             ")
//         )
//         ->groupBy('machines.id', 'machines.name')
//         ->orderBy('machines.name')
//         ->get();

//     $exportData = [];
//     $exportData[] = [
//         'Machine Name',
//         'Present Employees',
//         'Total Consumption (₹)',
//     ];

//     foreach ($data as $row) {
//         $exportData[] = [
//             $row->machine_name,
//             $row->present_employees,
//             $row->total_consumption,
//         ];
//     }

//     $fileName = 'machine_consumption_' . $request->from_date . '_to_' . $request->to_date . '.xlsx';

//     return Excel::download(
//         new class($exportData) implements FromArray {
//             protected $data;

//             public function __construct(array $data)
//             {
//                 $this->data = $data;
//             }

//             public function array(): array
//             {
//                 return $this->data;
//             }
//         },
//         $fileName
//     );
// }
public function exportMachineConsumption(Request $request)
{
    $request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date',
    ]);

    $data = DB::table('attendances as a')
        ->join('employees as e', 'a.employee_id', '=', 'e.id')
        ->join('machines as m', 'a.machine_id', '=', 'm.id')
        ->whereBetween('a.date', [$request->from_date, $request->to_date])
        ->whereIn('a.status', [1, 2]) // 1 = Present, 2 = Half Day
        ->select(
            'm.id',
            'm.name as machine_name',

            // Total unique employees present
            DB::raw('COUNT(DISTINCT a.employee_id) as present_employees'),

            // Total effective present days
            DB::raw("
                ROUND(
                    SUM(
                        CASE
                            WHEN a.status = 1 THEN 1
                            WHEN a.status = 2 THEN 0.5
                            ELSE 0
                        END
                    ), 2
                ) as total_present_days
            "),

            // Total consumption based on actual attendance
            DB::raw("
                ROUND(
                    SUM(
                        CASE
                            WHEN e.salary_type = 'monthly' THEN
                                (e.salary_monthly / DAY(LAST_DAY(a.date))) *
                                CASE
                                    WHEN a.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            WHEN e.salary_type = 'daily' THEN
                                e.salary_daily *
                                CASE
                                    WHEN a.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            ELSE 0
                        END
                    ), 2
                ) as total_consumption
            "),

            // Average daily consumption based on unique attendance dates
            DB::raw("
                ROUND(
                    SUM(
                        CASE
                            WHEN e.salary_type = 'monthly' THEN
                                (e.salary_monthly / DAY(LAST_DAY(a.date))) *
                                CASE
                                    WHEN a.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            WHEN e.salary_type = 'daily' THEN
                                e.salary_daily *
                                CASE
                                    WHEN a.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            ELSE 0
                        END
                    ) / NULLIF(COUNT(DISTINCT DATE(a.date)), 0),
                    2
                ) as daily_consumption
            ")
        )
        ->groupBy('m.id', 'm.name')
        ->orderBy('m.name', 'ASC')
        ->get();

    $exportData = [];
    $exportData[] = [
        'Machine Name',
        'Present Employees',
        'Total Present Days',
        'Daily Consumption (₹)',
        'Total Consumption (₹)',
    ];

    foreach ($data as $row) {
        $exportData[] = [
            $row->machine_name,
            (int) $row->present_employees,
            number_format((float) $row->total_present_days, 2),
            number_format((float) $row->daily_consumption, 2),
            number_format((float) $row->total_consumption, 2),
        ];
    }

    $fileName = 'machine_consumption_' .
        $request->from_date . '_to_' .
        $request->to_date . '.xlsx';

    return Excel::download(
        new class($exportData) implements FromArray {
            protected array $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        },
        $fileName
    );
}
}
