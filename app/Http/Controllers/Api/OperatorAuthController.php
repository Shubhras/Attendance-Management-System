<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{User, Employee , Machine , Contractor,ThumbMachineData,Shift,Attendance};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OperatorAuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $validated = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $validated['email'])->first();

    //     if (! $user || ! Hash::check($validated['password'], $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     $token = $user->createToken('operator-token')->plainTextToken;
    //     // 3️⃣ Match employee by employee_code
    //     $employee = Employee::where('employee_code', $user->employee_code)->first();
    //         // Add photo to user object
    // if ($employee && $employee->photo) {
    //     // $user->photo_url = asset('storage/' . $employee->photo);
    //     $user->photo = $employee->photo; // if you want raw path also
    // } else {
    //     // $user->photo_url = null;
    //     $user->photo = null;
    // }
    //     return response()->json([
    //         'status' => 200,
    //         'token' => $token,
    //         'user' => $user,
    //         'employee' => $employee,
    //     ], 200); 
    // }
    public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $validated['email'])->first();

    if (! $user || ! Hash::check($validated['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Check role
    if ($user->role == 'operator') {
        $tokenName = 'operator-token';
    } elseif ($user->role == 'hr') {
        $tokenName = 'hr-token';
    } else {
        return response()->json(['message' => 'Unauthorized role'], 403);
    }

    $token = $user->createToken($tokenName)->plainTextToken;

    // Match employee by employee_code
    $employee = Employee::where('employee_code', $user->employee_code)->first();

    // Attach photo if exists
    $user->photo = $employee && $employee->photo ? $employee->photo : null;

    return response()->json([
        'status' => 200,
        'token' => $token,
        'user' => $user,
        'employee' => $employee,
        'role' => $user->role, // return role explicitly
    ], 200);
}

// public function login(Request $request)
// {
//     $validated = $request->validate([
//         'email'       => 'required|email',
//         'password'    => 'required',
//         'device_name' => 'required'   // recommended for Sanctum
//     ]);

//     $user = User::where('email', $validated['email'])->first();

//     if (! $user || ! Hash::check($validated['password'], $user->password)) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Invalid credentials'
//         ], 401);
//     }

//     // Create token based on role
//     if ($user->role === 'admin') {

//         $token = $user->createToken('admin-token')->plainTextToken;

//         return response()->json([
//             'status'  => true,
//             'role'    => 'admin',
//             'message' => 'Admin login successful',
//             'token'   => $token,
//             'user'    => $user
//         ], 200);
//     }

//     if ($user->role === 'operator') {

//         $token = $user->createToken('operator-token')->plainTextToken;

//         $employee = Employee::where('employee_code', $user->employee_code)->first();

//         return response()->json([
//             'status'   => true,
//             'role'     => 'operator',
//             'message'  => 'Operator login successful',
//             'token'    => $token,
//             'user'     => $user,
//             'employee' => $employee
//         ], 200);
//     }

//     // If some unknown role
//     return response()->json([
//         'status'  => false,
//         'message' => 'Role not allowed'
//     ], 403);
// }
// public function logout(Request $request)
// {
//     $token = $request->user()->currentAccessToken();

//     if ($token) {
//         $token->delete();
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Logout successful'
//     ]);
// }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent'])
            : response()->json(['message' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Invalid token'], 400);
    }

// public function getEmployees(Request $request)
// {
//     $perPage = (int) $request->get('per_page', 10);
//     $search = $request->get('search');

//     // Base query
//     $query = Employee::with('user:id,name,email')
//         ->select('id', 'uuid', 'name', 'mobile', 'gender', 'dob', 'is_operator', 'user_id', 'photo', 'fingerprint');

//     // Optional search filter
//     if (!empty($search)) {
//         $query->where(function ($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('mobile', 'like', "%{$search}%")
//               ->orWhereHas('user', function ($u) use ($search) {
//                   $u->where('email', 'like', "%{$search}%");
//               });
//         });
//     }

//     // Paginate results
//     $employees = $query->orderBy('id', 'asc')->paginate($perPage);

//     // ✅ Use domain from .env (APP_URL)
//     $domain = rtrim(config('app.url'), '/');

//     // Map and format employees
//     $employeesData = $employees->map(function ($employee) use ($domain) {
//         return [
//             'id'          => $employee->id,
//             'uuid'        => $employee->uuid,
//             'name'        => $employee->name,
//             'mobile'      => $employee->mobile,
//             'gender'      => $employee->gender,
//             'dob'         => $employee->dob,
//             'is_operator' => $employee->is_operator,
//             'user'        => $employee->user,
//             'photo'       => $employee->photo ? "{$domain}/{$employee->photo}" : null,
//             'fingerprint' => $employee->fingerprint ? "{$domain}/{$employee->fingerprint}" : null,
//         ];
//     });

//     // JSON response
//     return response()->json([
//         'status' => true,
//         'message' => 'Employee list fetched successfully',
//         'data' => $employeesData,
//         'pagination' => [
//             'total' => $employees->total(),
//             'per_page' => $employees->perPage(),
//             'current_page' => $employees->currentPage(),
//             'last_page' => $employees->lastPage(),
//             'next_page_url' => $employees->nextPageUrl(),
//             'prev_page_url' => $employees->previousPageUrl(),
//         ],
//     ]);
// }


// public function getEmployees(Request $request)
// {
//     $perPage = (int) $request->get('per_page', 10);
//     $search = $request->get('search');

//     $query = Employee::with([
//         'user:id,name,email',
//         'shift' // ✅ load shift relation
//     ]);

//     // Optional search filter
//     if (!empty($search)) {
//         $query->where(function ($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('mobile', 'like', "%{$search}%")
//               ->orWhereHas('user', function ($u) use ($search) {
//                   $u->where('email', 'like', "%{$search}%");
//               });
//         });
//     }

//     // Paginate results
//     $employees = $query->orderBy('id', 'asc')->paginate($perPage);

//     // ✅ Use domain from .env (APP_URL)
//     $domain = rtrim(config('app.url'), '/');

//     // Map and format employees
//     $employeesData = $employees->map(function ($employee) use ($domain) {
//         $data = $employee->toArray(); // includes all columns

//         // Convert image paths to full URLs
//         $data['photo'] = $employee->photo ? "{$domain}/{$employee->photo}" : null;
//         // $data['fingerprint'] = $employee->fingerprint ? "{$domain}/{$employee->fingerprint}" : null;
//         $data['aadhar_card'] = $employee->aadhar_card ? "{$domain}/{$employee->aadhar_card}" : null;

//         // Include related user info
//         $data['user'] = $employee->user;
//         // ✅ Return shift details instead of ID
//         $data['shift'] = $employee->shift ? [
//             'shift_name'     => $employee->shift->shift_name,
//             'clock_in_time'  => $employee->shift->clock_in_time,
//             'clock_out_time' => $employee->shift->clock_out_time,
//         ] : null;
//         unset($data['shift_id']);
//         return $data;
//     });

//     // JSON response
//     return response()->json([
//         'status' => true,
//         'message' => 'Employee list fetched successfully',
//         'data' => $employeesData,
//         'pagination' => [
//             'total' => $employees->total(),
//             'per_page' => $employees->perPage(),
//             'current_page' => $employees->currentPage(),
//             'last_page' => $employees->lastPage(),
//             'next_page_url' => $employees->nextPageUrl(),
//             'prev_page_url' => $employees->previousPageUrl(),
//         ],
//     ]);
// }
public function getEmployees(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $search = $request->get('search');
    $fingerprintStatus = $request->get('fingerprint_status'); // 'true' or 'false'

    $query = Employee::with(['user:id,name,email','shift']);

    // Filter by fingerprint status if provided
    if ($fingerprintStatus !== null) {
        if ($fingerprintStatus === 'true' || $fingerprintStatus === '1') {
            $query->where('fingerprint', '=', '1');
        } elseif ($fingerprintStatus === 'false' || $fingerprintStatus === '0') {
            $query->where(function($q) {
                $q->where('fingerprint', '=', '0')
                ->orWhere('fingerprint', '=', 'false')
                ->orWhereNull('fingerprint');
            });
        }
    }


    // Optional search filter
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('email', 'like', "%{$search}%");
              });
        });
    }

    // Paginate results
    $employees = $query->orderBy('id', 'asc')->paginate($perPage);

    if ($employees->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found',
            'data' => [],
        ]);
    }

    $domain = rtrim(config('app.url'), '/');

    // Map employees
    $employeesData = $employees->map(function ($employee) use ($domain) {
        $data = $employee->toArray();
        $data['photo'] = $employee->photo ? "{$domain}/{$employee->photo}" : null;
        $data['aadhar_card'] = $employee->aadhar_card ? "{$domain}/{$employee->aadhar_card}" : null;
        $data['user'] = $employee->user;
        $data['shift'] = $employee->shift ? [
            'shift_name' => $employee->shift->shift_name,
            'clock_in_time' => $employee->shift->clock_in_time,
            'clock_out_time' => $employee->shift->clock_out_time,
        ] : null;
        unset($data['shift_id']);
        return $data;
    });

    return response()->json([
        'status' => true,
        'message' => 'Employee list fetched successfully',
        'data' => $employeesData,
        'pagination' => [
            'total' => $employees->total(),
            'per_page' => $employees->perPage(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'next_page_url' => $employees->nextPageUrl(),
            'prev_page_url' => $employees->previousPageUrl(),
        ],
    ]);
}


// public function getEmployeeDetails($uuid)
// {
//     $employee = Employee::with('user:id,name,email')
//         ->where('uuid', $uuid)
//         ->select('id', 'uuid', 'name', 'mobile', 'gender', 'dob', 'is_operator', 'user_id', 'photo', 'fingerprint')
//         ->first();

//     if (! $employee) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Employee not found',
//         ], 404);
//     }

//     // ✅ Get domain from .env
//     $domain = rtrim(config('app.url'), '/');

//     // ✅ Build full response with URLs
//     $employeeData = [
//         'id'          => $employee->id,
//         'uuid'        => $employee->uuid,
//         'name'        => $employee->name,
//         'mobile'      => $employee->mobile,
//         'gender'      => $employee->gender,
//         'dob'         => $employee->dob,
//         'is_operator' => $employee->is_operator,
//         'user'        => $employee->user,
//         'photo'       => $employee->photo ? "{$domain}/{$employee->photo}" : null,
//         'fingerprint' => $employee->fingerprint ? "{$domain}/{$employee->fingerprint}" : null,
//     ];

//     return response()->json([
//         'status' => true,
//         'message' => 'Employee details fetched successfully',
//         'data' => $employeeData,
//     ]);
// }
public function getEmployeeDetails($uuid)
{
    $employee = Employee::with([
            'user:id,name,email',
            'machine:id,name',
            'shift:id,shift_name,clock_in_time,clock_out_time' // ✅ added shift relation
        ])
        ->where('uuid', $uuid)
        ->first();

    if (! $employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found',
        ], 404);
    }
    // ✅ Convert to array
    $employeeData = $employee->toArray();

    // ✅ Add full URLs for file fields
    $fileFields = ['photo', 'fingerprint', 'aadhar_card'];
    foreach ($fileFields as $field) {
        if (!empty($employeeData[$field])) {
            $employeeData[$field] = "{$employeeData[$field]}";
        }
    }

    // ✅ Add machine name (if relationship exists)
    $employeeData['machine_name'] = $employee->machine->name ?? null;
    // ✅ Add shift details instead of shift_id
    $employeeData['shift'] = $employee->shift ? [
        'shift_name'     => $employee->shift->shift_name,
        'clock_in_time'  => $employee->shift->clock_in_time,
        'clock_out_time' => $employee->shift->clock_out_time,
    ] : null;

    // Remove shift_id from API response if you don't want it
    unset($employeeData['shift_id']);

    return response()->json([
        'status' => true,
        'message' => 'Employee details fetched successfully',
        'data' => $employeeData,
    ]);
}

// public function getMachines(Request $request)
// {
//     $perPage = (int) $request->get('per_page', 10);
//     $search = $request->get('search');

//     // ✅ Fetch all columns
//     $query = Machine::query();

//     // Optional search filter
//     if (!empty($search)) {
//         $query->where(function ($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('description', 'like', "%{$search}%");
//         });
//     }

//     // Paginate
//     $machines = $query->orderBy('id', 'asc')->paginate($perPage);

//     // Prepare domain
//     $domain = rtrim(config('app.url'), '/');

//     // Format response
//     $machinesData = $machines->map(function ($machine) use ($domain) {
//         // Auto-handle manager_names JSON and image URL
//         return [
//             ...$machine->toArray(),
//             'manager_names' => $machine->manager_names ? json_decode($machine->manager_names, true) : [],
//             'image'         => $machine->image ? "{$domain}/{$machine->image}" : null,
//         ];
//     });

//     // JSON response
//     return response()->json([
//         'status' => 200,
//         'message' => 'Machine list fetched successfully',
//         'data' => $machinesData,
//         'pagination' => [
//             'total' => $machines->total(),
//             'per_page' => $machines->perPage(),
//             'current_page' => $machines->currentPage(),
//             'last_page' => $machines->lastPage(),
//             'next_page_url' => $machines->nextPageUrl(),
//             'prev_page_url' => $machines->previousPageUrl(),
//         ],
//     ]);
// }
public function getMachines(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $search = $request->get('search');

    $query = Machine::query();

    // Optional search filter
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // ✅ Paginate results
    $machines = $query->orderBy('id', 'asc')->paginate($perPage);

    // ✅ Get domain for image URLs
    $domain = rtrim(config('app.url'), '/');

    // ✅ Format each machine record
    $machinesData = $machines->map(function ($machine) use ($domain) {
        $employeeCount = \App\Models\Employee::where('machine_id', $machine->id)->count();

        // Handle manager_names safely (can be array or JSON string)
        $managerNames = $machine->manager_names;
        if (is_string($managerNames)) {
            $decoded = json_decode($managerNames, true);
            $managerNames = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($managerNames)) {
            $managerNames = [];
        }

        return [
            ...$machine->toArray(),
            'manager_names'  => $managerNames,
            'image'          => $machine->image ? "{$domain}/{$machine->image}" : null,
            'employee_count' => $employeeCount,
        ];
    });

    // ✅ Return JSON response
    return response()->json([
        'status' => 200,
        'message' => 'Machine list fetched successfully',
        'data' => $machinesData,
        'pagination' => [
            'total' => $machines->total(),
            'per_page' => $machines->perPage(),
            'current_page' => $machines->currentPage(),
            'last_page' => $machines->lastPage(),
            'next_page_url' => $machines->nextPageUrl(),
            'prev_page_url' => $machines->previousPageUrl(),
        ],
    ]);
}


//  public function getEmployeesByMachine(Request $request, $machine_id)
//     {
//         // ✅ Check if machine exists
//         $machine = Machine::find($machine_id);
//         if (!$machine) {
//             return response()->json([
//                 'status' => 404,
//                 'message' => 'Machine not found',
//             ], 404);
//         }

//         $perPage = (int) $request->get('per_page', 10);
//         $search  = $request->get('search');

//         // ✅ Build query
//         $query = Employee::where('machine_id', $machine_id);

//         // Optional search filter (by name, code, or mobile)
//         if (!empty($search)) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('name', 'like', "%{$search}%")
//                   ->orWhere('employee_code', 'like', "%{$search}%")
//                   ->orWhere('mobile', 'like', "%{$search}%");
//             });
//         }

//         // ✅ Paginate employees
//         $employees = $query->orderBy('id', 'desc')->paginate($perPage);

//         $domain = rtrim(config('app.url'), '/');

//         // ✅ Format each employee record
//         $employeesData = $employees->map(function ($employee) use ($domain) {
//             return [
//                 'id'            => $employee->id,
//                 'uuid'          => $employee->uuid,
//                 'employee_code' => $employee->employee_code,
//                 'name'          => $employee->name,
//                 'mobile'        => $employee->mobile,
//                 'gender'        => $employee->gender,
//                 'machine_id'    => $employee->machine_id,
//                 'machine'       => optional($employee->machine)->name ?? null,
//                 'salary_type'   => $employee->salary_type,
//                 'salary_monthly'=> $employee->salary_monthly,
//                 'salary_daily'  => $employee->salary_daily,
//                 'employee_type' => $employee->employee_type,
//                 'company_department' => $employee->company_department,
//                 'photo'         => $employee->photo,
//                 'fingerprint'   => $employee->fingerprint,
//                 'fingerprint_template_data'   => $employee->fingerprint_template_data,
//                 'attendance_status'   => $employee->attendance_status,
//                 'employee_work_title'   => $employee->employee_work_title,
//                 'joining_date'   => $employee->joining_date,
//                 'aadhar_card'   => $employee->aadhar_card,
//                 'created_at'    => $employee->created_at,
//                 'updated_at'    => $employee->updated_at,
//             ];
//         });

//         // ✅ Response
//         return response()->json([
//             'status' => 200,
//             'message' => 'Employee list fetched successfully',
//             'data' => $employeesData,
//             'pagination' => [
//                 'total' => $employees->total(),
//                 'per_page' => $employees->perPage(),
//                 'current_page' => $employees->currentPage(),
//                 'last_page' => $employees->lastPage(),
//                 'next_page_url' => $employees->nextPageUrl(),
//                 'prev_page_url' => $employees->previousPageUrl(),
//             ],
//         ]);
//     }
public function getEmployeesByMachine(Request $request, $machine_id)
{
    // Check if machine exists
    $machine = Machine::find($machine_id);
    if (!$machine) {
        return response()->json([
            'status' => 404,
            'message' => 'Machine not found',
        ], 404);
    }

    $perPage = (int) $request->get('per_page', 10);
    $search  = $request->get('search');

    $today = Carbon::today()->format('Y-m-d');

    // Build query
    $query = Employee::where('machine_id', $machine_id);

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('employee_code', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%");
        });
    }

    // Paginate employees
    $employees = $query->orderBy('id', 'desc')->paginate($perPage);

    // Prepare result
    $employeesData = $employees->map(function ($employee) use ($today) {

        // 🔥 Fetch today's attendance
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        $today_status = $attendance ? (int)$attendance->status : 0; // If not found → 0

        return [
            'id'            => $employee->id,
            'uuid'          => $employee->uuid,
            'employee_code' => $employee->employee_code,
            'name'          => $employee->name,
            'mobile'        => $employee->mobile,
            'gender'        => $employee->gender,
            'machine_id'    => $employee->machine_id,
            'machine'       => optional($employee->machine)->name ?? null,
            'salary_type'   => $employee->salary_type,
            'salary_monthly'=> $employee->salary_monthly,
            'salary_daily'  => $employee->salary_daily,
            'employee_type' => $employee->employee_type,
            'company_department' => $employee->company_department,
            'photo'         => $employee->photo,
            'fingerprint'   => $employee->fingerprint,
            'fingerprint_template_data'   => $employee->fingerprint_template_data,

            // 🔥 FIXED: Always return TODAY'S attendance status
            'attendance_status' => $today_status,

            'employee_work_title'   => $employee->employee_work_title,
            'joining_date'   => $employee->joining_date,
            'aadhar_card'    => $employee->aadhar_card,
            'created_at'     => $employee->created_at,
            'updated_at'     => $employee->updated_at,
        ];
    });

    return response()->json([
        'status' => 200,
        'message' => 'Employee list fetched successfully',
        'data' => $employeesData,
        'pagination' => [
            'total' => $employees->total(),
            'per_page' => $employees->perPage(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'next_page_url' => $employees->nextPageUrl(),
            'prev_page_url' => $employees->previousPageUrl(),
        ],
    ]);
}

public function getTotalCounts()
{
    return response()->json([
        'status' => true,
        'message' => 'Total counts fetched successfully',
        'data' => [
            'employees_count'   => Employee::count(),
            'machines_count'    => Machine::count(),
            'contractor_count'  => Contractor::count(),
            'attendance_count'  => 0, // today,
            'current_date'      => now()->format('Y-m-d'),
            'fingerprint_false_count' => Employee::where('fingerprint', false)->count(),
            // 'attendance_count'  => Attendance::whereDate('created_at', today())->count() // today
        ]
    ]);
}

public function getContractors(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $search = $request->get('search');

    $query = Contractor::with(['employees.shift']); // load employees + their shift

    // Search by name, mobile or email
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $contractors = $query->orderBy('id', 'asc')->paginate($perPage);

    $domain = rtrim(config('app.url'), '/');

    // Format contractor and employee data
    $contractorData = $contractors->map(function ($contractor) use ($domain) {

        // Convert contractor to array
        $data = $contractor->toArray();

        // Fix contractor image URL
        $data['self_photo'] = $contractor->self_photo
            ? "{$domain}/{$contractor->self_photo}"
            : null;

        // Employees under contractor
        $data['employees'] = $contractor->employees->map(function ($employee) use ($domain) {

            return [
                'id' => $employee->id,
                'uuid' => $employee->uuid,
                'employee_code' => $employee->employee_code,
                'name' => $employee->name,
                'mobile' => $employee->mobile,
                'gender' => $employee->gender,
                'photo' => $employee->photo ? "{$employee->photo}" : null,
                'fingerprint' => $employee->fingerprint ? "{$employee->fingerprint}" : null,
                'aadhar_card' => $employee->aadhar_card ? "{$employee->aadhar_card}" : null,

                // Shift details
                'shift' => $employee->shift ? [
                    'shift_name'     => $employee->shift->shift_name,
                    'clock_in_time'  => $employee->shift->clock_in_time,
                    'clock_out_time' => $employee->shift->clock_out_time,
                ] : null,
            ];
        });

        // Add total employee count
        $data['employee_count'] = $contractor->employees->count();

        return $data;
    });

    // Send API Response
    return response()->json([
        'status' => true,
        'message' => 'Contractor list fetched successfully',
        'total_contractor_count' => Contractor::count(),
        'data' => $contractorData,
        'pagination' => [
            'total' => $contractors->total(),
            'per_page' => $contractors->perPage(),
            'current_page' => $contractors->currentPage(),
            'last_page' => $contractors->lastPage(),
            'next_page_url' => $contractors->nextPageUrl(),
            'prev_page_url' => $contractors->previousPageUrl(),
        ],
    ]);
}

public function downloadContractorReport()
{
    // Fetch contractors + employees + shift
    $contractors = Contractor::with(['employees.shift'])->get();
    $generatedAt = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
    // Generate PDF using view
    $pdf = Pdf::loadView('reports.contractor_report', [
        'contractors' => $contractors,
        'generated_at' => $generatedAt,
    ])->setPaper('a4', 'portrait');

    // Download PDF
    return $pdf->download('contractor-report.pdf');
}
// public function downloadSingleContractorReport($contractor_id)
// {
//     // Fetch contractor + employees + shift
//     $contractor = Contractor::with(['employees.shift'])->find($contractor_id);

//     if (!$contractor) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Contractor not found',
//         ], 404);
//     }

//     $generatedAt = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');

//     // Load single-contractor PDF view
//     $pdf = Pdf::loadView('reports.contractor_report', [
//         'contractor' => $contractor,
//         'generated_at' => $generatedAt,
//     ])->setPaper('a4', 'portrait');

//     return $pdf->download('contractor-' . $contractor->id . '-report.pdf');
// }
public function downloadSingleContractorReport($contractor_id)
{
    $contractor = Contractor::with(['employees.shift'])->find($contractor_id);

    if (!$contractor) {
        return response()->json([
            'status' => false,
            'message' => 'Contractor not found',
        ], 404);
    }

    $generatedAt = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
    // MONTH + YEAR (default: current)
    $year  = request('year', now()->year);
    $month = request('month', now()->month);

    // ⭐ TOTAL DAYS OF MONTH
    $currentMonthDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;
    // -------------------------------------
    // EMPLOYEE ATTENDANCE + SALARY SUMMARY
    // -------------------------------------
    $summary = [];

    foreach ($contractor->employees as $emp) {

        $monthlySalary = $emp->salary ?? 0;         // if no salary → 0
        $perDayPay     = $monthlySalary > 0 ? ($monthlySalary / 30) : 0;

        $present   = Attendance::where('employee_id', $emp->id)
                       ->where('status', 1)
                       ->count();

        $leave     = Attendance::where('employee_id', $emp->id)
                       ->where('status', 0)
                       ->count();

        $halfDay   = Attendance::where('employee_id', $emp->id)
                       ->where('status', 2)
                       ->count();

        $salary_present = $present * $perDayPay;
        $salary_half    = ($halfDay * $perDayPay) / 2;
        $total_salary   = $salary_present + $salary_half;
        // ⭐ TOTAL DAYS (present + leave + half day)
        // $totalDays = $present + $leave + $halfDay;
        $summary[] = [
            'employee'       => $emp,
            'present'        => $present,
            'leave'          => $leave,
            'half_day'       => $halfDay,
            'per_day_pay'    => $perDayPay,
            'salary_present' => $salary_present,
            'salary_half'    => $salary_half,
            'total_salary'   => $total_salary,
            'month_days'     => $currentMonthDays,
            //  'total_days'     => $totalDays,
        ];
    }

    // LOAD PDF VIEW
    $pdf = Pdf::loadView('reports.contractor_report', [
        'contractor' => $contractor,
        'generated_at' => $generatedAt,
        'summary' => $summary,
         'month_days'   => $currentMonthDays,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('contractor-' . $contractor->id . '-report.pdf');
}

public function storeThumb(Request $request)
{
    try {
        $request->validate([
            'thumb_template_data' => 'required|array|min:1',
        ], [
            'thumb_template_data.required' => 'Thumb template data is required.',
            'thumb_template_data.array'    => 'Thumb template data must be a valid JSON array.',
            'thumb_template_data.min'      => 'Thumb template data cannot be empty.',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->errors()['thumb_template_data'][0] ?? 'Invalid input',
        ], 400); // <-- return 400 Bad Request
    }

    // Save to DB
    $record = ThumbMachineData::create([
        'thumb_template_data' => $request->thumb_template_data,
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Thumb template data saved successfully',
        'id'      => $record->id,
    ], 201);
}
public function getShifts()
{
    try {
        $shifts = Shift::select('id', 'shift_name', 'clock_in_time', 'clock_out_time','created_by')
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Shift list fetched successfully',
            'shifts' => $shifts
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function storeFingerprint(Request $request)
{
    try {
        $validated = $request->validate([
            'employee_id'   => 'required|integer|exists:employees,id',
            'template_data' => 'required|array',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    }

    $employee = Employee::findOrFail($validated['employee_id']);

    $employee->update([
        'fingerprint' => true,
        'fingerprint_template_data' => $validated['template_data'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Fingerprint saved successfully',
    ]);
}

public function nofingerprintData(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    $search = $request->get('search');

    $query = Employee::with(['user:id,name,email','shift'])
        ->where('fingerprint', false); // <- match string 'false'
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('email', 'like', "%{$search}%");
              });
        });
    }

    $employees = $query->orderBy('id', 'asc')->paginate($perPage);

    if ($employees->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ]);
    }

    $domain = rtrim(config('app.url'), '/');

    $employeesData = $employees->map(function ($employee) use ($domain) {
        $data = $employee->toArray();
        $data['photo'] = $employee->photo ? "{$domain}/{$employee->photo}" : null;
        $data['aadhar_card'] = $employee->aadhar_card ? "{$domain}/{$employee->aadhar_card}" : null;
        $data['user'] = $employee->user;
        $data['shift'] = $employee->shift ? [
            'shift_name' => $employee->shift->shift_name,
            'clock_in_time' => $employee->shift->clock_in_time,
            'clock_out_time' => $employee->shift->clock_out_time,
        ] : null;
        unset($data['shift_id']);
        return $data;
    });

    return response()->json([
        'status' => true,
        'message' => 'Employees without fingerprint fetched successfully',
        'data' => $employeesData,
        'pagination' => [
            'total' => $employees->total(),
            'per_page' => $employees->perPage(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'next_page_url' => $employees->nextPageUrl(),
            'prev_page_url' => $employees->previousPageUrl(),
        ],
    ]);
}
public function withfingerprintData(Request $request)
{
    // echo"helllo";die;
    $perPage = (int) $request->get('per_page', 10);
    $search = $request->get('search');

    $query = Employee::with(['user:id,name,email','shift'])
        ->where('fingerprint', true); // <- match string 'false'
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('email', 'like', "%{$search}%");
              });
        });
    }

    $employees = $query->orderBy('id', 'asc')->paginate($perPage);

    if ($employees->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ]);
    }

    $domain = rtrim(config('app.url'), '/');

    $employeesData = $employees->map(function ($employee) use ($domain) {
        $data = $employee->toArray();
        $data['photo'] = $employee->photo ? "{$domain}/{$employee->photo}" : null;
        $data['aadhar_card'] = $employee->aadhar_card ? "{$domain}/{$employee->aadhar_card}" : null;
        $data['user'] = $employee->user;
        $data['shift'] = $employee->shift ? [
            'shift_name' => $employee->shift->shift_name,
            'clock_in_time' => $employee->shift->clock_in_time,
            'clock_out_time' => $employee->shift->clock_out_time,
        ] : null;
        unset($data['shift_id']);
        return $data;
    });

    return response()->json([
        'status' => true,
        'message' => 'Employees without fingerprint fetched successfully',
        'data' => $employeesData,
        'pagination' => [
            'total' => $employees->total(),
            'per_page' => $employees->perPage(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'next_page_url' => $employees->nextPageUrl(),
            'prev_page_url' => $employees->previousPageUrl(),
        ],
    ]);
}
}
