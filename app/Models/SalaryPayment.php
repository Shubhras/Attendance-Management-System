<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    protected $fillable = [
        'month','date_paid','employee_id','created_by','gross_amount','deductions','net_amount','notes','payment_method'
    ];

    public function items()
    {
        return $this->hasMany(SalaryPaymentItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
