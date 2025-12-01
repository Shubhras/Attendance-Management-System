<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class BiometricController extends Controller
{
    public function receiveScan(Request $request)
    {
        // very simple token auth — replace with stronger auth in prod
        $token = $request->header('X-Device-Token');
        if ($token !== env('BIOMETRIC_DEVICE_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'device_serial' => 'nullable|string',
            'device_uid'    => 'nullable|string',
            'user_id'       => 'nullable|integer',
            'template'      => 'nullable|string',
            'scanned_at'    => 'required|date',
            'scan_type'     => 'nullable|string',
        ]);

        $attendance = Attendance::create(array_merge($data, [
            'remote_ip' => $request->ip(),
        ]));

        return response()->json(['success' => true, 'id' => $attendance->id]);
    }
}
