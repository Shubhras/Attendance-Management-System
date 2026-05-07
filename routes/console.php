<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Existing inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ----------------------------
// Attendance Auto-Leave Cron
// ----------------------------

// Morning shift closes at 20:00 → run auto-leave at 21:00
Schedule::command('attendance:auto-leave')
    ->dailyAt('21:00');

// Night shift closes at 08:00 → run auto-leave at 09:00
Schedule::command('attendance:auto-leave')
    ->dailyAt('09:00');

// Employee Inactivity Deactivation Checker
Schedule::command('app:deactivate-inactive-employees')
    ->daily();
