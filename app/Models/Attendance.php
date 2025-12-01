<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id','date','clock_in','clock_out','status',
        'fingerprint_template','scan_response','marked_by'
    ];

    protected $casts = [
        'fingerprint_template' => 'array',
        'scan_response' => 'array',
        'date' => 'date',
    ];

    public function employee() {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function marker() {
        return $this->belongsTo(\App\Models\User::class, 'marked_by');
    }
}
