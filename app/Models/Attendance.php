<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id','machine_id','date','slot1',
    'slot2',
    'slot3','shift_type','clock_in','clock_out','status',
        'fingerprint_template','scan_response','marked_by'
    ];

    protected $casts = [
        'fingerprint_template' => 'array',
        'scan_response' => 'array',
        'date' => 'datetime',
    ];

    // public function employee() {
    //     return $this->belongsTo(\App\Models\Employee::class);
    // }
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}
    public function marker() {
        return $this->belongsTo(\App\Models\User::class, 'marked_by');
    }
    public function getMarkedByUserAttribute() {
    return $this->marker;
}
public function machine()
{
    return $this->belongsTo(Machine::class, 'machine_id');
}
}
