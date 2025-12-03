<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OperatorAuthController;
use App\Http\Controllers\Api\OperatorAttendanceController;
use App\Http\Controllers\Api\AdminAuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::post('operator/login', [OperatorAuthController::class, 'login']);
Route::post('operator/login', [OperatorAuthController::class, 'login'])->name('login');
// Route::post('login', [OperatorAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('employee/shifts', [OperatorAuthController::class, 'getShifts']);
    Route::get('/employees', [OperatorAuthController::class, 'getEmployees']);
    Route::get('/employees/{uuid}', [OperatorAuthController::class, 'getEmployeeDetails']);
    Route::get('/get-machines', [OperatorAuthController::class, 'getMachines']);
    Route::get('/get-machines/{machine_id}/employees', [OperatorAuthController::class, 'getEmployeesByMachine']);
    Route::get('/total-counts', [OperatorAuthController::class, 'getTotalCounts']);
    Route::get('/get-contractors-employee', [OperatorAuthController::class, 'getContractors']);
    Route::get('/contractors/report/download', [OperatorAuthController::class, 'downloadContractorReport']);
    Route::get('/contractors/{id}/download', [OperatorAuthController::class, 'downloadSingleContractorReport']);
    Route::post('/thumb-machine/store', [OperatorAuthController::class, 'storeThumb']);

        // Operator side
    Route::get('/operator/employees', [OperatorAttendanceController::class, 'assignedEmployees']);
    Route::post('/operator/attendance/mark', [OperatorAttendanceController::class, 'markAttendance']);
    //Route::post('/operator/attendance/bulk', [OperatorAttendanceController::class, 'bulkMarkAttendance']);

    // Reports
    Route::get('/operator/attendance/list', [OperatorAttendanceController::class, 'attendanceList']);
    //Route::get('/operator/attendance/pdf/all', [OperatorAttendanceController::class, 'exportAll']);
    Route::get('/operator/attendance/pdf/{employee_id}', [OperatorAttendanceController::class, 'exportEmployee']);

// Fingerprint store route
Route::post('operator/fingerprint/store', [OperatorAuthController::class, 'storeFingerprint']);
// Route::post('attendance/fingerprint/verify', [AttendanceController::class, 'verifyFingerprint']);
Route::get('/no-fingerprint/get', [OperatorAuthController::class, 'nofingerprintData']);
Route::get('/with-fingerprint/get', [OperatorAuthController::class, 'withfingerprintData']);

});
// Admin Login Routes
    // Route::post('/admin/login', [AdminAuthController::class, 'login']);
    // Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
    // Route::post('operator/logout', [OperatorAuthController::class, 'logout'])->middleware('auth:sanctum');
