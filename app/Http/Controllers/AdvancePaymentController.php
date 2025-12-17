<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{AdvancePayment, Employee, Contractor, Machine, User};
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
class AdvancePaymentController extends Controller
{
    public function index()
    {
        $advances = AdvancePayment::with('employee','machine','payer')
            ->latest()->paginate(15);

        return view('advance.index', compact('advances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount'      => 'required|numeric|min:1',
            'machine_id'  => 'nullable|exists:machines,id',
            'reason'      => 'nullable|string'
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        AdvancePayment::create([
            'employee_id'   => $employee->id,
            'contractor_id' => $employee->employee_type === 'contractor'
                                ? $employee->contractor_id
                                : null,
            'machine_id'    => $request->machine_id,
            'hr_id'         => auth()->id(),   // 👈 HR USER
            'employee_type' => $employee->employee_type,
            'amount'        => $request->amount,
            'reason'        => $request->reason,
            'thumb_verified'=> true,
            'paid_at'       => now(),
        ]);


        return back()->with('success','Advance payment saved');
    }
}
