<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoMarkLeave extends Command
{
    protected $signature = 'attendance:auto-leave';
    protected $description = 'Auto mark leave for employees who did not scan any slot';

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');

        $employees = Employee::all();

        foreach ($employees as $emp) {

            $record = Attendance::where('employee_id', $emp->id)
                ->whereDate('date', $today)
                ->first();

            if (!$record) {
                Attendance::create([
                    'employee_id' => $emp->id,
                    'machine_id'  => null,
                    'date'        => $today,
                    'status'      => 0, // leave
                    'slot1'       => null,
                    'slot2'       => null,
                    'slot3'       => null,
                    'scan_status' => 0,
                ]);
            }
        }

        $this->info("Leave auto-marked for all missing employees.");
    }
}

