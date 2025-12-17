<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Response;
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
    $query = DB::table('machines')
        ->leftJoin('employees', 'machines.id', '=', 'employees.machine_id')
        ->select(
            'machines.id',
            'machines.name as machine_name',
            'machines.image as machine_image',
            DB::raw('COUNT(employees.id) as total_employees'),
            DB::raw('COALESCE(SUM(
                CASE 
                    WHEN employees.salary_type = "monthly" THEN employees.salary_monthly
                    WHEN employees.salary_type = "daily" THEN employees.salary_daily * 30
                    ELSE 0 
                END
            ), 0) as total_monthly_salary')
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
}
