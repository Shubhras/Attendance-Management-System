<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Contractor;
use App\Models\{Machine,Shift,Attendance};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

    class EmployeesController extends Controller
    {
        public function index(Request $request)
        {
            $query = Employee::query();

            // Filters
            if ($request->employee_type) {
                $query->where('employee_type', $request->employee_type);
            }

            if ($request->contractor_id) {
                $query->where('contractor_id', $request->contractor_id);
            }

            // if ($search = $request->search) {
            //     $query->where(function ($q) use ($search) {
            //         $q->where('name', 'like', "%{$search}%")
            //           ->orWhere('mobile', 'like', "%{$search}%")
            //           ->orWhere('govid', 'like', "%{$search}%");
            //     });
            // }
            if ($search = $request->search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }
            $employees = $query->latest()->paginate($request->get('per_page', 10));
            $contractors = Contractor::orderBy('name')->get();
            $machine = Machine::orderBy('name')->get();
            $shifts = Shift::orderBy('shift_name')->get();
    // echo"<pre>";print_r($machine);die;
            return view('employees-get.index', compact('employees', 'contractors','machine','shifts'));
        }

        public function create()
        {
            $contractors = Contractor::orderBy('name')->get();
            $shifts = Shift::orderBy('shift_name')->get();
            return view('employees-get.create', compact('contractors','shifts'));
        }

        // public function store(Request $request)
        // {
        //     $validated = $request->validate([
        //         'name'              => 'required|string|max:255',
        //         'mobile'            => 'required|string|max:15|unique:employees,mobile',
        //         'govid'             => 'nullable|string|max:255',
        //         'dob'               => 'nullable|date',
        //         'gender'            => ['nullable', Rule::in(['male', 'female', 'other'])],
        //         'photo'             => 'nullable|image|max:2048',
        //         'fingerprint'       => 'nullable|file|max:4096',
        //         'contractor_id'     => 'nullable|exists:contractors,id',
        //         'machine'           => 'nullable|string|max:255',
        //         'salary_monthly'    => 'nullable|numeric|min:0',
        //         'employee_type'     => ['required', Rule::in(['contractor', 'company'])],
        //         'company_department'=> 'nullable|string|max:255',
        //     ]);

        //     // Generate UUID
        //     $validated['uuid'] = Str::uuid();
        //     $validated['created_by'] = auth()->id();

        //     // ✅ Save photo directly to public/employees/photos
        //     if ($request->hasFile('photo')) {
        //         $photo = $request->file('photo');
        //         $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        //         $photo->move(public_path('employees/photos'), $photoName);
        //         $validated['photo'] = 'employees/photos/' . $photoName;
        //     }
        
        //     // ✅ Save fingerprint directly to public/employees/fingerprints
        //     if ($request->hasFile('fingerprint')) {
        //         $fingerprint = $request->file('fingerprint');
        //         $fingerprintName = uniqid() . '.' . $fingerprint->getClientOriginalExtension();
        //         $fingerprint->move(public_path('employees/fingerprints'), $fingerprintName);
        //         $validated['fingerprint'] = 'employees/fingerprints/' . $fingerprintName;
        //     }

        //     Employee::create($validated);

        //     return redirect()->route('employees-get.index')->with('success', 'Employee created successfully!');
        // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'mobile'            => 'required|string|max:15|unique:employees,mobile',
            'govid'             => 'nullable|string|max:255',
            'dob'               => 'nullable|date',
            'gender'            => ['nullable', Rule::in(['male', 'female', 'other'])],
            'photo'             => 'nullable|image|max:2048',
            //'fingerprint'       => 'nullable|string|max:255',
            'aadhar_card'       => 'nullable|file|max:4096',
            'contractor_id'     => 'nullable|exists:contractors,id',
            //'machine'           => 'nullable|string|max:255',
            'machine_id' => 'nullable|exists:machines,id',
            'shift_id' => 'nullable|exists:shifts,id',
            // 'salary_monthly'    => 'nullable|numeric|min:0',
            'salary_type'       => ['required', Rule::in(['monthly', 'daily'])],
            'salary_monthly'    => 'nullable|numeric|min:0',
            'salary_daily'      => 'nullable|numeric|min:0',
            'employee_type'     => ['required', Rule::in(['contractor', 'company'])],
            'company_department'=> 'nullable|string|max:255',
            'employee_work_title' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
        ]);

        $validated['uuid'] = Str::uuid();
        $validated['created_by'] = auth()->id();
        if ($validated['salary_type'] === 'monthly') {
            $validated['salary_daily'] = null;
        } else {
            $validated['salary_monthly'] = null;
        }
        // ✅ Handle Photo Upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('employees/photos'), $photoName);
            $validated['photo'] = 'employees/photos/' . $photoName;
        }

        // ✅ Handle Fingerprint Upload
        // if ($request->hasFile('fingerprint')) {
        //     $fingerprint = $request->file('fingerprint');
        //     $fingerprintName = uniqid() . '.' . $fingerprint->getClientOriginalExtension();
        //     $fingerprint->move(public_path('employees/fingerprints'), $fingerprintName);
        //     $validated['fingerprint'] = 'employees/fingerprints/' . $fingerprintName;
        // }
        $validated['fingerprint'] = 'false';
        $validated['fingerprint_template_data'] = null;
        // ✅ Handle Aadhaar Card Upload
        if ($request->hasFile('aadhar_card')) {
            $aadhar = $request->file('aadhar_card');
            $aadharName = uniqid() . '.' . $aadhar->getClientOriginalExtension();
            $aadhar->move(public_path('employees/aadhar_cards'), $aadharName);
            $validated['aadhar_card'] = 'employees/aadhar_cards/' . $aadharName;
        }

        // ✅ Generate Employee Code
        // if ($validated['employee_type'] === 'company') {
        //     // Company employees use EMP-01 → EMP-999
        //     $count = Employee::where('employee_type', 'company')->count() + 1;
        //     $validated['employee_code'] = 'EMP-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        // } 
        if ($validated['employee_type'] === 'company') {
            $lastEmp = Employee::where('employee_type', 'company')
                ->where('employee_code', 'like', 'EMP-%')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastEmp) {
                $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastEmp->employee_code);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $validated['employee_code'] = 'EMP-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        }
        else {
            // Contractor employees use CONT-XXXX based on contractor code range
            $contractor = Contractor::findOrFail($validated['contractor_id']);
            $lastEmp = Employee::where('contractor_id', $contractor->id)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastEmp
                ? ((int)preg_replace('/[^0-9]/', '', $lastEmp->employee_code) + 1)
                : $contractor->code_start;

            if ($nextNumber > $contractor->code_end) {
                return redirect()->back()->withErrors([
                    'error' => 'This contractor’s employee code range is full.',
                ]);
            }

            $validated['employee_code'] = 'CONT-' . $nextNumber;
        }

        // Employee::create($validated);
        $employee = Employee::create($validated);
// create dummy fingerprint template & today's pending attendance
// $dummy = [
//     'device' => 'MFS110',
//     'template_id' => (string) Str::uuid(),
//     'notes' => 'enrolled by admin (dummy)'
// ];

// Attendance::create([
//     'employee_id' => $employee->id,
//     'date' => Carbon::now('Asia/Kolkata')->format('Y-m-d'),
//     'status' => 'pending',
//     'fingerprint_template' => $dummy,
//     'marked_by' => auth()->id()
// ]);
        return redirect()->route('employees-get.index')->with('success', 'Employee created successfully!');
    }

        public function edit(Employee $employee)
        {
            $contractors = Contractor::orderBy('name')->get();
            $shifts = Shift::orderBy('shift_name')->get();
            $machines = Machine::all();
            
            return view('employees-get.edit', compact('employee', 'contractors','machines','shifts'));
        }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'mobile'            => ['required', 'string', 'max:15', Rule::unique('employees')->ignore($employee->id)],
            'govid'             => 'nullable|string|max:255',
            'dob'               => 'nullable|date',
            'gender'            => ['nullable', Rule::in(['male', 'female', 'other'])],
            'photo'             => 'nullable|image|max:2048',
            'fingerprint'       => 'nullable|file|max:4096',
            'aadhar_card'       => 'nullable|file|max:4096',
            'contractor_id'     => 'nullable|exists:contractors,id',
            //'machine'           => 'nullable|string|max:255',
            'machine_id' => 'nullable|exists:machines,id',
            'shift_id' => 'nullable|exists:shifts,id',
            //'salary_monthly'    => 'nullable|numeric|min:0',
            'salary_type'       => ['required', Rule::in(['monthly', 'daily'])],
            'salary_monthly'    => 'nullable|numeric|min:0',
            'salary_daily'      => 'nullable|numeric|min:0',
            'employee_type'     => ['required', Rule::in(['contractor', 'company'])],
            'company_department'=> 'nullable|string|max:255',
            'employee_work_title' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
        ]);

        // ✅ Update Photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('employees/photos'), $photoName);
            $validated['photo'] = 'employees/photos/' . $photoName;
        }

        // ✅ Update Fingerprint
        if ($request->hasFile('fingerprint')) {
            $fingerprint = $request->file('fingerprint');
            $fingerprintName = uniqid() . '.' . $fingerprint->getClientOriginalExtension();
            $fingerprint->move(public_path('employees/fingerprints'), $fingerprintName);
            $validated['fingerprint'] = 'employees/fingerprints/' . $fingerprintName;
        }

        // ✅ Update Aadhaar Card
        if ($request->hasFile('aadhar_card')) {
            $aadhar = $request->file('aadhar_card');
            $aadharName = uniqid() . '.' . $aadhar->getClientOriginalExtension();
            $aadhar->move(public_path('employees/aadhar_cards'), $aadharName);
            $validated['aadhar_card'] = 'employees/aadhar_cards/' . $aadharName;
        }

        $employee->update($validated);

        return redirect()->route('employees-get.index')->with('success', 'Employee updated successfully!');
    }

        // public function show(Employee $employee)
        // {
        //     //  $employee->load(['machine', 'contractor']);
        //     $employees = Machine::orderBy('name')->get();
        //     $contractors = Contractor::orderBy('name')->get();
        //     // echo"<pre>";print_r($employees);die;
        //     return view('employees-get.show', compact('employee','employees', 'contractors'));
        // }
public function show(Employee $employee)
{
    $employee->load(['machine', 'contractor']); // <-- IMPORTANT

    return view('employees-get.show', [
        'employee' => $employee,
    ]);
}
        public function destroy(Employee $employee)
        {
            $employee->delete();
            return redirect()->route('employees-get.index')->with('success', 'Employees deleted successfully.');
            // return response()->json([
            //     'message' => 'Employee deleted successfully!'
            // ]);
        }

        public function restore($uuid)
        {
            $employee = Employee::withTrashed()->where('uuid', $uuid)->firstOrFail();
            $employee->restore();

            return redirect()->route('employees-get.index')->with('success', 'Employee restored successfully!');
        }
    }
