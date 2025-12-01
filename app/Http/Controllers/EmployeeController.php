<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Contractor;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EmployeeController extends Controller
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
// echo"<pre>";print_r($machine);die;
        return view('employees.index', compact('employees', 'contractors','machine'));
    }

    public function create()
    {
        $contractors = Contractor::orderBy('name')->get();
        return view('employees.create', compact('contractors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'mobile'            => 'required|string|max:15|unique:employees,mobile',
            'govid'             => 'nullable|string|max:255',
            'dob'               => 'nullable|date',
            'gender'            => ['nullable', Rule::in(['male', 'female', 'other'])],
            'photo'             => 'nullable|image|max:2048',
            'fingerprint'       => 'nullable|file|max:4096',
            'contractor_id'     => 'nullable|exists:contractors,id',
            'machine'           => 'nullable|string|max:255',
            'salary_monthly'    => 'nullable|numeric|min:0',
            'employee_type'     => ['required', Rule::in(['contractor', 'company'])],
            'company_department'=> 'nullable|string|max:255',
        ]);

        // Generate UUID
        $validated['uuid'] = Str::uuid();
        $validated['created_by'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        if ($request->hasFile('fingerprint')) {
            $validated['fingerprint'] = $request->file('fingerprint')->store('employees/fingerprints', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }

    public function edit(Employee $employee)
    {
        $contractors = Contractor::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'contractors'));
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
            'contractor_id'     => 'nullable|exists:contractors,id',
            'machine'           => 'nullable|string|max:255',
            'salary_monthly'    => 'nullable|numeric|min:0',
            'employee_type'     => ['required', Rule::in(['contractor', 'company'])],
            'company_department'=> 'nullable|string|max:255',
        ]);

        // Replace photo if uploaded
        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        // Replace fingerprint if uploaded
        if ($request->hasFile('fingerprint')) {
            if ($employee->fingerprint) {
                Storage::disk('public')->delete($employee->fingerprint);
            }
            $validated['fingerprint'] = $request->file('fingerprint')->store('employees/fingerprints', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function show(Employee $employee)
    {
        $contractors = Contractor::orderBy('name')->get();
        return view('employees.show', compact('employee', 'contractors'));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employees deleted successfully.');
        // return response()->json([
        //     'message' => 'Employee deleted successfully!'
        // ]);
    }

    public function restore($uuid)
    {
        $employee = Employee::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $employee->restore();

        return redirect()->route('employees.index')->with('success', 'Employee restored successfully!');
    }
}