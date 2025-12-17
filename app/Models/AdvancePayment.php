<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    //     protected $fillable = [
    //     'employee_id',
    //     'contractor_id',
    //     'machine_id',
    //     'hr_id',
    //     'employee_type',
    //     'amount',
    //     'reason',
    //     'thumb_verified',
    //     'paid_at'
    // ];
    protected $fillable = [
        'employee_id','contractor_id','machine_id','hr_id',
        'employee_type','amount','reason','paid_at',
        'is_settled','settled_salary_id'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function contractor() {
        return $this->belongsTo(Contractor::class);
    }

    public function machine() {
        return $this->belongsTo(Machine::class);
    }

    public function hr() {
        return $this->belongsTo(User::class, 'hr_id');
    }
}


