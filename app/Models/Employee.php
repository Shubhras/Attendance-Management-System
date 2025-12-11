<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'mobile',
        'govid',
        'dob',
        'gender',
        'photo',
        'fingerprint',
        'fingerprint_template_data',
        'contractor_id',
        //'machine',
        'user_id',
        'machine_id',
        'shift_id',
        'salary_type',
        'salary_daily', 
        'salary_monthly',
        'employee_type',
        'company_department',
        'employee_work_title',
        'attendance_status',
        'joining_date',
        'is_operator',
        'is_hr',
        'employee_code',
        'aadhar_card',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'dob' => 'date',
        //'salary_monthly' => 'decimal:2',
        'salary_monthly' => 'decimal:2',
        'salary_daily' => 'decimal:2',
        'fingerprint_template_data' => 'array',
         'machine_id' => 'integer',
    ];

    // ✅ Use UUID for route model binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }
public function shift()
{
    return $this->belongsTo(Shift::class, 'shift_id');
}
    // ✅ Relationships
    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    // public function user() {
    //     return $this->belongsTo(User::class);
    // }
    // ✅ Auto-set uuid, created_by, deleted_by
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            if (empty($model->created_by) && Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            // Set deleted_by before soft deleting
            if (! $model->isForceDeleting()) {
                if (Auth::check()) {
                    $model->deleted_by = Auth::id();
                    $model->saveQuietly();
                }
            }
        });
    }
            // ✅ Accessors to return full URLs for image paths
    public function getPhotoAttribute($value)
    {
        return $value ? url($value) : null;
    }

    // public function getFingerprintAttribute($value)
    // {
    //     return $value ? url($value) : null;
    // }

    public function getAadharCardAttribute($value)
    {
        return $value ? url($value) : null;
    }
// public function machine()
// {
//     return $this->belongsTo(Machine::class, 'machine_id');
// }
public function machine()
{
    return $this->belongsTo(Machine::class, 'machine_id')->withTrashed();
}
    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
public function machineRelation()
{
    return $this->belongsTo(Machine::class, 'machine_id', 'id')->withTrashed();
}
}
