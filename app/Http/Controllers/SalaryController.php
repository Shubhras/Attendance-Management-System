<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\{SalaryPayment,AdvancePayment};
use App\Models\SalaryPaymentItem;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        // filters: month, employee, contractor, shift
        $month = $request->get('month', Carbon::now('Asia/Kolkata')->format('Y-m'));
        $employeeId = $request->get('employee_id');

        // $query = SalaryPayment::with('employee')->orderByDesc('date_paid');
$query = SalaryPayment::with(['employee', 'creator'])
    ->orderByDesc('date_paid');
        if ($employeeId) $query->where('employee_id', $employeeId);
        if ($month) $query->where('month', $month);

        $payments = $query->paginate($request->get('per_page', 15))->withQueryString();

        return view('salary.index', compact('payments','month'));
    }

    /**
     * Calculate salary preview for given employee & month (not persist)
     * Request: employee_id, month (YYYY-MM)
     */
// public function calculate(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|exists:employees,id',
//         'month' => 'required|date_format:Y-m'
//     ]);

//     $employee = Employee::findOrFail($request->employee_id);
//     $month = $request->month;
//     $start = Carbon::parse($month.'-01')->startOfMonth();
//     $end = Carbon::parse($month.'-01')->endOfMonth();
//     $daysInMonth = $start->daysInMonth;

//     $attendances = Attendance::where('employee_id', $employee->id)
//         ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
//         ->get();

//     $presentCount = $attendances->where('status','present')->count();
//     $halfDayCount = $attendances->where('status','half_day')->count();
//     $leaveCount = $attendances->where('status','leave')->count();
//     $absentCount = $daysInMonth - ($presentCount + $halfDayCount + $leaveCount);

//     // Salary calculations
//     if ($employee->salary_type === 'monthly') {
//         $perDay = $employee->salary_monthly / $daysInMonth;
//         $payPresent = $perDay * $presentCount;
//         $payHalf = $perDay * 0.5 * $halfDayCount;
//     } else { // daily
//         $perDay = $employee->salary_daily;
//         $payPresent = $perDay * $presentCount;
//         $payHalf = $perDay * 0.5 * $halfDayCount;
//     }

//     $gross = round($payPresent + $payHalf,2);
//     $deductions = 0;
//     $net = max(0, $gross - $deductions);

//     $items = [
//         ['title'=>'Present Days Pay', 'amount'=>$payPresent],
//         ['title'=>'Half Day Pay', 'amount'=>$payHalf],
//     ];

//     return response()->json([
//         'status'=>true,
//         'data'=>[
//             'employee'=>$employee,
//             'month'=>$month,
//             'total_days'=>$daysInMonth,
//             'present'=>$presentCount,
//             'half_day'=>$halfDayCount,
//             'leave'=>$leaveCount,
//             'absent'=>$absentCount,
//             'gross'=>$gross,
//             'deductions'=>$deductions,
//             'net'=>$net,
//             'items'=>$items
//         ]
//     ]);
// }
// Date:5nov
// public function calculate(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|exists:employees,id',
//         'month' => 'required|date_format:Y-m'
//     ]);

//     $employee = Employee::findOrFail($request->employee_id);
//     $month = $request->month;

//     $start = Carbon::parse($month . '-01')->startOfMonth();
//     $end = Carbon::parse($month . '-01')->endOfMonth();
//     $daysInMonth = $start->daysInMonth;

//     $attendances = Attendance::where('employee_id', $employee->id)
//         ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
//         ->get();

//     // ↙️ New numeric status calculation
//     $presentCount = $attendances->where('status', 1)->count();
//     // $absentCount  = $attendances->where('status', 0)->count();
//     // $halfDayCount = $attendances->where('status', 2)->count();
//     $leaveCount   = $attendances->where('status', 0)->count();

//     // Salary calculation
//     if ($employee->salary_type === 'monthly') {
//         $perDay = $employee->salary_monthly / $daysInMonth;
//     } else {
//         $perDay = $employee->salary_daily;
//     }

//     $payPresent = $perDay * $presentCount;
//     // $payHalf = $perDay * 0.5 * $halfDayCount; // Half day
//     $gross = round($payPresent, 2);
//     $deductions = 0;
//     $net = max(0, $gross - $deductions);

//     return response()->json([
//         'status' => true,
//         'data' => [
//             'employee' => $employee,
//             'month' => $month,
//             'total_days' => $daysInMonth,
//             'present' => $presentCount,
//             // 'half_day' => $halfDayCount,
//             'leave' => $leaveCount,
//             // 'absent' => $absentCount,
//             'gross' => $gross,
//             'deductions' => $deductions,
//             'net' => $net,
//             'items' => [
//                 ['title' => 'Present Days Pay', 'amount' => $payPresent],
//                 ['title' => 'Half Day Pay', 'amount' => $leaveCount],
//             ]
//         ]
//     ]);
// }

// public function calculate(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required|exists:employees,id',
//         'month' => 'required|date_format:Y-m'
//     ]);

//     $employee = Employee::findOrFail($request->employee_id);
//     $month = $request->month;

//     $start = Carbon::parse($month . '-01')->startOfMonth();
//     $end = Carbon::parse($month . '-01')->endOfMonth();
//     $daysInMonth = $start->daysInMonth;

//     // Get attendance for the month
//     $attendances = Attendance::where('employee_id', $employee->id)
//         ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
//         ->get();

//     // ✔️ Correct numeric logic
//     $presentCount = $attendances->where('status', 1)->count();  // Present
//     $halfDayCount = $attendances->where('status', 2)->count();  // Half Day
//     $leaveCount   = $attendances->where('status', 0)->count();  // Leave

//     // ✔️ Absent = days without attendance record
//     $absentCount = $daysInMonth - ($presentCount + $halfDayCount + $leaveCount);

//     // Salary calculation
//     if ($employee->salary_type === 'monthly') {
//         $perDay = $employee->salary_monthly / $daysInMonth;
//     } else {
//         $perDay = $employee->salary_daily;
//     }

//     $payPresent = $perDay * $presentCount;
//     $payHalf    = ($perDay * 0.5) * $halfDayCount;

//     $gross = round($payPresent + $payHalf, 2);
//     $deductions = 0;
//     $net = max(0, $gross - $deductions);

//     return response()->json([
//         'status' => true,
//         'data' => [
//             'employee' => $employee,
//             'month' => $month,
//             'total_days' => $daysInMonth,
//             'present' => $presentCount,
//             'half_day' => $halfDayCount,
//             'leave' => $leaveCount,
//             'absent' => $absentCount,
//             'gross' => $gross,
//             'deductions' => $deductions,
//             'net' => $net,
//             'items' => [
//                 ['title' => 'Present Days Pay', 'amount' => $payPresent],
//                 ['title' => 'Half Days Pay', 'amount' => $payHalf],
//             ]
//         ]
//     ]);
// }
public function calculate(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'month' => 'required|date_format:Y-m'
    ]);

    $employee = Employee::findOrFail($request->employee_id);
    $month = $request->month;

    $start = Carbon::parse($month . '-01')->startOfMonth();
    $end   = Carbon::parse($month . '-01')->endOfMonth();
    $daysInMonth = $start->daysInMonth;

    // 🔹 Attendance for the month
    $attendances = Attendance::where('employee_id', $employee->id)
        ->whereBetween('date', [
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        ])
        ->get();

    // 🔹 Attendance counts
    $presentCount = $attendances->where('status', 1)->count(); // Present
    $halfDayCount = $attendances->where('status', 2)->count(); // Half Day
    $leaveCount   = $attendances->where('status', 0)->count(); // Leave

    // 🔹 Absent = days without attendance record
    $absentCount = $daysInMonth - ($presentCount + $halfDayCount + $leaveCount);

    // 🔹 Per day salary
    if ($employee->salary_type === 'monthly') {
        $perDay = $employee->salary_monthly / $daysInMonth;
    } else {
        $perDay = $employee->salary_daily;
    }

    // 🔹 Salary calculation
    $payPresent = $perDay * $presentCount;
    $payHalf    = ($perDay * 0.5) * $halfDayCount;

    $gross = round($payPresent + $payHalf, 2);

    // ======================================================
    // 🔥 ADVANCE PAYMENT DEDUCTION LOGIC (ADDED)
    // ======================================================
    $advanceAmount = AdvancePayment::where('employee_id', $employee->id)
        ->whereMonth('paid_at', $start->month)
        ->whereYear('paid_at', $start->year)
        ->sum('amount');

    $deductions = $advanceAmount;
    $net = max(0, $gross - $deductions);

    return response()->json([
        'status' => true,
        'data' => [
            'employee' => $employee,
            'month' => $month,
            'total_days' => $daysInMonth,
            'present' => $presentCount,
            'half_day' => $halfDayCount,
            'leave' => $leaveCount,
            'absent' => $absentCount,
            'gross' => $gross,
            'advance_deduction' => $advanceAmount,
            'deductions' => $deductions,
            'net' => $net,
            'items' => [
                ['title' => 'Present Days Pay', 'amount' => round($payPresent, 2)],
                ['title' => 'Half Days Pay', 'amount' => round($payHalf, 2)],
                ['title' => 'Advance Deduction', 'amount' => $advanceAmount],
            ]
        ]
    ]);
}
    /**
     * Persist salary payment
     * POST: employee_id, month, date_paid (optional), payment_method, notes
     * Body: items[] {title, amount} — optional; if not sent, controller uses calculate logic
     */
    public function pay(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'date_paid' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'items' => 'nullable|array'
        ]);

        DB::beginTransaction();
        try {
            // compute amounts if not provided
            $calcResp = $this->calculate(app('request')->merge($request->only(['employee_id','month'])));
            $calcData = json_decode($calcResp->getContent(), true)['data'];

            $gross = $calcData['gross'];
            $deductions = 0;
            $net = $calcData['net'];

            $payment = SalaryPayment::create([
                'employee_id' => $request->employee_id,
                'month' => $request->month,
                'date_paid' => $request->date_paid ?? Carbon::now('Asia/Kolkata')->format('Y-m-d'),
                'gross_amount' => $gross,
                'deductions' => $deductions,
                'net_amount' => $net,
                'created_by' => auth()->id(),
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            // items: store breakdown
            $items = $request->items ?? $calcData['items'];
            foreach ($items as $it) {
                SalaryPaymentItem::create([
                    'salary_payment_id' => $payment->id,
                    'title' => $it['title'],
                    'amount' => $it['amount']
                ]);
            }

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Salary paid and saved','payment'=>$payment],201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status'=>false,'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Salary slip PDF for a specific payment
     */
    public function slipPdf($id)
    {
        // $payment = SalaryPayment::with('employee','items')->findOrFail($id);
        $payment = SalaryPayment::with(['employee', 'items', 'creator'])
    ->findOrFail($id);
        $generatedAt = Carbon::now('Asia/Kolkata')->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('salary.pdf.slip', compact('payment','generatedAt'))->setPaper('a4','portrait');
        return $pdf->download("salary-slip-{$payment->employee->employee_code}-{$payment->month}.pdf");
    }

    /**
     * Monthly consolidated salary PDF
     */
// public function monthlyReportPdf(Request $request)
// {
//     $month = $request->month ?? now()->format('Y-m');
//     $start = Carbon::parse($month.'-01')->startOfMonth();
//     $end = Carbon::parse($month.'-01')->endOfMonth();

//     $payments = SalaryPayment::with('employee')->get()->map(function($payment) use ($start, $end) {
//         $attendances = Attendance::where('employee_id', $payment->employee_id)
//             ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
//             ->get();

//         $presentDays = $attendances->where('status','present')->count();
//         $halfDays = $attendances->where('status','half_day')->count();

//         $payment->present_days = $presentDays;
//         $payment->half_days = $halfDays;
//         $payment->total_salary = $payment->net_amount;

//         return $payment;
//     });

//     $pdf = Pdf::loadView('salary.pdf.monthly_report', compact('payments', 'month'));

//     return $pdf->download('monthly_salary_report_'.$month.'.pdf');
// }
public function monthlyReportPdf(Request $request)
{
    $month = $request->month ?? now()->format('Y-m');
    $start = Carbon::parse($month.'-01')->startOfMonth();
    $end = Carbon::parse($month.'-01')->endOfMonth();

    $payments = SalaryPayment::with(['employee' => function($q){
        $q->withTrashed(); // include deleted employees
    }])->get()->map(function($payment) use ($start, $end) {

        $att = Attendance::where('employee_id', $payment->employee_id)
            ->whereBetween('date', [$start, $end])
            ->get();

        $payment->present_days = $att->where('status', 1)->count();
        $payment->half_days    = $att->where('status', 2)->count();
        $payment->leave_days   = $att->where('status', 0)->count();
        $payment->total_salary = $payment->net_amount;

        return $payment;
    });

    $pdf = Pdf::loadView('salary.pdf.monthly_report', compact('payments', 'month'));
    return $pdf->download('monthly_salary_report_'.$month.'.pdf');
}

}
