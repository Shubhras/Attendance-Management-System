<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeId = 7; // Employee ID
        $month = '2025-11'; // Month to mark
        $daysInMonth = Carbon::parse($month . '-01')->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::parse($month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));

            // Skip weekends if you want
            if ($date->isWeekend()) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'date' => $date->format('Y-m-d'),
                ],
                [
                    'clock_in' => '09:00:00',
                    'clock_out' => '18:00:00',
                    'status' => 'present',
                    'marked_by' => 1, // Admin ID
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Attendance marked for employee ID '.$employeeId.' for '.$month);
    }
}
