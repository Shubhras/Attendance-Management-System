<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Machine extends Model
{
    use SoftDeletes;

    protected $table = 'machines';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'image',
        'manager_names',
        'created_by',
        'deleted_by',
    ];

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
            // soft-delete only
            if (! $model->isForceDeleting()) {
                if (Auth::check()) {
                    $model->deleted_by = Auth::id();
                    $model->saveQuietly();
                }
            }
        });
    }

    // Use uuid for route model binding
    public function getRouteKeyName()
    {
        return 'uuid';
    }
    protected $casts = [
    'manager_names' => 'array',
    ];
            // Automatically return [] if null
    public function getManagerNamesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
        public function employees()
    {
        return $this->hasMany(Employee::class, 'machine_id');
    }
}
