<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThumbMachineData extends Model
{
    protected $table = 'thumb_machine_data';

    protected $fillable = [
        'thumb_template_data'
    ];

    protected $casts = [
        'thumb_template_data' => 'array'
    ];
}
