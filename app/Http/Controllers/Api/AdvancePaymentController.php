<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{AdvancePayment, Employee};
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdvancePaymentController extends Controller
{
    public function store(Request $request)
    {
        // 🔐 Ensure HR is logged in
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. HR login required.'
            ], 401);
        }

        // ✅ FORCE JSON validation (NO REDIRECT ISSUE)
        $validator = Validator::make($request->all(), [
            'employee_id'    => 'required|exists:employees,id',
            'amount'         => 'required|numeric|min:1',
            'thumb_verified' => 'required|boolean',
            'machine_id'     => 'nullable|exists:machines,id',
            'reason'         => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ✅ Get employee
        $employee = Employee::findOrFail($request->employee_id);

        // ✅ Create advance payment
        $advance = AdvancePayment::create([
            'employee_id'   => $employee->id,
            'contractor_id' => $employee->employee_type === 'contractor'
                                ? $employee->contractor_id
                                : null,
            'machine_id'    => $request->machine_id,
            'hr_id'         => auth()->id(), // HR USER ID
            'employee_type' => $employee->employee_type,
            'amount'        => $request->amount,
            'reason'        => $request->reason,
            'thumb_verified'=> $request->thumb_verified,
            'paid_at'       => Carbon::now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Advance payment created successfully',
            'data'    => [
                'id'            => $advance->id,
                'employee_id'   => $advance->employee_id,
                'amount'        => $advance->amount,
                'thumb_verified'=> $advance->thumb_verified,
                'reason'        => $advance->reason,
                'paid_at'       => $advance->paid_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }
}
