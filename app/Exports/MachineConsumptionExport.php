<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MachineConsumptionExport implements FromCollection, WithHeadings
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate   = $toDate;
    }

    public function collection()
    {
        return DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->join('machines', 'employees.machine_id', '=', 'machines.id')
            ->whereBetween('attendances.date', [$this->fromDate, $this->toDate])
            ->whereIn('attendances.status', [1, 2])
            ->select(
                'machines.name as machine_name',
                DB::raw('COUNT(DISTINCT employees.id) as total_employees'),

                DB::raw("
                    ROUND(SUM(
                        CASE
                            WHEN employees.salary_type = 'daily' THEN
                                employees.salary_daily *
                                CASE
                                    WHEN attendances.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            WHEN employees.salary_type = 'monthly' THEN
                                (employees.salary_monthly / DAY(LAST_DAY(attendances.date))) *
                                CASE
                                    WHEN attendances.status = 2 THEN 0.5
                                    ELSE 1
                                END
                            ELSE 0
                        END
                    ), 2) as total_consumption
                ")
            )
            ->groupBy('machines.id', 'machines.name')
            ->orderBy('machines.name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Machine Name',
            'Present Employees',
            'Total Consumption (₹)',
        ];
    }
}