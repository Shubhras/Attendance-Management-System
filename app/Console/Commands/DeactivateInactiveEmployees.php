<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DeactivateInactiveEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-inactive-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates employees who have not marked attendance in the last 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Date 30 days ago at start of day
        $thresholdDate = Carbon::now()->subDays(30)->startOfDay();
        
        $this->info("Checking for employees inactive since " . $thresholdDate->toDateString());
        
        $count = 0;

        Employee::where('is_active', true)->chunk(100, function($employees) use ($thresholdDate, &$count) {
            foreach($employees as $employee) {
                // Find latest attendance date
                $lastAttendance = Attendance::where('employee_id', $employee->id)->latest('date')->first();
                
                $shouldDeactivate = false;

                if ($lastAttendance) {
                    // Check if last attendance is older than 30 days
                    if (Carbon::parse($lastAttendance->date)->startOfDay() < $thresholdDate) {
                        $shouldDeactivate = true;
                    }
                } else {
                    // No attendance record found.
                    // Check if employee was created or joined more than 30 days ago.
                    $compareDate = $employee->joining_date ? Carbon::parse($employee->joining_date) : $employee->created_at;
                    if ($compareDate && $compareDate->startOfDay() < $thresholdDate) {
                        $shouldDeactivate = true;
                    }
                }

                if ($shouldDeactivate) {
                    $employee->update(['is_active' => false]);
                    $this->line("Deactivated employee ID: {$employee->id} (Code: {$employee->employee_code})");
                    $count++;
                }
            }
        });

        $this->info("Completed. Deactivated $count employee(s).");
    }
}
