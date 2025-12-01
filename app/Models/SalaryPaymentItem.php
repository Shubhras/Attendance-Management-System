<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPaymentItem extends Model
{
    protected $fillable = ['salary_payment_id','title','amount'];

    public function payment()
    {
        return $this->belongsTo(SalaryPayment::class, 'salary_payment_id');
    }
}
