<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Contractor extends Model
{
    use SoftDeletes;

    protected $table = 'contractors';

    protected $fillable = [
        'uuid',
        'name',
        'mobile',
        'email',
        'govid',
        'dob',
        'gender',
        'address',
        'self_photo',
        'company_name',
        'code_start',
        'code_end',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // Use uuid for route model binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }

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

        // For soft deletes, set deleted_by before deletion and save quietly.
        static::deleting(function ($model) {
            // only for soft-delete (not forceDelete)
            if (! $model->isForceDeleting()) {
                if (Auth::check()) {
                    $model->deleted_by = Auth::id();
                    // Save without firing events to avoid loop.
                    $model->saveQuietly();
                }
            }
        });
    }
    public function employees()
{
    return $this->hasMany(Employee::class, 'contractor_id');
}
}
