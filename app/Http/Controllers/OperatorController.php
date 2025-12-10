<?php

// namespace App\Http\Controllers;

// use App\Models\Employee;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

// class OperatorController extends Controller
// {
//     /**
//      * Display a listing of the employees.
//      */
//     // public function index()
//     // {
//     //     $employees = Employee::select('id', 'uuid', 'name', 'mobile', 'employee_type', 'is_operator', 'user_id')
//     //         ->orderBy('name')
//     //         ->get();

//     //     return view('operators.index', compact('employees'));
//     // }
// public function index(Request $request)
// {
//     $employees = Employee::with('user')
//         ->orderBy('name')
//         ->paginate($request->get('per_page', 10));

//     // For dropdown (list all employees)
//     $allEmployees = Employee::where('is_operator', false)->orderBy('name')->get();

//     return view('operators.index', compact('employees', 'allEmployees'));
// }
//     /**
//      * Show the form for creating a new operator (assigning operator).
//      */
//     public function create(Request $request)
//     {
//         $uuid = $request->get('employee_uuid');
//         $employee = Employee::where('uuid', $uuid)->firstOrFail();

//         return view('operators.create', compact('employee'));
//     }

//     /**
//      * Store a newly created operator in storage.
//      */
//     public function store(Request $request)
//     {
//         $employee = Employee::where('uuid', $request->employee_uuid)->firstOrFail();

//         $validator = Validator::make($request->all(), [
//             'email' => 'required|email|unique:users,email',
//             'password' => 'required|min:6',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         // Create user as operator
//         $user = User::create([
//             'name' => $employee->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'role' => 'operator',
//         ]);

//         // Link to employee
//         $employee->update([
//             'is_operator' => 1,
//             'user_id' => $user->uuid,
//         ]);

//         return redirect()->route('operators.index')->with('success', 'Operator assigned successfully!');
//     }

//     /**
//      * Display the specified operator details.
//      */
//     public function show($uuid)
//     {
//         $employee = Employee::where('uuid', $uuid)->with('user')->firstOrFail();
//         return view('operators.show', compact('employee'));
//     }

//     /**
//      * Show the form for editing (optional if needed).
//      */
//     public function edit($uuid)
//     {
//         $employee = Employee::where('uuid', $uuid)->with('user')->firstOrFail();
//         return view('operators.edit', compact('employee'));
//     }

//     /**
//      * Update operator details (optional).
//      */
//     public function update(Request $request, $uuid)
//     {
//         $employee = Employee::where('uuid', $uuid)->firstOrFail();

//         $validator = Validator::make($request->all(), [
//             'email' => 'required|email|unique:users,email,' . $employee->user_id,
//             'password' => 'nullable|min:6',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $user = $employee->user;
//         if ($user) {
//             $user->update([
//                 'email' => $request->email,
//                 'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
//             ]);
//         }

//         return redirect()->route('operators.index')->with('success', 'Operator updated successfully!');
//     }

//     /**
//      * Remove the specified operator.
//      */
//     public function destroy($uuid)
//     {
//         $employee = Employee::where('uuid', $uuid)->firstOrFail();

//         if ($employee->user_id) {
//             User::where('id', $employee->user_id)->delete();
//         }

//         $employee->update([
//             'is_operator' => false,
//             'user_id' => null,
//         ]);

//         return redirect()->route('operators.index')->with('success', 'Operator removed successfully!');
//     }
// }

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Show the listing of employees (with their operator status).
     * Also provide a separate list of employees available to assign as operator.
     */
    public function index(Request $request)
    {
        // Paginated employees for the table (show all employees and indicate is_operator)
        // $employees = Employee::with('user')
        //     ->orderBy('name')
        //     ->paginate($request->get('per_page', 10));
    $employees = Employee::with('user')
        ->when($request->search, function($query) use ($request){
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('mobile', 'like', '%'.$request->search.'%');
        })
        ->orderBy('name')
        ->paginate($request->get('per_page', 10));
        //         echo"<pre>";
        // print_r($employees);die;
        // For the dropdown: only employees that are NOT operators
        $allEmployees = Employee::where('is_operator', false)
            ->orderBy('name')
            ->get();

        return view('operators.index', compact('employees', 'allEmployees'));
    }
public function show($uuid)
{
    $employee = Employee::with('user')->where('uuid', $uuid)->firstOrFail();
    return view('operators.show', compact('employee'));
}
    /**
     * Store a new operator (create User + link to Employee).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'min:6'],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        // Create user account for operator
        $user = User::create([
            'name'     => $employee->name,
            'employee_code'  => $employee->employee_code,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
// echo"<pre>";
// print_r($user);die;
        // Save correct user id (users.id)
        $employee->update([
            'is_operator' => true,
            'user_id'     => $user->id,
        ]);

        return redirect()->route('operators.index')->with('success', 'Operator assigned successfully.');
    }
public function updatePassword(Request $request, $userId)
{
    $request->validate([
        'email'    => 'required|email|unique:users,email,' . $userId,
        'password' => 'required|min:6|confirmed',
    ]);

    User::where('id', $userId)->update([
        'email'    => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return back()->with('success', 'Password updated successfully');
}


    /**
     * Remove operator: delete created user (optional) and unlink from employee.
     * We accept $id (employee id) because blade will post employee->id.
     */
    // public function destroy($id)
    // {
    //     $employee = Employee::findOrFail($id);

    //     // If employee had user account, delete it (optional)
    //     if ($employee->user_id) {
    //         $user = User::find($employee->user_id);
    //         if ($user) {
    //             $user->delete();
    //         }
    //     }

    //     $employee->update(['is_operator' => false, 'user_id' => null]);

    //     return redirect()->route('operators.index')->with('success', 'Operator removed successfully.');
    // }
    public function destroy($uuid)
{
    $employee = Employee::where('uuid', $uuid)->firstOrFail();

    // If employee had user account, delete it
    if ($employee->user_id) {
        $user = User::find($employee->user_id);
        if ($user) {
            $user->delete();
        }
    }

    $employee->update([
        'is_operator' => false,
        'user_id' => null,
    ]);

    return redirect()->route('operators.index')->with('success', 'Operator removed successfully.');
}

}
