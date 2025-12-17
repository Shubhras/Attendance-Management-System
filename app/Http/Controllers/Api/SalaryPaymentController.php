<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    SalaryPayment,
    SalaryPaymentItem,
    Attendance,
    Employee
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller
{
    /**
     * HR Salary Payment
     * POST: employee_id, amount, thumb_verified
     * Month is AUTO from backend
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'amount'          => 'required|numeric|min:0',
            'payment_method'  => 'nullable|string',
            'notes'           => 'nullable|string',
            'thumb_verified'  => 'nullable|boolean'
        ]);

        DB::beginTransaction();

        try {
            $employee = Employee::findOrFail($request->employee_id);

            $now   = Carbon::now();
            $month = $now->format('Y-m');

            // Prevent duplicate salary
            if (SalaryPayment::where('employee_id', $employee->id)
                ->where('month', $month)
                ->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Salary already paid for current month'
                ], 409);
            }

            // Attendance
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()])
                ->get();

            $presentDays = $attendances->where('status', 1)->count();
            $halfDays    = $attendances->where('status', 2)->count();

            // Per day salary (optional, for info)
            $daysInMonth = $now->daysInMonth;
            $perDay = $employee->salary_type === 'monthly' 
                      ? $employee->salary_monthly / $daysInMonth 
                      : $employee->salary_daily;

            $presentPay = $perDay * $presentDays;
            $halfPay    = ($perDay * 0.5) * $halfDays;

            $grossAmount = round($presentPay + $halfPay, 2);

            // Use frontend amount as net pay
            $netPay = $request->amount;

            // Create salary payment
            $salary = SalaryPayment::create([
                'employee_id'       => $employee->id,
                'month'             => $month,
                'date_paid'         => now(),
                'created_by'        => auth()->id(),
                'gross_amount'      => $grossAmount,
                'net_amount'        => $netPay,
                'deductions'        => 0,
                'advance_deduction' => 0,
                'payment_method'    => $request->payment_method,
                'notes'             => $request->notes,
                'thumb_verified'    => $request->thumb_verified ?? false,
            ]);

            // Salary Items
            SalaryPaymentItem::insert([
                [
                    'salary_payment_id' => $salary->id,
                    'title' => 'Present Days Pay',
                    'amount' => round($presentPay, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_payment_id' => $salary->id,
                    'title' => 'Half Days Pay',
                    'amount' => round($halfPay, 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salary_payment_id' => $salary->id,
                    'title' => 'Net Pay',
                    'amount' => $netPay,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Salary paid successfully',
                'data' => [
                    'salary_id' => $salary->id,
                    'month'     => $month,
                    'gross'     => $grossAmount,
                    'net_paid'  => $netPay,
                    'thumb_verified' => $salary->thumb_verified
                ]
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage()
            ], 500);
        }
    }
}
